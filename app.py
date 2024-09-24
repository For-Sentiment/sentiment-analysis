import pandas as pd
import re
from flask import Flask, request, jsonify
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
import os
from flask_cors import CORS
import requests  # Added to support HTTP requests

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

analyzer = SentimentIntensityAnalyzer()

def clean_comment(comment):
    comment = re.sub(r'[^\w\s]', '', comment)  # Remove special characters
    comment = re.sub(r'\b(?:ha)+\b|\b(?:h[ue]){2,}\b', '', comment, flags=re.IGNORECASE)  # Remove repeated laughs and cries
    comment = re.sub(r'\s+', ' ', comment).strip()  # Remove extra spaces
    return comment

@app.route('/analyze', methods=['POST'])
def analyze_sentiment():
    try:
        comment = request.json.get('comment', '')
        comment = comment.lower()
        comment = re.sub(r'[^\w\s]', '', comment)
        sentiment_scores = analyzer.polarity_scores(comment)
        compound_score = sentiment_scores['compound']
        if compound_score >= 0.05:
            sentiment = "POSITIVE"
        elif compound_score <= -0.05:
            sentiment = "NEGATIVE"
        else:
            sentiment = "NEUTRAL"
        return jsonify({"sentiment": sentiment, "comment": comment})
    except Exception as e:
        return jsonify({'error': str(e)})

@app.route('/upload_csv', methods=['POST'])
def upload_csv():
    try:
        if 'file' not in request.files:
            return jsonify({'error': 'No file part'})
        file = request.files['file']
        if file.filename == '':
            return jsonify({'error': 'No selected file'})
        file_path = os.path.join('uploads', file.filename)
        os.makedirs(os.path.dirname(file_path), exist_ok=True)
        file.save(file_path)
        df = pd.read_csv(file_path)
        if 'Comment' not in df.columns:
            return jsonify({'error': 'No "Comment" column found in the file'})
        results = []
        for comment in df['Comment'].dropna():
            cleaned_comment = clean_comment(comment)
            sentiment_scores = analyzer.polarity_scores(cleaned_comment)
            compound_score = sentiment_scores['compound']
            if compound_score >= 0.05:
                sentiment = "POSITIVE"
                emoji = '😊'
            elif compound_score <= -0.05:
                sentiment = "NEGATIVE"
                emoji = '😠'
            else:
                sentiment = "NEUTRAL"
                emoji = '😐'
            results.append({
                'comment': cleaned_comment,
                'sentiment': sentiment,
                'emoji': emoji,
                'score': compound_score
            })
        return jsonify({'results': results})
    except Exception as e:
        return jsonify({'error': str(e)})

# Inserted new Facebook comments fetch route
@app.route('/fetch_facebook_comments', methods=['POST'])
def fetch_facebook_comments():
    try:
        access_token = 'EAAGZCpxXYvfcBO5HqcSWZAzaOJjzEmGYSWCmUgAjpzIXD37SvjZCXZAqXRZCNAIdeS454bP0sDVr53qMJkZB0s7IhCaN8RbmaRzkdPIu26JmOnTHaSSqfIgtWqlZAO94ZBBE0ZBN0rBWmz1TLov6UWPLmeJBlgJeIHZBanvGUtiMsjLAfGnZBZAftZCp65TOTxGOneTQsnS1uvuTyZAOuCDtCw5QZDZD'  # Replace with your valid access token
        post_id = request.json.get('post_id')
        if not post_id:
            return jsonify({'error': 'No post_id provided'}), 400
        url = f'https://graph.facebook.com/v20.0/{post_id}?fields=comments&access_token={access_token}'
        response = requests.get(url)
        if response.status_code != 200:
            return jsonify({'error': f'Error: {response.status_code} - {response.json()}'})
        comments_data = response.json()
        comments = []
        for comment in comments_data.get('comments', {}).get('data', []):
            if 'message' in comment:
                comments.append(comment['message'])
        results = []
        for comment in comments:
            cleaned_comment = clean_comment(comment)
            sentiment_scores = analyzer.polarity_scores(cleaned_comment)
            compound_score = sentiment_scores['compound']
            if compound_score >= 0.05:
                sentiment = "POSITITVE"
                emoji = '😊'
            elif compound_score <= -0.05:
                sentiment = "NEGATIVE"
                emoji = '😠'
            else:
                sentiment = "NEUTRAL"
                emoji = '😐'
            results.append({
                'comment': cleaned_comment,
                'sentiment': sentiment,
                'emoji': emoji,
                'score': compound_score
            })
        return jsonify({'results': results})
    except Exception as e:
        return jsonify({'error': str(e)})

if __name__ == '__main__':
    app.run(debug=True)
