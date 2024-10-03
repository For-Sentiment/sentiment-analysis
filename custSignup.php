<?php
session_start(); // Start the session to use session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Signup - Sentiment Analysis</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* General Styles */
        html {
            scroll-behavior: smooth;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Background Image with Blur Effect */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/loginimg.jpg') no-repeat center center/cover;
            filter: blur(8px);
            z-index: -2;
        }

        /* Overlay Effect */
        body::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: -1;
        }

        /* Body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            position: relative;
            z-index: 0;
            line-height: 1.6;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(to right, rgba(64, 64, 64, 0.7), rgba(0, 0, 0, 0.8));
            background-blend-mode: multiply;
        }

        /* Navigation Bar Styling */
        nav {
            background-color: #003366;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1;
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
            transition: color 0.3s;
        }

        nav .nav-links a:hover {
            color: #ffcc00;
        }

        nav .nav-links .active {
            color: #ffcc00;
            border-bottom: 2px solid #ffcc00;
        }

        /* Main Container */
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 30px;
            height: 100%; 
            width: 100%;
        }

        /* Quote Section */
        .quote {
            width: 50%; /* Align with signup form */
            padding: 30px;
            margin: auto; /* Center the quote */
            border-radius: 8px;
            color: #fff; /* Keep text color white for better visibility */
            font-style: italic; /* Italic style for emphasis */
            text-align: left; /* Align text to the left */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif; /* Change font to Arial or a similar sans-serif font */
            font-size: 1.2em; /* Adjust font size for better readability */
        }

        /* Signup Form */
        .signup-form {
            width: 400px; /* Set a specific width for the form */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: auto; /* Center the form */
            background: rgba(255, 255, 255, 0.1); /* Transparent background */
            backdrop-filter: blur(10px); /* Add a blur effect to the background of the form */
            color: #fff; /* Ensure the text color is white */
        }

        .signup-form h2 {
            color: gray; /* Change the header color to make it stand out */
            margin-bottom: 20px;
            text-align: center; /* Center the header text */
        }

        .signup-form label {
            font-weight: bold;
            color: #fff; /* Change label color to white */
        }

        .signup-form input[type="text"],
        .signup-form input[type="email"],
        .signup-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .signup-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #003366;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .signup-form input[type="submit"]:hover {
            background-color: #ffcc00;
            color: #003366;
        }

        .signup-message {
            text-align: center; /* Center the message */
            margin-top: 10px; /* Add some space above the message */
        }

        .signup-link {
            color: #ffcc00; /* Change text color */
            cursor: pointer; /* Change cursor to pointer */
            text-decoration: none; /* Remove underline */
        }

        .signup-link:hover{
            color: #003366;
        }

        .error-message {
    color: #a33; /* Softer red color for error messages */
    text-align: center;
    margin-top: 10px;
    font-size: 18px; /* Increased font size */
    font-weight: bold; /* Bold text for emphasis */
    background-color: rgba(255, 200, 200, 0.5); /* Light red background */
    border: 1px solid #a33; /* Softer red border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Padding for spacing */
    width: 80%; /* Responsive width */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    margin: 20px auto; /* Centered with margin */
}

.success-message {
    color: #3a3; /* Softer green color for success messages */
    text-align: center;
    margin-top: 10px;
    font-size: 18px; /* Increased font size */
    font-weight: bold; /* Bold text for emphasis */
    background-color: rgba(200, 255, 200, 0.5); /* Light green background */
    border: 1px solid #3a3; /* Softer green border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Padding for spacing */
    width: 80%; /* Responsive width */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    margin: 20px auto; /* Centered with margin */
}


        .login-link {
            color: #ffcc00; /* Change text color */
            cursor: pointer; /* Change cursor to pointer */
            text-decoration: none; /* Remove underline */
        }

        .login-link:hover {
            color: #003366;
        }
    </style>
</head>
<body>

<nav>
    <img src="images/sentlogo.png" alt="Logo" class="logo">
    <div class="nav-links">
        <a href="Customer.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'Customer.php' ? 'active' : ''; ?>">Home</a>
        <a href="custDashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custDashboard.php' ? 'active' : ''; ?>">Dashboard</a>
        <a href="custRealtime.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custRealtime.php' ? 'active' : ''; ?>">Real-Time Sentiment Analysis</a>
        <a href="custSentiment.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'custSentiment.php' || basename($_SERVER['PHP_SELF']) == 'custtypeown.php' || basename($_SERVER['PHP_SELF']) == 'custImport.php' || basename($_SERVER['PHP_SELF']) == 'custImportresult.php' || basename($_SERVER['PHP_SELF']) == 'custtyperesult.php') ? 'active' : ''; ?>">Sentiment Analysis</a>
        <a href="custAbout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custAbout.php' ? 'active' : ''; ?>">About Us</a>
        <a href="custLogin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custLogin.php' ? 'active' : ''; ?>">Login</a>
        <a href="custSignup.php" class="login-link <?php echo basename($_SERVER['PHP_SELF']) == 'custSignup.php' ? 'active' : ''; ?>">Sign Up</a>
    </div>
</nav>

<!-- Main Container -->
<div class="container">
    <!-- Quote Section -->
    <div class="quote">
        <p>
            In the dynamic landscape of social media, language serves as a powerful lens to observe the shifting sentiments of communities. This study explores the evolution of Facebook comments from Bulacan State University, both before and after the pandemic, illuminating how collective experiences shape individual expressions and the profound impact of societal challenges on communication.
        </p>
    </div>

    <!-- Signup Form Section -->
    <div class="signup-form">
        <h2>NLP Sentiment Signup</h2>

        <!-- Display messages based on URL parameters -->
        <?php if (isset($_GET['error'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <p class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>

        <form action="custProcsignup.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <input type="submit" value="Sign Up">
        </form>

        <div class="signup-message">
            <p>Already have an account? <a href="custLogin.php" class="signup-link">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>