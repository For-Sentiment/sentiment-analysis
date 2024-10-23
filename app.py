import pandas as pd
import re
from flask import Flask, request, jsonify
from flask_cors import CORS
from transformers import pipeline
import gc

# Initialize Flask app and CORS
app = Flask(__name__)
CORS(app)

# Initialize sentiment analysis model using a lighter model
sentiment_pipeline = pipeline("sentiment-analysis", model="nlptown/bert-base-multilingual-uncased-sentiment")

# Route to analyze sentiment from scraped Facebook comments
@app.route('/analyze', methods=['POST'])
def analyze():
    """Handles Facebook post URL submission and performs sentiment analysis on comments."""
    data = request.json
    post_url = data.get('url')

    if not post_url:
        return jsonify({'error': 'No URL provided'}), 400

    # Call the scrape function from Service 1
    comments = scrape_facebook_comments(post_url)  # Ensure this function is imported from Service 1
    if not comments:
        return jsonify({'error': 'Failed to retrieve comments.'}), 500

    # Perform sentiment analysis in batches to save memory
    analyzed_comments = []
    for comment in comments:
        cleaned_comment = clean_comment(comment)  # This should call the clean_comment function from Service 1
        result = sentiment_pipeline(cleaned_comment)[0]
        sentiment_label = "Positive" if result['label'] == '5 stars' else "Neutral" if result['label'] == '3 stars' else "Negative"
        emoji = '😊' if sentiment_label == 'Positive' else '😐' if sentiment_label == 'Neutral' else '😠'
        analyzed_comments.append({'text': cleaned_comment, 'sentiment': sentiment_label, 'emoji': emoji})

    gc.collect()  # Call garbage collection
    return jsonify({'comments': analyzed_comments})

if __name__ == '__main__':
    app.run(debug=True)
