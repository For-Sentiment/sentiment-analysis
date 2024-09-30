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

// Set the emoji path based on sentiment
$emojiPath = '';
switch ($sentiment) {
    case 'positive':
        $emojiPath = 'images/happy.png'; // Path to happy emoji
        break;
    case 'neutral':
        $emojiPath = 'images/neutral.png'; // Path to neutral emoji
        break;
    case 'negative':
        $emojiPath = 'images/aangryy.png'; // Path to angry emoji
        break;
    default:
        $emojiPath = ''; // Default to no emoji if sentiment is unknown
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View - Sentiment Analysis</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Prevent horizontal scrolling */
        html, body {
            overflow-x: hidden; /* Disable horizontal scrolling */
        }

        /* Make sure elements don’t extend beyond the viewport */
        * {
            max-width: 100vw; /* Ensure no element exceeds the viewport width */
            box-sizing: border-box; /* Keep padding and borders inside the width/height */
        }

        /* Background Image with Blur Effect */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff; /* Change text color for better contrast */
            background: url('images/bg.jpg') no-repeat center center fixed; /* Replace with your image */
            background-size: cover; /* Cover the entire viewport */
            padding-top: 70px; /* Set padding to ensure the body content doesn't overlap with the fixed nav */
        }

        /* Navigation Bar Styling */
        nav {
            background-color: #003366; /* Navigation background color */
            padding: 15px 40px; /* Adjusted padding for better spacing */
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1;
            position: fixed; /* Fix the nav at the top */
            top: 0;
            width: 100%; /* Make sure the nav spans the full width */
            height: 60px; /* Adjust nav height */
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        nav .logo {
            width: 150px;
        }

        nav .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0 15px;
            font-weight: bold;
            font-size: 16px;
        }

        nav .nav-links a:hover {
            color: #ffcc00; /* Highlight color */
        }

        nav .nav-links .active {
            color: #ffcc00;
            border-bottom: 2px solid #ffcc00;
        }

        /* Heading Styles */
        h1 {
            font-size: 2.5em;
            color: #2e4c8d;
            margin-right: -90px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Main content */
        .main-content {
            margin: 20px auto;
            padding: 20px;
            max-width: 800px; /* Limit width for better readability */
            background: rgba(0, 51, 102, 0.8); /* Match nav background for consistency */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); /* Subtle shadow for depth */
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 100px; /* Adjust margin for better spacing */
        }

        .main-content h1 {
            color: #ffcc00; /* Gold color for headings */
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .main-content h2 {
            color: #fff; /* White color for subheadings */
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        #comment {
            font-size: 20px; 
            font-style: italic; /* Italic style for comments */
            text-align: center; 
            margin-bottom: 20px; 
            padding: 10px; 
            border: 1px solid rgba(255, 255, 255, 0.5); /* Light border */
            border-radius: 5px; /* Slightly rounded edges */
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent background for the comment */
            width: 100%; /* Full width for better layout */
            transition: box-shadow 0.3s; /* Smooth transition for box shadow */
            color: #fff; /* White text color */
        }

        #comment:hover {
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.5); /* Shadow effect on hover */
        }

        /* Emoji Display */
        .emoji-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px; /* Adjust margin to provide space below emojis */
        }

        .emoji-container img {
            width: 80px; /* Adjust emoji size */
            height: auto;
            margin: 0 10px;
        }

        #result {
            color: #fff; /* Change to white for better contrast against the dark background */
            font-size: 24px; 
            font-weight: bold; /* Bold for emphasis */
            margin-top: 20px; /* Space above the sentiment text */
            z-index: 1000;
            text-align: center; /* Center the result */
        }

        /* Back button styling */
        .back-button {
            background-color: rgba(0, 51, 102, 0.8);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none; /* Remove underline */
            margin-top: 20px; /* Space above the button */
            transition: background-color 0.3s; /* Smooth background transition */
        }

        .back-button:hover {
            background-color: rgba(0, 51, 102, 1); /* Darker shade on hover */
        }

        /* Footer Styles */
        footer {
            margin-top: 220px; /* Push footer to the bottom */
            text-align: center;
            font-size: 14px;
            padding: 20px 0; /* Space inside footer */
            background-color: #003366; /* Match nav color */
            color: white;
            font-weight: bolder;
        }

    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <img src="images/sentlogo.png" alt="Logo" class="logo">
        <div class="nav-links">
            <a href="Customer.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'Customer.php' ? 'active' : ''; ?>">Home</a>
            <a href="custDashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custDashboard.php' ? 'active' : ''; ?>">Dashboard</a>
            <a href="custRealtime.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custRealtime.php' ? 'active' : ''; ?>">Real-Time Sentiment Analysis</a>
            <a href="custSentiment.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'custSentiment.php' || basename($_SERVER['PHP_SELF']) == 'custtypeown.php' || basename($_SERVER['PHP_SELF']) == 'custImport.php' || basename($_SERVER['PHP_SELF']) == 'custImportresult.php' || basename($_SERVER['PHP_SELF']) == 'custtyperesult.php') ? 'active' : ''; ?>">Sentiment Analysis</a>
            <a href="custAbout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custAbout.php' ? 'active' : ''; ?>">About Us</a>
        </div>
    </nav>


    <div class="main-content">
        <h1>Sentiment Analysis Result</h1>
        <h2>Comment:</h2>
        <div id="comment"><?php echo $comment; ?></div>

        <div class="emoji-container">
            <?php if ($emojiPath): ?>
                <img src="<?php echo $emojiPath; ?>" alt="<?php echo ucfirst($sentiment); ?>">
            <?php endif; ?>
        </div>

        <div id="result">Sentiment: <?php echo ucfirst($sentiment); ?></div>
        <a href="custSentiment.php" class="back-button">Back</a>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date("Y"); ?> Your Company Name. All Rights Reserved.
    </footer>
</body>
</html>
