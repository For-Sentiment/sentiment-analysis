<?php
function fetchCommentsFromFacebookPost($fb_post_url) {
    $comments = [];
    $access_token = 'EAAGZCpxXYvfcBO5HqcSWZAzaOJjzEmGYSWCmUgAjpzIXD37SvjZCXZAqXRZCNAIdeS454bP0sDVr53qMJkZB0s7IhCaN8RbmaRzkdPIu26JmOnTHaSSqfIgtWqlZAO94ZBBE0ZBN0rBWmz1TLov6UWPLmeJBlgJeIHZBanvGUtiMsjLAfGnZBZAftZCp65TOTxGOneTQsnS1uvuTyZAOuCDtCw5QZDZD'; // Ensure this is valid

    preg_match('/\/posts\/(pfbid[^\?]+)/', $fb_post_url, $matches);
    if (empty($matches)) {
        preg_match('/\/(\d{15,})/', $fb_post_url, $matches);
    }
    
    $post_id = isset($matches[1]) ? $matches[1] : ''; 

    if ($post_id) {
        // Make the API request
        $api_url = "https://graph.facebook.com/v20.0/$post_id?fields=comments&access_token=$access_token";
        error_log("API URL: $api_url"); // Log the API URL
        $response = @file_get_contents($api_url); // Use @ to suppress warnings

        if ($response === FALSE) {
            return ['error' => 'Failed to fetch data from Facebook API.'];
        }

        $data = json_decode($response, true);
        
        if (isset($data['comments']['data'])) {
            foreach ($data['comments']['data'] as $comment) {
                $comments[] = $comment['message'];
            }
        }
    } else {
        return ['error' => 'Invalid post ID or URL.'];
    }
    
    return $comments;
}


function analyzeSentiment($comment) {
    // Your existing sentiment analysis logic
    $ch = curl_init('https://nlp-sentiment-analysis-f4u4.onrender.com/analyze');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['comment' => $comment]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fb_post_url = $_POST['fb_url'];

    // Step 1: Fetch comments from the specific Facebook post
    $comments = fetchCommentsFromFacebookPost($fb_post_url);

    // Step 2: Analyze each comment's sentiment
    $results = [];
    foreach ($comments as $comment) {
        $sentiment_data = analyzeSentiment($comment); // Call your defined sentiment analysis function
        $results[] = [
            'comment' => $comment,
            'sentiment' => $sentiment_data['sentiment'],
            'emoji' => $sentiment_data['emoji'], // Ensure this matches your API response
        ];
    }
}


    // Handle CSV download after processing the comments
    if (isset($_GET['download_csv'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Comments.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Comment', 'Sentiment', 'Emoji']); // CSV header

        foreach ($results as $result) {
            fputcsv($output, [$result['comment'], $result['sentiment'], $result['emoji']]);
        }

        fclose($output);
        exit();
    }

    // If you want to display results on the page, you can add that logic here.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'>
    <title>Sentiment Results</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #456ab6;
            overflow: hidden;
        }

        .sidebar {
            width: 19.5%;
            height: 99.3%;
            background-color: #2e4c8d;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            position: fixed;
            transition: filter 0.3s ease;
        }

        .blurred {
        filter: blur(5px);
        pointer-events: none;
    }

    .notification, .logout-confirmation {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px 50px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        text-align: center;
        display: none;
    }

    .notification h2, .logout-confirmation h2 {
        margin: 0 0 20px 0;
    }

    .notification button, .logout-confirmation button {
        padding: 10px 50px;
        background-color: white;
        color: black;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: large;
        font-weight: bolder;
    }

    .notification button:hover, .logout-confirmation button:hover {
        background-color: #3b5998;
    }

    .logout-confirmation button {
        margin: 0 10px;
    }

    .dashboard {
        text-align: left;
        color: white;
        position: absolute;
        top: 10%;
        left: 1%;
        padding: 50px;
        margin: 0;
        font-size: 19px;
        font-family: 'Open Sans';
        display: flex;
        flex-direction: column;
        height: 80%;
    }

    .dashboard p {
        position: absolute;
        line-height: 1.1;
        margin-top: -100px;
        right: 40px;
        margin-bottom: 0;
    }

    .dashboard ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Spread items evenly */
    height: 100%;
}


    .dashboard .top-items {
        position: absolute;
        display: flex;
        flex-direction: column;
    }

    

    .dashboard .bottom-items {
        margin-top: auto;
        display: flex;
        flex-direction: column;
    }

    .dashboard li {
    margin-bottom: 20px; /* Ensure space between each item */
}

.dashboard a {
    color: white;
    text-decoration: none;
    padding: 10px;
    display: block;
    font-size: 18px;
}

.dashboard a:hover {
    font-weight: 600; 
}

