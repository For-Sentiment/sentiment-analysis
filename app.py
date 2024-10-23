import pandas as pd
import re
import os
import csv
from io import StringIO
from flask import Flask, request, jsonify, make_response
from flask_cors import CORS
from transformers import pipeline
import gc

# Initialize Flask app and CORS
app = Flask(__name__)
CORS(app)

# Initialize sentiment analysis model using a lighter model
sentiment_pipeline = pipeline("sentiment-analysis", model="nlptown/bert-base-multilingual-uncased-sentiment")

# Helper function to clean individual comments
def clean_comment(comment):
    """Cleans Facebook comments by removing mentions, timestamps, and other unnecessary text."""
    comment = re.sub(r'@\w+', '', comment)
    comment = re.sub(r'Top\s*Fan\s+[A-Za-z\s]+', '', comment, flags=re.IGNORECASE)
    comment = re.sub(r'\d+\s+(minute|hour|day|week|month|year)s?\s*ago|\d+[a-z]', '', comment, flags=re.IGNORECASE)
    comment = ' '.join(comment.split()).strip()
    return comment

# Route to analyze sentiment from scraped Facebook comments
@app.route('/analyze', methods=['POST'])
def analyze():
    """Handles Facebook post URL submission and performs sentiment analysis on comments."""
    data = request.json
    post_url = data.get('url')

    if not post_url:
        return jsonify({'error': 'No URL provided'}), 400

    comments = scrape_facebook_comments(post_url)
    if not comments:
        return jsonify({'error': 'Failed to retrieve comments.'}), 500

    # Perform sentiment analysis in batches to save memory
    analyzed_comments = []
    for comment in comments:
        cleaned_comment = clean_comment(comment)
        result = sentiment_pipeline(cleaned_comment)[0]
        sentiment_label = "Positive" if result['label'] == '5 stars' else "Neutral" if result['label'] == '3 stars' else "Negative"
        emoji = '😊' if sentiment_label == 'Positive' else '😐' if sentiment_label == 'Neutral' else '😠'
        analyzed_comments.append({'text': cleaned_comment, 'sentiment': sentiment_label, 'emoji': emoji})

    gc.collect()  # Call garbage collection
    return jsonify({'comments': analyzed_comments})

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
            result = sentiment_pipeline(cleaned_comment)[0]
            sentiment = 'Positive' if result['label'] == '5 stars' else 'Negative' if result['label'] == '1 star' else 'Neutral'
            emoji = '😊' if sentiment == 'Positive' else '😠' if sentiment == 'Negative' else '😐'
            results.append({'comment': cleaned_comment, 'sentiment': sentiment, 'emoji': emoji})

        gc.collect()  # Call garbage collection
        return jsonify({'results': results})

    except Exception as e:
        return jsonify({'error': str(e)})

if __name__ == '__main__':
    app.run(debug=True)
