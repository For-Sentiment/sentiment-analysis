<?php
// Start session
session_start();

// Retrieve the sentiment and comment from the session
$sentiment = isset($_SESSION['sentiment']) ? $_SESSION['sentiment'] : '';
$comment = isset($_SESSION['comment']) ? htmlspecialchars($_SESSION['comment']) : '';

// Optional: Clear the session data after displaying (so it doesn't persist)
unset($_SESSION['sentiment']);
unset($_SESSION['comment']);

// Convert the comment to lowercase
$comment = strtolower($comment);

// Remove special characters from the comment (leaving only alphanumeric characters and spaces)
$comment = preg_replace("/[^a-zA-Z0-9\s]/", "", $comment);

// Determine which emoji to highlight
$positiveClass = $sentiment == 'positive' ? 'highlight' : '';
$neutralClass = $sentiment == 'neutral' ? 'highlight' : '';
$negativeClass = $sentiment == 'negative' ? 'highlight' : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="shortcut icon" type="x-icon" href="images/logoo1.png">
<title>NLP Sentiment Analysis</title>
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
            width: 29.5%;
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
        left: 6%;
        padding: 0;
        margin: 0;
        font-size: 23px;
        font-family: 'Open Sans';
        display: flex;
        flex-direction: column;
        height: 80%;
        }

        .dashboard p {
        line-height: 1.1;   
        margin-bottom: 20px;  
        }

        .dashboard ul {
        padding: 0;
        margin: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        }

        .dashboard .top-items {
        display: flex;
        flex-direction: column;
    }

    .dashboard .bottom-items {
        margin-top: auto;
        display: flex;
        flex-direction: column;
    }


        .dashboard ul li {
        padding-left: 10px;
        margin-top: 20px;
        list-style-type: none;
        }

        .dashboard li a {
        color: white;
        line-height: 1.5;
        margin-bottom: 5px;
        text-decoration: none;
        font-weight: normal;
        transition: font-weight 0.1s ease-in-out;

        }

        .dashboard a:hover {
            font-weight: 600; 
        }

        .facebook-logo {
        position: absolute;
        right: 30px; 
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
        padding-left: 24px;
    }

    .dashboard-icon::before {
        content: '';
        position: absolute;
        left: 0;
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
            margin-left: 540px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%;
            width: 70%;
        }

        .main-content h1 {
            color: white;
            font-size: 50px;
            font-family: Arial Black, sans-serif;
            margin-bottom: 10px;
            margin-right: 30%;
        }

        .main-content h2 {
            color: white;
            font-size: 40px;
            font-family: Arial Black, sans-serif;
            margin-bottom: 10px;
            text-align: center;
        }

        .emoji-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 20px; /* Adjust margin to provide space below emojis */
        }

        .emoji-container img {
            width: 110px; /* Default size for emojis */
            height: auto;
            margin: 0 10px;
            transition: transform 0.3s; /* Smooth transition for highlighting */
        }

        #result {
            color: white;
            font-size: 60px; /* Adjust size as needed */
            font-family: Arial Black, sans-serif;
            text-align: center; /* Center the text */
            margin-top: 80px; /* Space above the sentiment text */
            z-index: 1000;
        }

        #comment {
            color: white;
            font-size: 40px; /* Adjust size as needed */
            font-family: Arial Black, sans-serif;
            text-align: center; /* Center the comment text */
            margin-top: 20px; /* Space above the comment text */
            z-index: 1000;
        }

        .quoted {
            font-style: italic;
            color: lightgray; /* Optional: lighter color for the quotes */
        }

</style>
</head>
<body>

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
        <h1>SENTIMENT ANALYSIS</h1>
        <h2>Facebook Comments:</h2>
        <p id="comment" class="quoted">"<?php echo $comment; ?>"</p> <!-- Display the user's comment with quotes -->
        <div class="emoji-container">
            <div class="Positive <?php echo $positiveClass; ?>">
                <img src="images/happy.png" alt="Positive">
            </div>
            <div class="Neutral <?php echo $neutralClass; ?>">
                <img src="images/neutral.png" alt="Neutral">
            </div>
            <div class="Negative <?php echo $negativeClass; ?>">
                <img src="images/aangryy.png" alt="Negative">
            </div>
        </div>
        <p id="result">Sentiment: <?php echo ucfirst($sentiment); ?></p> <!-- Sentiment text below emojis -->
    </div>

    <div class="facebooklogo">
        <i class="fab fa-facebook-f"></i>
    </div>

<!-- Overlay -->
<div id="formOverlay" class="form-overlay"></div>


<!-- Notification -->
<div id="notification" class="notification">
    <p>Your notification message here</p>
    <button onclick="closeNotification()">Close</button>
</div>

<!-- Logout Confirmation Dialog -->
<div id="logout-confirmation" class="logout-confirmation">
    <h2>Logging Out?</h2>
    <button onclick="logout()">Yes</button>
    <button onclick="closeLogoutConfirmation()">No</button>
</div>

s
<!-- Your other scripts for notifications, logout, etc. -->
<script>
    function showLogoutConfirmation() {
        document.getElementById('logout-confirmation').style.display = 'block';
        document.querySelector('.sidebar').classList.add('blurred');
        document.querySelector('.main-content').classList.add('blurred');
    }

    function closeLogoutConfirmation() {
        document.getElementById('logout-confirmation').style.display = 'none';
        document.querySelector('.sidebar').classList.remove('blurred');
        document.querySelector('.main-content').classList.remove('blurred');
    }

    function logout() {
        window.location.href = 'Account.php?logout=true';
    }

    function showNotification() {
        document.getElementById('notification').style.display = 'block';
        document.querySelector('.sidebar').classList.add('blurred');
        document.querySelector('.main-content').classList.add('blurred');
    }

    function closeNotification() {
        document.getElementById('notification').style.display = 'none';
        document.querySelector('.sidebar').classList.remove('blurred');
        document.querySelector('.main-content').classList.remove('blurred');
    }
</script>

</body>
</html>