.facebook-logo {
        position: absolute;
        right: 290px; 
        top: 0;
        background-color: #2e4c8d;
        color: white;
        padding: 10px;
        border-radius: 50%;
        display: inline-block;
        text-align: center;
        width: 40px;
        height: 95%;
        line-height: 20px;
        font-size: 90px;
    }


    .dashboard-icon {
        position: relative;
        padding-left: 35px;
    }

    .dashboard-icon::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/dashb.png');
        background-size: cover;
    }

    .realtime-icon {
        position: relative;
        padding-left: 24px;
    }

    .realtime-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/realt.png');
        background-size: cover;
    }

    .sentiment-icon {
        position: relative;
        padding-left: 24px;
    }

    .sentiment-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/sentit.png');
        background-size: cover;
    }

    .acc-icon {
        position: relative;
        padding-left: 24px;
    }

    .acc-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/user.png');
        background-size: cover;
    }

    .logout-icon {
        position: relative;
        padding-left: 24px;
    }

    .logout-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/logout.png');
        background-size: cover;
    }

    .dashboard-icon::before, .realtime-icon::before, .sentiment-icon::before, 
.acc-icon::before, .logout-icon::before {
    left: -20px; /* Adjust this for better alignment */
}

    .facebooklogo {
    position: absolute;
    right: 260px; 
    top: 20%;
    background-color: #456ab6;
    color: rgba(255, 255, 255, 0.5);
    padding: 20px; /* Reduce padding for smaller size */
    border-radius: 50%;
    display: inline-block;
    text-align: center;
    width: 80px; /* Adjust width */
    height: 80px; /* Adjust height */
    line-height: 90px; /* Match height for centering the logo vertically */
    font-size: 580px; /* Adjust font size */
    z-index: -99;
}

    .highlight {
            transform: scale(1.3); /* Slightly increase size of highlighted emoji */
        }
    .main-content {
    margin-top: -20px;
    margin-left: 400px; 
    display: flex;
    flex-direction: column; 
    justify-content: flex-start;
    height: 100%;
    width: 70%;
}

.main-content h2 {
    color: white;
    font-size: 25px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: -10px;
    text-align: left; /* Aligns the text to the right */
}

.main-content p {
    color: white;
    font-size: 15px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: 40px;
    text-align: left; /* Aligns the text to the right */
}


.results-container {
    max-height: 80vh;
    overflow-y: auto;
    padding: 20px;
    background-color: #456ab6;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    border: 2px solid white; /* Add white border */
}
/* Styling the Table */
table {
    width: 102%;
    border-collapse: collapse;
    table-layout: fixed; /* Ensures columns are equally spaced */
    margin-top: 30px;
    border: 1px solid #ddd; /* Adds border to the table */
    text-align: center;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd; /* Line between rows */
    overflow: hidden; /* Prevents text overflow */


}

th {
    background-color: #8b9dc3;
    color: white;
    position: -webkit-sticky; /* For Safari */
    position: sticky;
    top: 0; /* Stick to the top */
    z-index: 10; /* Ensure the header stays above other content */
    border-bottom: 2px solid white; /* Border under header */
    text-align: center;
}
td {
    border-right: 1px solid #ddd; /* Line between columns */
    color: white;
}

td:last-child, th:last-child {
    border-right: none; /* Remove right border for last column */
}

/* Center align Sentiments and Emojis */
td:nth-child(2), /* Sentiment column */
td:nth-child(3) { /* Emoji column */
    text-align: center;
}

/* Optional: Set fixed width for each column to ensure equal spacing */
th:first-child, td:first-child {
    width: 40%; /* Adjust width as needed */
}

th:nth-child(2), td:nth-child(2) {
    width: 30%; /* Adjust width as needed */
}

th:nth-child(3), td:nth-child(3) {
    width: 30%; /* Adjust width as needed */
}

.emoji {
            text-align: center;
        }

        /* Match background color of .fb-link to the body */
.fb-link {
    position: absolute;
    top: 79px;
    left: 400px;
    background-color: #456ab6; /* Same as body background */
    padding: 5px;
    font-size: 11px;
    font-family: 'Open Sans', sans-serif; /* Change the font */
    word-wrap: break-word; /* Wrap long URLs */
    word-break: break-all; /* Break long words/URLs if necessary */
    max-width: 60%; /* Ensure it doesn't overflow */
    white-space: normal; /* Allow text to wrap to the next line */
}

/* Style the "Facebook Post URL:" text */
.fb-link strong {
    font-size: 15px;
    color: white; /* Change text color */
    font-family: 'Arial Black', sans-serif; /* Change font to Arial Black */
}

