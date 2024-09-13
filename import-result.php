<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .results-container {
            max-height: 80vh;
            overflow-y: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .emoji {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h1>Analysis Results</h1>
    <div class="results-container" id="results-container">
        <!-- Results will be populated here -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the results from local storage
            const results = JSON.parse(localStorage.getItem('analysisResults'));
            const container = document.getElementById('results-container');

            if (results) {
                let html = '<table><thead><tr><th>Comment</th><th>Sentiment</th><th>Emoji</th></tr></thead><tbody>';
                results.forEach(result => {
                    html += `<tr><td>${result.comment}</td><td>${result.sentiment}</td><td class="emoji">${result.emoji}</td></tr>`;
                });
                html += '</tbody></table>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p>No results found.</p>';
            }
        });
    </script>
</body>
</html>
