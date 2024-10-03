<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View - Sentiment Analysis</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="customer.css">

    <style>

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
        /* Smooth Scroll */
html {
    scroll-behavior: smooth;
}

/* Custom Scrollbar styling for webkit-based browsers (Chrome, Safari) */   
::-webkit-scrollbar {
    width: 10px; /* Width of the scrollbar */
}

::-webkit-scrollbar-track {
    background: #2e4c8d; /* Background color of the scrollbar track */
}

::-webkit-scrollbar-thumb {
    background: #555; /* Color of the scrollbar handle */
    border-radius: 5px; /* Rounded corners of the scrollbar handle */
}

::-webkit-scrollbar-thumb:hover {
    background: #888; /* Color on hover */
}

/* Firefox scrollbar styling */
* {
    scrollbar-width: thin; /* Set scrollbar width */
    scrollbar-color: #555 #2e4c8d; /* Handle color and track color */
}


.main-content {
    margin-top: -10px;
    margin-left: 200px; 
    display: flex;
    flex-direction: column; 
    justify-content: flex-start;
    height: 100%;
    width: 70%;
}

.main-content h1 {
    color: #243a69;
    font-size:  50px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: -10px;
    text-align: left; /* Aligns the text to the right */
    margin-left: -150px; 

}

.main-content p {
    color: #243a69;
    font-size: 20px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: 40px;
    text-align: left; /* Aligns the text to the right */
    margin-left: -150px; 

}

.emoji-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    margin-right: -40px;
}

.Positive img, .Neutral img, .Negative img {
    width: 130px;
    height: auto;
    margin: 0 10px;
    cursor: pointer;
}

.comment-icon {
    position: absolute; 
    bottom: 350px;       
    left: 50%;          
    transform: translateX(-50%); 
    display: flex;        /* Flexbox to align icons horizontally */
    justify-content: center; /* Ensures the icons are centered horizontally */
    align-items: center;  /* Aligns the icons vertically */
    gap: 200px;           /* Adds space between the two icon sections */
    color: #243a69;
    font-weight: bolder;
}

.import-icon {
    display: flex;
    flex-direction: column;  /* Aligns items in a vertical column (image, h2, and p) */
    align-items: center;     /* Centers the content horizontally */
    text-align: center;
}

.import-icon img {
    width: 200px;
    height: auto;
    margin-bottom: 20px; /* Adjust the spacing below the image */
    cursor: pointer;
}

.import-icon h2 {
    font-size: 18px;
    margin-top: 10px; /* Adds space between the image and h2 */
}

.import-icon p {
    font-size: 14px;
    line-height: 1.5;
    margin-top: 5px; /* Adds space between h2 and p */
}

.type-icon {
    display: flex;
    flex-direction: column;  /* Aligns items in a column (image, h2, and p) */
    align-items: center;     /* Centers the content horizontally */
    text-align: center;
    margin-bottom: 20px; /* Adjust the spacing below the image */

}

.type-icon img {
    width: 170px;
    height: auto;
    cursor: pointer;
    margin-bottom: 20px; /* Adds space below the image */
}

.type-icon h2 {
    font-size: 18px;
    margin-top: 10px; /* Adds space between the image and h2 */
}

.type-icon p {
    font-size: 14px;
    line-height: 1.5;
    margin-top: 5px; /* Adds space between h2 and p */
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

<nav>
    <img src="images/sentlogo.png" alt="Logo" class="logo">
    <div class="nav-links">
        <a href="Customer.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'Customer.php' ? 'active' : ''; ?>">Home</a>
        <a href="custDashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custDashboard.php' ? 'active' : ''; ?>">Dashboard</a>
        <a href="custRealtime.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custRealtime.php' ? 'active' : ''; ?>">Real-Time Sentiment Analysis</a>
        <a href="custSentiment.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'custSentiment.php' || basename($_SERVER['PHP_SELF']) == 'custtypeown.php' || basename($_SERVER['PHP_SELF']) == 'custImport.php' || basename($_SERVER['PHP_SELF']) == 'custImportresult.php' || basename($_SERVER['PHP_SELF']) == 'custtyperesult.php') ? 'active' : ''; ?>">Sentiment Analysis</a>
        <a href="custAbout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custAbout.php' ? 'active' : ''; ?>">About Us</a>
        <a href="custLogin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custLogin.php' ? 'active' : ''; ?>">Login</a>

        <!-- Sign Up link with custom class -->
        <a href="custSignup.php" class="signup-link <?php echo basename($_SERVER['PHP_SELF']) == 'custSignup.php' ? 'active' : ''; ?>">Sign Up</a>
    </div>
</nav>

<div class="main-content">
        <h1>SENTIMENT ANALYSIS</h1>
        <p>Categorises Facebook Comments Under Positive,  <br> Neutral or Negative.</p>
        <div class="emoji-container">
            <div class="Positive">
                <img src="images/happy.png" alt="Positive">
            </div>
            <div class="Neutral">
                <img src="images/neutral.png" alt="Neutral">
            </div>
            <div class="Negative">
                <img src="images/aangryy.png" alt="Negative">
            </div>
        </div>
    </div>

    <div class="comment-icon">
        <div class="import-icon">
            <a href="custImport.php"><img src="images/custImport.png" alt="import live"></a>
            <h2>Import Live Comments</h2>
            <p> You can import live Facebook <br> comments by using FB Post Link <br> and perform sentiment analysis.</p>
        </div>
        <div class="type-icon">
            <a href="custtypeown.php"><img src="images/ownn.png" alt="Own Comment"></a>
            <h2>Type Your Own Comment</h2>
            <p>You can type your own comment in  <br> format and peform sentiment analysis</p>
        </div>
    </div>


    <!-- Footer -->
    <footer>
        &copy; 2024 Sentiment Analysis Project. All rights reserved.
    </footer>


</body>
</html>
