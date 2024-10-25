import re
from flask import Flask, request, jsonify
from flask_cors import CORS
from transformers import pipeline

# Initialize Flask app and CORS
app = Flask(__name__)
CORS(app)  # This will allow all domains. You can restrict it to specific domains if needed.

# Initialize sentiment analysis model using transformers
sentiment_pipeline = pipeline("sentiment-analysis", model="nlptown/bert-base-multilingual-uncased-sentiment")

# Route to analyze a single comment
@app.route('/analyze_comment', methods=['POST'])
def analyze_comment():
    """Analyzes a single comment and returns its sentiment."""
    try:
        comment = request.json.get('comment', '')
        comment = comment.lower()
        comment = re.sub(r'[^\w\s]', '', comment)  # Remove punctuation

        # Perform sentiment analysis using transformers
        result = sentiment_pipeline(comment)[0]
        label = result['label']
        sentiment = "positive" if label == '5 stars' else "neutral" if label == '3 stars' else "negative"

        return jsonify({"sentiment": sentiment, "comment": comment})
    except Exception as e:
        return jsonify({'error': str(e)})

# Route to analyze comments in bulk from a CSV file
@app.route('/upload_csv', methods=['POST'])
def upload_csv():
    """Uploads a CSV file and performs sentiment analysis on its comments."""
    try:
        if 'file' not in request.files:
            return jsonify({'error': 'No file part'}), 400

        file = request.files['file']
        if file.filename == '':
            return jsonify({'error': 'No selected file'}), 400

        # Read and analyze comments from the CSV
        df = pd.read_csv(file)
        if 'Comment' not in df.columns:
            return jsonify({'error': 'No "Comment" column found in the file'}), 400

        results = []
        for comment in df['Comment'].dropna():
            cleaned_comment = re.sub(r'[^\w\s]', '', comment.lower())  # Clean comment

            # Perform sentiment analysis using transformers
            result = sentiment_pipeline(cleaned_comment)[0]
            label = result['label']
            sentiment = 'positive' if label == '5 stars' else 'negative' if label == '1 star' else 'neutral'

            results.append({
                'comment': cleaned_comment,
                'sentiment': sentiment,
                'score': result['score']
            })

        return jsonify({'results': results})

    except Exception as e:
        return jsonify({'error': str(e)})

if __name__ == '__main__':
    app.run(debug=True)
