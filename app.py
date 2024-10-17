import pandas as pd
import re
import os
import csv
import time
from io import StringIO
from flask import Flask, request, jsonify, make_response
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
from flask_cors import CORS
from playwright.sync_api import sync_playwright
import requests

# Initialize Flask app and CORS
app = Flask(__name__)
CORS(app)  # This will allow all domains. You can restrict it to specific domains if needed.

# Initialize sentiment analyzer
analyzer = SentimentIntensityAnalyzer()

# Helper function to clean individual comments
def clean_comment(comment):
    """Cleans Facebook comments by removing mentions, timestamps, and other unnecessary text."""
    comment = re.sub(r'@\w+', '', comment)  # Remove mentions
    comment = re.sub(r'Top\s*Fan\s+[A-Za-z\s]+', '', comment, flags=re.IGNORECASE)  # Remove "Top Fan"
    comment = re.sub(r'\d+\s+(minute|hour|day|week|month|year)s?\s*ago|\d+[a-z]', '', comment, flags=re.IGNORECASE)
    return ' '.join(comment.split()).strip()

# Facebook comments scraper using Playwright
def scrape_facebook_comments(post_url):
    """Scrapes comments from a Facebook post using Playwright."""
    start_time = time.time()
    comments = set()  # Use a set to avoid duplicates

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        page.goto(post_url)

        # Wait for the comments section to load
        page.wait_for_selector('div[role="article"]')
        print(f"Page loaded in {time.time() - start_time:.2f} seconds.")

        last_height = 0
        while True:
            # Scroll to the bottom of the page
            page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
            time.sleep(3)  # Adjust based on network speed

            # Extract comments
            comment_elements = page.query_selector_all(
                'div[role="article"] div[dir="auto"]:not([role="presentation"])'
            )
            for element in comment_elements:
                comment_text = element.inner_text()
                if comment_text:
                    cleaned_text = clean_comment(comment_text)
                    comments.add(cleaned_text)

            # Click "Load more comments" if available
            load_more = page.query_selector('div[role="button"]:has-text("Load more comments")')
            if load_more:
                load_more.click()
                time.sleep(3)
            else:
                break  # No more comments to load

            # Check if the page has scrolled to the bottom
            new_height = page.evaluate("document.body.scrollHeight")
            if new_height == last_height:
                break
            last_height = new_height

        browser.close()
        return list(comments)

# Route to analyze sentiment from scraped Facebook comments
@app.route('/analyze', methods=['POST'])
def analyze():
    """Handles Facebook post URL submission and performs sentiment analysis on comments."""
    data = request.json
    post_url = data.get('url')

    if not post_url:
        return jsonify({'error': 'No URL provided'}), 400

    # Scrape comments from Facebook post
    comments = scrape_facebook_comments(post_url)
    if not comments:
        return jsonify({'error': 'Failed to retrieve comments.'}), 500

    # Perform sentiment analysis
    analyzed_comments = []
    for comment in comments:
        sentiment_scores = analyzer.polarity_scores(comment)
        score = sentiment_scores['compound']
        emoji = '😊' if score > 0 else ('😐' if score == 0 else '😠')
        sentiment_label = 'Positive' if score > 0 else ('Neutral' if score == 0 else 'Negative')

        analyzed_comments.append({
            'text': comment,
            'sentiment': sentiment_label,
            'emoji': emoji
        })

    return jsonify({'comments': analyzed_comments})

# New route to analyze a single comment
@app.route('/analyze_comment', methods=['POST'])
def analyze_comment():
    """Analyzes a single comment and returns its sentiment."""
    try:
        comment = request.json.get('comment', '')
        comment = comment.lower()
        comment = re.sub(r'[^\w\s]', '', comment)  # Remove punctuation
        sentiment_scores = analyzer.polarity_scores(comment)
        compound_score = sentiment_scores['compound']

        if compound_score >= 0.05:
            sentiment = "positive"
        elif compound_score <= -0.05:
            sentiment = "negative"
        else:
            sentiment = "neutral"

        return jsonify({"sentiment": sentiment, "comment": comment})
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
            sentiment_scores = analyzer.polarity_scores(cleaned_comment)
            compound_score = sentiment_scores['compound']
            sentiment = 'positive' if compound_score >= 0.05 else 'negative' if compound_score <= -0.05 else 'neutral'
            emoji = '😊' if sentiment == 'positive' else '😠' if sentiment == 'negative' else '😐'

            results.append({
                'comment': cleaned_comment,
                'sentiment': sentiment,
                'emoji': emoji,
                'score': compound_score
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
