<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View - Sentiment Analysis</title>
    <link rel="stylesheet" href="customer.css">

    <style>
        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* General Styles */
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
            background: url('images/bg.jpg') no-repeat center center/cover;
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
            background-color: rgba(255, 255, 255, 0.6); /* Light overlay */
            z-index: -1;
        }

        /* Body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            position: relative;
            z-index: 0;
            line-height: 1.6; /* Improved line height for readability */
            height: 100vh; /* Ensure full height */
            overflow: hidden; /* Prevent scroll */
        }

        /* Navigation Bar Styling */
        nav {
            background-color: #003366;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed; /* Fix navigation at the top */
            top: 0; /* Align to the top */
            width: 100%; /* Full width */
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

        /* Main Content Styling */
        .content {
            margin: 70px auto; /* Adjusted margin for fixed nav */
            text-align: left;
            width: 90%; /* Slightly reduced width for better spacing */
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.9); /* Solid white background for better contrast */
            padding: 100px; /* Increased padding */
            border-radius: 10px; /* Rounded corners */
            display: flex; /* Use flexbox for layout */
            align-items: center; /* Center items vertically */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Soft shadow for depth */
        }

        .main-content {
            flex: 1; /* Allow the text to take remaining space */
            margin-right: 20px; /* Space between text and image */
        }

        .main-content h1 {
            color: #003366; /* Dark blue for main heading */
            font-size: 40px;
            margin-bottom: 20px;
            font-weight: 700; /* Bold heading */
        }

        .main-content h2 {
            color: #003366; /* Dark blue for subheading */
            font-size: 28px; /* Slightly reduced font size */
            margin: 20px 0; /* Add margin for spacing */
        }

        .main-content p {
            color:  #0a0a0a; /* Dark gray for paragraph text */
            font-size: 18px;
            margin-bottom: 30px;
        }

        .main-content a {
            color: #ffcc00; /* Link color */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Bold text */
            font-size: 18px; /* Increase font size for links */
            border: 2px solid #ffcc00; /* Add border */
            padding: 10px 15px; /* Add padding */
            border-radius: 5px; /* Rounded corners for buttons */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
        }

        .main-content a:hover {
            background-color: #ffcc00; /* Change background on hover */
            color: #003366; /* Change text color on hover */
        }

        .content img {
            width: 550px; /* Adjusted width for better layout */
            height: auto; /* Maintain aspect ratio */
        }

        /* Footer Styling */
        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
            font-size: 14px;
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
            <a href="custSentiment.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'custSentiment.php' || 
                basename($_SERVER['PHP_SELF']) == 'custtypeown.php' || 
                basename($_SERVER['PHP_SELF']) == 'custImport.php' || 
                basename($_SERVER['PHP_SELF']) == 'custImportresult.php' || 
                basename($_SERVER['PHP_SELF']) == 'custtyperesult.php') ? 'active' : ''; ?>">Sentiment Analysis</a>
            <a href="custAbout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custAbout.php' ? 'active' : ''; ?>">About Us</a>
        </div>
    </nav>

    <div class="content">
        <div class="main-content">
            <h1>Welcome to <span style="color: #003366;">NLP Sentiment!</span></h1>
            <p>Discover the mood behind your words with our simple to use and reliable sentiment analysis tool. Analyze your social media comments here! Our platform helps you understand the sentiment—whether it’s positive, negative, or neutral.</p>
            <h2><a href="custSentiment.php">Try it now</a> and see the emotions behind the words!</h2>
        </div>
        <img src="images/homeimg.png" alt="Sentiment Analysis Image"> <!-- Replace with your image source -->
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Sentiment Analysis. All rights reserved.</p>
    </footer>

</body>
</html>