/* Style the actual link */
.fb-link a {
    color: white; /* Change link color */
    text-decoration: none; /* Remove underline from the link */
    font-family: 'Open Sans', sans-serif; /* Change the font */
    white-space: normal; /* Ensure link text wraps */
    word-break: break-all; /* Force breaking for long URLs */
}

/* Hover effect for the link */
.fb-link a:hover {
    color: red; /* Change the hover color */
    text-decoration: underline; /* Add underline on hover */
}

        .emoji img {
            width: 60px; /* Adjust size as needed */
            height: auto;
        }

.button-csv{
    background-color: #8b9dc3;
    position: absolute;
    font-weight: 300;
    color: white;
    width: 10%;
    height: 35px;
    top: 80.5%;
    left: 24.5%;
    border-radius: 5px;
    font-size: 14px;
    font-family: Arial Black, sans-serif;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    border: 1px solid #424e66;

}


.button-csv:hover{
    background-color: #3b5998;
}

.dashboard-button{
    background-color: #8b9dc3;
    position: absolute;
    font-weight: 300;
    color: white;
    width: 10%;
    height: 35px;
    top: 10.5%;
    left: 87%;
    border-radius: 5px;
    font-size: 14px;
    font-family: Arial Black, sans-serif;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    border: 1px solid #424e66;
}


.dashboard-button:hover{
    background-color: #3b5998;
}


    </style>
</head>
<body>

<div id="content-wrapper">

<div class="sidebar">
    <div class="facebook-logo">
        <i class="fab fa-facebook-f"></i>
    </div>
    <div class="dashboard">
    <p>Exploring Voices: <br> Pre and Post-Pandemic <br>Facebook Insights at BulSU</p>

<ul>
    <li class="top-items">
        <li><a href="home.php" class="dashboard-icon"> &nbsp; Dashboard</a></li>
        <li><a href="RealTAnalysis.php" class="realtime-icon"> &nbsp; Realtime Sentiment Analysis</a></li>
        <li><a href="SentAnalysis.php" class="sentiment-icon"> &nbsp; Sentiment Analysis</a></li>
    </li>
    <li class="bottom-items">
        <li><a href="Account.php" class="acc-icon"> &nbsp; Account</a></li>
        <li><a href="#" onclick="showLogoutConfirmation()" class="logout-icon"> &nbsp; Logout</a></li>
    </li>
</ul>
</div>
</div>



<div class="main-content">
        <h2>REAL-TIME SENTIMENT ANALYSIS</h2>
        <p>Updated Facebook Page.</p>
    <a href="home.php"> <button class="dashboard-button">See Dashboard</button></a>

    <div class="fb-link">
    <strong>Facebook Post URL:</strong><br>
    <a href="<?php echo htmlspecialchars($fb_post_url); ?>" target="_blank"><?php echo htmlspecialchars($fb_post_url); ?></a>
</div>


<div class="container">
    <table>
        <thead>
            <tr>
                <th>Comment</th>
                <th>Sentiment</th>
                <th>Emoji</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($comments as $comment) { 
    $sentiment_data = analyzeSentiment($comment); // Use analyzeSentiment instead
    ?>
    <tr>
        <td><?php echo htmlspecialchars($comment); ?></td>
        <td><?php echo $sentiment_data['sentiment']; ?></td>
        <td class="emoji"><?php echo $sentiment_data['emoji']; ?></td>
    </tr>
<?php } ?>


        </tbody>
    </table>
</div>

</div>

    <!-- Download CSV Button -->
<a href="?download_csv=true"><button class="button-csv">Download As .csv</button></a>



<!-- Notification, logout confirmation, and scripts (unchanged)... -->

<div class="facebooklogo">
        <i class="fab fa-facebook-f"></i>
    </div>

    </div>

<div id="formOverlay" class="form-overlay"></div>


<div id="notification" class="notification">
    <p>Your notification message here</p>
    <button onclick="closeNotification()">Close</button>
</div>

<div id="logout-confirmation" class="logout-confirmation">
    <h2>Logging Out?</h2>
    <button onclick="logout()">Yes</button>
    <button onclick="closeLogoutConfirmation()">No</button>
</div>


<script>
function showLogoutConfirmation() {
    document.getElementById('logout-confirmation').style.display = 'block';
    document.getElementById('content-wrapper').classList.add('blurred');
}

function closeLogoutConfirmation() {
    document.getElementById('logout-confirmation').style.display = 'none';
    document.getElementById('content-wrapper').classList.remove('blurred');
}

function showNotification() {
    document.getElementById('notification').style.display = 'block';
    document.getElementById('content-wrapper').classList.add('blurred');
}

function closeNotification() {
    document.getElementById('notification').style.display = 'none';
    document.getElementById('content-wrapper').classList.remove('blurred');
}

</script>

</body>
</html>
