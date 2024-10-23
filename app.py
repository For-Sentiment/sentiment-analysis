import pandas as pd
import re
import os
import csv
from io import StringIO
from flask import Flask, request, jsonify, make_response
from playwright.sync_api import sync_playwright
from flask_cors import CORS
from transformers import pipeline
import emoji  # Ensure you have this library installed if you plan to use it


# Ensure Chromium is installed
subprocess.run(["playwright", "install", "chromium"], check=True)
# Initialize Flask app and CORS
app = Flask(__name__)
CORS(app)  # This will allow all domains. You can restrict it to specific domains if needed.

# Initialize sentiment analysis model using transformers
sentiment_pipeline = pipeline("sentiment-analysis", model="nlptown/bert-base-multilingual-uncased-sentiment")

# Helper function to clean individual comments
def clean_comment(comment):
    """Cleans Facebook comments by removing mentions, timestamps, and other unnecessary text."""
    comment = re.sub(r'@\w+', '', comment)  # Remove mentions
    comment = re.sub(r'Top\s*Fan\s+[A-Za-z\s]+', '', comment, flags=re.IGNORECASE)  # Remove "Top Fan"
    comment = re.sub(r'\d+\s+(minute|hour|day|week|month|year)s?\s*ago|\d+[a-z]', '', comment, flags=re.IGNORECASE)
    comment = re.sub(r'[^\w\s]', '', comment)  # Remove special characters (punctuation)
    comment = emoji.replace_emoji(comment, replace='')  # Remove emojis
    comment = ' '.join(comment.split()).strip()  # Remove excess whitespace
    return comment

# Route to analyze sentiment from scraped Facebook comments
@app.route('/analyze', methods=['POST'])
def analyze():
    """Handles Facebook post URL submission and performs sentiment analysis on comments."""
    data = request.json
    post_url = data.get('url')

    if not post_url:
        return jsonify({'error': 'No URL provided'}), 400

    # Scrape comments from Facebook post (implement scrape_facebook_comments function here)
    comments = scrape_facebook_comments(post_url)
    if not comments:
        return jsonify({'error': 'Failed to retrieve comments.'}), 500

    # Perform sentiment analysis using transformers for multilingual support
    analyzed_comments = []
    for comment in comments:
        result = sentiment_pipeline(comment)[0]
        label = result['label']
        sentiment_label = "Positive" if label == '5 stars' else "Neutral" if label == '3 stars' else "Negative"
        emoji_char = '😊' if sentiment_label == 'Positive' else '😐' if sentiment_label == 'Neutral' else '😠'

        analyzed_comments.append({
            'text': comment,
            'sentiment': sentiment_label,
            'emoji': emoji_char
        })

    return jsonify({'comments': analyzed_comments})

# New route to analyze a single comment
@app.route('/analyze_comment', methods=['POST'])
def analyze_comment():
    """Analyzes a single comment and returns its sentiment."""
    try:
        comment = request.json.get('comment', '')
        cleaned_comment = clean_comment(comment)

        # Perform sentiment analysis using transformers
        result = sentiment_pipeline(cleaned_comment)[0]
        label = result['label']
        sentiment = "Positive" if label == '5 stars' else "Neutral" if label == '3 stars' else "Negative"

        return jsonify({"sentiment": sentiment, "comment": cleaned_comment})
    except Exception as e:
        return jsonify({'error': str(e)})

# Route to upload CSV and analyze comments
@app.route('/upload_csv', methods=['POST'])
def upload_csv():
    """Uploads a CSV file and performs sentiment analysis on its comments."""
    try:
        if 'file' not in request.files:
            return jsonify({'error': 'No file part'}), 400

        file = request.files['file']
        if file.filename == '':
            return jsonify({'error': 'No selected file'}), 400

        # Save the uploaded file
        file_path = os.path.join('uploads', file.filename)
        os.makedirs(os.path.dirname(file_path), exist_ok=True)
        file.save(file_path)

        # Read and analyze comments from the CSV
        df = pd.read_csv(file_path)
        if 'Comment' not in df.columns:
            return jsonify({'error': 'No "Comment" column found in the file'}), 400

        results = []
        for comment in df['Comment'].dropna():
            cleaned_comment = clean_comment(comment)

            # Perform sentiment analysis using transformers
            result = sentiment_pipeline(cleaned_comment)[0]
            label = result['label']
            sentiment = 'Positive' if label == '5 stars' else 'Negative' if label == '1 star' else 'Neutral'
            emoji_char = '😊' if sentiment == 'Positive' else '😠' if sentiment == 'Negative' else '😐'

            results.append({
                'comment': cleaned_comment,
                'sentiment': sentiment,
                'emoji': emoji_char,
                'score': result['score']
            })

        return jsonify({'results': results})

    except Exception as e:
        return jsonify({'error': str(e)})

# Route to download analyzed comments as a CSV
@app.route('/download_csv', methods=['POST'])
def download_csv():
    """Downloads analyzed comments as a CSV file."""
    data = request.json
    comments = data.get('comments')

    output = StringIO()
    writer = csv.DictWriter(output, fieldnames=['Comment', 'Sentiment', 'Emoji'])
    writer.writeheader()

    for comment in comments:
        writer.writerow({
            'Comment': comment['text'],
            'Sentiment': comment['sentiment'],
            'Emoji': comment['emoji']
        })

    output.seek(0)
    response = make_response(output.getvalue())
    response.headers["Content-Disposition"] = "attachment; filename=comments_analysis.csv"
    response.headers["Content-type"] = "text/csv"

    return response

if __name__ == '__main__':
    app.run(debug=True)
