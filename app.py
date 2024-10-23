from flask import Flask, request, jsonify
from flask_cors import CORS
from flair.models import TextClassifier
from flair.data import Sentence
from service1 import scrape_facebook_comments  # Import the scraping function

# Initialize Flask app and CORS
app = Flask(__name__)
CORS(app)

# Load the sentiment analysis model
classifier = TextClassifier.load('sentiment')  # Use a multilingual model if available

@app.route('/analyze', methods=['POST'])
def analyze():
    """Handles Facebook post URL submission and performs sentiment analysis on comments."""
    data = request.json
    post_url = data.get('url')

    if not post_url:
        return jsonify({'error': 'No URL provided'}), 400

    # Call the scrape function from Service 1
    comments = scrape_facebook_comments(post_url)  # Ensure this function is correctly imported
    if not comments:
        return jsonify({'error': 'Failed to retrieve comments.'}), 500

    analyzed_comments = []
    for comment in comments:
        sentence = Sentence(comment)
        classifier.predict(sentence)
        sentiment_label = sentence.labels[0].value
        emoji = '😊' if sentiment_label == 'POSITIVE' else '😠' if sentiment_label == 'NEGATIVE' else '😐'
        analyzed_comments.append({'text': comment, 'sentiment': sentiment_label, 'emoji': emoji})

    return jsonify({'comments': analyzed_comments})

if __name__ == '__main__':
    app.run(debug=True)
