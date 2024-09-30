<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View - Sentiment Analysis</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>

                /* General Styles */
                * {
            box-sizing: border-box; /* Include padding and borders in width calculations */
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar styling for webkit-based browsers (Chrome, Safari) */   
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #2e4c8d;
        }

        ::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #888;
        }

        /* Firefox scrollbar styling */
        * {
            scrollbar-width: thin;
            scrollbar-color: #555 #2e4c8d;
        }


        /* Background Image with Blur Effect */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            height: 100%;
            background-color: rgba(69, 107, 182, 0.5); /* Semi-transparent overlay */
            z-index: -1; /* Behind content but over the background image */
        }

        /* Body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
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

        .main-content {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;

        }

        .main-content h1 {
            color: #243a69;
            font-size: 50px;
            font-family: Arial Black, sans-serif;
            text-align: center;
        }

        .main-content p {
            color: #243a69;
            font-size: 20px;
            font-family: Arial Black, sans-serif;
            text-align: center;
        }

        /* Emoji container */
        .emoji-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .Positive img, .Neutral img, .Negative img {
            width: 130px;
            margin: 0 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .Positive img:hover, .Neutral img:hover, .Negative img:hover {
            transform: scale(1.1);
        }

        /* Search container */
        .search-container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            flex-direction: column;
            align-items: center;
        }

        .search-box {
            position: relative;
            background-color: white;
            border-radius: 50px;
            display: flex;
            align-items: center;
            padding: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 500px;
        }

        .search-box input[type="text"] {
            border: none;
            outline: none;
            font-size: 16px;
            padding: 10px;
            border-radius: 50px;
            width: 100%;
            margin-right: 10px;
        }

        .search-box input[type="text"]::placeholder {
            color: #aaa;
            font-size: 14px;
        }

        .search-btn {
            background-color: #3b5998;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search-icon {
            color: white;
            font-size: 20px;
        }

        /* Loader */
        #loader {
            display: none;
            margin-top: 20px;
            border: 8px solid #f3f3f3; /* Light grey */
            border-top: 8px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Footer Styles */
        footer {
            margin-top: 245px; /* Push footer to the bottom */
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

    <!-- Main Content -->
    <div class="main-content">
        <h1>SENTIMENT ANALYSIS</h1>
        <p>Categorizes Facebook Comments Under Positive, Neutral, or Negative.</p>

        <!-- Emoji container -->
        <div class="emoji-container">
            <div class="Positive" title="Positive sentiment">
                <img src="images/happy.png" alt="Positive">
            </div>
            <div class="Neutral" title="Neutral sentiment">
                <img src="images/neutral.png" alt="Neutral">
            </div>
            <div class="Negative" title="Negative sentiment">
                <img src="images/aangryy.png" alt="Negative">
            </div>
        </div>

        <!-- Search container -->
        <div class="search-container">
            <form id="commentForm" action="custtypeprocess.php" method="POST">
                <div class="search-box">
                    <input type="text" id="comment" name="comment" placeholder="Enter comment to be analyzed (max 250 chars)" maxlength="250" required>
                    <button type="submit" class="search-btn">
                        <i class="search-icon">&#128269;</i>
                    </button>
                </div>
            </form>
            <div id="loader"></div>
        </div>
    </div>

   <!-- Footer -->
   <footer>
        &copy; 2024 Sentiment Analysis Project. All rights reserved.
    </footer>

    <script>
    // Show Loader on Form Submission
    const form = document.getElementById('commentForm');
    const loader = document.getElementById('loader');

    form.addEventListener('submit', () => {
        loader.style.display = 'block';
    });

    // Hide Loader on Page Load
    window.onload = () => {
        loader.style.display = 'none';
    };
</script>

</body>
</html>
