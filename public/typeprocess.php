<?php
// Capture the comment from the form submission
$comment = $_POST['comment'];

// Use cURL to send the comment to the Flask API and get the result
$api_url = 'http://127.0.0.1:5000/analyze';

$ch = curl_init($api_url);
$data = json_encode(array('comment' => $comment));

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

// Decode the API response
$sentiment_data = json_decode($result, true);
$sentiment = $sentiment_data['sentiment'];
$cleaned_comment = $sentiment_data['comment'];

// Redirect to result.php with sentiment data
header('Location: result.php?sentiment=' . urlencode($sentiment) . '&comment=' . urlencode($cleaned_comment));
exit();
?>
