<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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


        /* About Page Container */
        .about-container {
            width: 80%;
            margin: 0 auto;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.7); /* Slightly transparent white background */
            border-radius: 15px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
            text-align: center;
            min-height: 600px; /* Adjust this value as needed */
            margin-top: 30px;
        }

        /* Header Section */
        .about-header {
            margin-bottom: 30px;
        }

        .about-header h1 {
            font-size: 3em;
            color: #003366;
            font-weight: bold;
            text-transform: uppercase;
        }

        .about-header .know-more {
            font-size: 1.4em;
            color: #003366;
            margin-bottom: 5px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Main Body Section */
        .about-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
        }

        /* Illustration Styling */
        .illustration {
            flex: 1.5;
            text-align: center;
            position: relative; /* Added for positioning */
        }

        .illustration-img {
            width: 90%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            opacity: 0; /* Start hidden for fade effect */
            transition: opacity 0.5s ease-in-out; /* Fade effect */
        }

        /* Zoom Effect */
        .zoom {
            transform: scale(2); /* Adjust scale as needed */
            transition: transform 0.3s ease; /* Smooth transition */
            z-index: 10; /* Bring to front */
        }

        /* Description Styling */
        .description {
            flex: 2;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background for readability */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            text-align: left;
            margin-left: 30px;
        }

        /* Description Paragraph */
        .description p {
            font-size: 1.1em;
            color: #333;
            line-height: 1.8;
            margin-bottom: 20px;
            opacity: 1; /* Ensure text is visible on load */
            transition: opacity 0.5s ease-in-out; /* Fade effect */
        }

        /* Button styling for slideshow */
        .slideshow-controls {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .slideshow-controls button {
            background-color: #3b5998;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .slideshow-controls button:hover {
            background-color: #243a69;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-body {
                flex-direction: column;
                align-items: center;
            }

            .description {
                margin-left: 0;
                margin-top: 20px;
            }

            .illustration-img {
                width: 80%;
            }
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
        <a href="custLogin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custLogin.php' ? 'active' : ''; ?>">Login</a>

        <!-- Sign Up link with custom class -->
        <a href="custSignup.php" class="signup-link <?php echo basename($_SERVER['PHP_SELF']) == 'custSignup.php' ? 'active' : ''; ?>">Sign Up</a>
    </div>
</nav>
    <!-- About Us Section -->
    <div class="about-container">
        <div class="about-header">
            <p class="know-more">Know More</p>
            <h1>About Us</h1>
        </div>

        <div class="about-body">
            <div class="illustration">
                <img id="slideshow-image" class="illustration-img" src="images/image0.jpg" alt="Slideshow Image">
            </div>
            <div class="description">
                <p id="slideshow-text">"Welcome to our NLP-based study on sentiments at Bulacan State University. Our goal is to analyze the sentiments expressed by people during and after the COVID-19 pandemic."</p>
            </div>
        </div>

        <div class="slideshow-controls">
            <button id="prevBtn">Previous</button>
            <button id="nextBtn">Next</button>
        </div>
    </div>

    <script>
        // Slideshow Data
        const slideshowData = [
            {
                image: "images/emoji.png",
                text: "Our website is part of a capstone project titled An NLP-based Study of Pre and Post-Pandemic Sentiments in Bulacan State University. The goal of this project is to analyze the sentiments expressed by people through their comments during and after the COVID-19 pandemic. By examining posts from a specific page within the university, we aim to identify whether these comments reflect positive, negative, or neutral emotions."
            },
            {
                image: "images/slide1.png",
                text: "We specialize in analyzing user feedback and comments to better understand people's feelings and opinions. Our application uses a powerful tool called SentimentIntensityAnalyzer to evaluate the sentiment of each comment. We clean the comments by removing special characters and repeated phrases, and then we classify them as positive, negative, or neutral based on their overall sentiment. This process helps us gain valuable insights, allowing us to respond effectively to feedback and improve user experiences."
            },
            {
                image: "images/slayd2.png",
                text: "In sentiment analysis, polarity refers to the emotional tone of text, which can be positive, negative, or neutral, and is determined by the specific words used. For example, in the sentence 'I love the new design, but I hate the price,' the word 'love' contributes a strong positive sentiment, while 'hate' carries a strong negative sentiment. Each word is assigned a sentiment score, with positive words like 'love' scoring high and negative words like 'hate' scoring low. The overall sentiment is determined by calculating a compound score from these individual scores; if the negative score outweighs the positive, the sentence is classified as negative, even if it contains positive words. This analysis highlights how the strength and weight of words shape the overall sentiment expressed in a sentence."
            }
        ];

        let currentIndex = 0;

        const illustrationImg = document.getElementById("slideshow-image");
        const slideshowText = document.getElementById("slideshow-text");

        function updateSlideshow() {
            illustrationImg.src = slideshowData[currentIndex].image;
            slideshowText.textContent = slideshowData[currentIndex].text;

            illustrationImg.style.opacity = 0; 
            slideshowText.style.opacity = 0; 

            setTimeout(() => {
                illustrationImg.style.opacity = 1; // Fade in effect for image
                slideshowText.style.opacity = 1; // Fade in effect for text
            }, 500);
        }

        document.getElementById("prevBtn").addEventListener("click", () => {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : slideshowData.length - 1;
            updateSlideshow();
        });

        document.getElementById("nextBtn").addEventListener("click", () => {
            currentIndex = (currentIndex < slideshowData.length - 1) ? currentIndex + 1 : 0;
            updateSlideshow();
        });

        illustrationImg.addEventListener("click", () => {
            illustrationImg.classList.toggle("zoom"); 
        });

        updateSlideshow();
    </script>
</body>
</html>
