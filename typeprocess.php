<?php
// Capture the comment from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
} else {
    echo 'No comment provided.';
    exit();
}

// Use cURL to send the comment to the Flask API and get the result
$api_url = 'https://nlp-sentiment-analysis-f4u4.onrender.com/analyze'; // Make sure to include the correct endpoint

$ch = curl_init($api_url);
$data = json_encode(array('comment' => $comment));

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    curl_close($ch);
    exit();
}

curl_close($ch);

// Decode the API response
$sentiment_data = json_decode($result, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'JSON Decode Error: ' . json_last_error_msg();
    echo '<br>Response from API: ' . htmlspecialchars($result); // Display raw response for debugging
    exit();
}

// Extract sentiment and cleaned comment from the API response
$sentiment = isset($sentiment_data['sentiment']) ? $sentiment_data['sentiment'] : 'unknown';
$cleaned_comment = isset($sentiment_data['comment']) ? $sentiment_data['comment'] : 'No comment';

// Redirect to result.php with sentiment data
header('Location: Result.php?sentiment=' . urlencode($sentiment) . '&comment=' . urlencode($cleaned_comment));
exit();
?>
