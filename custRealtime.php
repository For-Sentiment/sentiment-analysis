<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Sentiment Analysis</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="customer.css">

    <style>

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* General Styles */
* {
    box-sizing: border-box; /* Include padding and borders in width calculations */
}

        /* Background Image with Blur Effect */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 110%;
            background: url('images/bg.jpg') no-repeat center center/cover; /* Replace with your image */
            filter: blur(8px); /* Blur effect */
            z-index: -2; /* Behind everything */
        }

        /* Overlay Effect */
        body::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 110%;
            background-color: #456bb656; /* Semi-transparent overlay */
            z-index: -1; /* Behind content but over the background image */
        }

        /* Body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            position: relative;
            z-index: 0; /* Make sure content is above the background */
        }

        /* Navigation Bar Styling */
        nav {
            background-color: #003366;
            padding: 15px 40px; /* Adjusted padding for better spacing */
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
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
        }

        nav .nav-links a:hover {
            color: #ffcc00;
        }

        nav .nav-links .active {
            color: #ffcc00;
            border-bottom: 2px solid #ffcc00;
        }

        /* Main Content Styling */
        .main-content {
            margin: 50px auto;
            text-align: center;
            width: 80%;
            position: relative;
            z-index: 1; /* Make sure content is on top */
        }

        .main-content h1 {
            color: #003366;
            font-size: 40px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .main-content p {
            color: black;
            font-size: 18px;
            margin-bottom: 30px;
        }

        /* Search Box Styling */
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
        }

        .search-box {
            background-color: #fff;
            border-radius: 30px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            width: 600px;
        }

        .search-box input[type="text"] {
            border: none;
            outline: none;
            font-size: 16px;
            padding: 10px;
            width: 100%;
        }

        .search-btn {
            background-color: #003366;
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background-color: #002244;
        }

        /* Emoji Section */
        .emoji-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
        }

        .emoji-container div {
            text-align: center;
        }

        .emoji-container img {
            width: 80px;
            height: auto;
        }

        /* Footer Styles */
        footer {
            margin-top: 240px; /* Push footer to the bottom */
            text-align: center;
            font-size: 14px;
            padding: 20px 0; /* Space inside footer */
            background-color: #003366;
            color: white;
            font-weight: bolder;
        }

    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <!-- Logo -->
        <img src="images/sentlogo.png" alt="Logo" class="logo">
        
        <!-- Navigation Links -->
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

    <!-- Main Content -->
    <div class="main-content">
        <h1>Real-Time Sentiment Analysis</h1>
        <p>Paste a Facebook post link to analyze customer feedback weekly.</p>

        <div class="search-container">  
            <form action="RealTresult.php" method="POST">
                <div class="search-box">    
                    <input type="text" id="comment" name="fb_url" placeholder="Link to Facebook Post" required>
                    <button type="submit" class="search-btn">Analyze</button>
                </div>
            </form>
        </div>

        <!-- Emoji section -->
        <div class="emoji-container">
            <div class="Positive">
                <img src="images/happy.png" alt="Positive">
                <p>Positive</p>
            </div>
            <div class="Neutral">
                <img src="images/neutral.png" alt="Neutral">
                <p>Neutral</p>
            </div>
            <div class="Negative">
                <img src="images/aangryy.png" alt="Negative">
                <p>Negative</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 Sentiment Analysis Project. All rights reserved.
    </footer>


</body>
</html>
