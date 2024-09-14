import pandas as pd
import re
from flask import Flask, request, jsonify
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
import os
from flask_cors import CORS

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "https://nlp.infinityfreeapp.com"}})

analyzer = SentimentIntensityAnalyzer()
# Existing route for typed comments
@app.route('/analyze', methods=['POST'])
def analyze_sentiment():
    comment = request.json.get('comment')

    # Normalize the comment
    comment = comment.lower()  # Convert to lowercase
    comment = re.sub(r'[^\w\s]', '', comment)  # Remove special characters

    sentiment_scores = analyzer.polarity_scores(comment)
    compound_score = sentiment_scores['compound']

    if compound_score >= 0.05:
        sentiment = "positive"
    elif compound_score <= -0.05:
        sentiment = "negative"
    else:
        sentiment = "neutral"

    return jsonify({"sentiment": sentiment, "comment": comment})

def clean_comment(comment):
    # Remove emojis, special characters, and repeated expressions like "haha", "huhu", "hehe"
    comment = re.sub(r'[^\w\s]', '', comment)  # Remove special characters
    comment = re.sub(r'\b(?:ha)+\b|\b(?:h[ue]){2,}\b', '', comment, flags=re.IGNORECASE)  # Remove repeated laughs and cries
    comment = re.sub(r'\s+', ' ', comment).strip()  # Remove extra spaces
    return comment

@app.route('/upload_csv', methods=['POST'])
def upload_csv():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'})

    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'})

    # Save the uploaded file to a temporary location
    file_path = os.path.join('uploads', file.filename)
    os.makedirs(os.path.dirname(file_path), exist_ok=True)
    file.save(file_path)

    try:
        # Read the file
        df = pd.read_csv(file_path)

        # Check if the required column is present
        if 'Comment' not in df.columns:
            return jsonify({'error': 'No "Comment" column found in the file'})

        results = []
        for comment in df['Comment'].dropna():  # Drop any NaN values
            comment = str(comment).strip()  # Convert to string and strip whitespace
            cleaned_comment = clean_comment(comment)  # Clean the comment

            # Analyze the sentiment
            sentiment_scores = analyzer.polarity_scores(cleaned_comment)
            compound_score = sentiment_scores['compound']

            if compound_score >= 0.05:
                sentiment = "positive"
                emoji = '😊'
            elif compound_score <= -0.05:
                sentiment = "negative"
                emoji = '😠'
            else:
                sentiment = "neutral"
                emoji = '😐'

            results.append({
                'comment': cleaned_comment,  # Add cleaned comment
                'sentiment': sentiment,
                'emoji': emoji,
                'score': compound_score
            })

        return jsonify({'results': results})

    except Exception as e:
        return jsonify({'error': str(e)})


if __name__ == '__main__':
    app.run(debug=True)
