<?php
// Start session
session_start();

// Capture the comment from the form submission
$comment = $_POST['comment'];

// Use cURL to send the comment to the Flask API and get the result
$api_url = 'https://nlp-sentiment-analysis-f4u4.onrender.com/analyze';

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
    exit();
}

$sentiment = isset($sentiment_data['sentiment']) ? $sentiment_data['sentiment'] : 'unknown';
$cleaned_comment = isset($sentiment_data['comment']) ? $sentiment_data['comment'] : 'No comment';

// Store sentiment and comment in session
$_SESSION['sentiment'] = $sentiment;
$_SESSION['comment'] = $cleaned_comment;

// Redirect to result.php without exposing data in the URL
header('Location: custtyperesult.php');
exit();
?>