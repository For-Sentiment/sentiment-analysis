from flask import Flask, request, jsonify
from flask_cors import CORS
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
import re  # Import the 're' module for regular expressions

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes
analyzer = SentimentIntensityAnalyzer()


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


if __name__ == '__main__':
    app.run(debug=True)
