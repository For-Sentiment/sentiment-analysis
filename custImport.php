<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View - Sentiment Analysis</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
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

        /* Main content */
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
            width: 110px;
            margin: 0 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .Positive img:hover, .Neutral img:hover, .Negative img:hover {
            transform: scale(1.1);
        }

        .import-form {
            position: relative; /* Updated to relative */
        }

        .upload-form {
            padding: 20px; /* Reduced padding for height */
            margin-top: 20px;
            width: 150%; 
            max-width: 600px; /* Increased max-width for better layout */
            border-radius: 5px;
            border: 1px solid black;
            box-sizing: border-box; 
            height: auto; /* Set to auto to reduce height */
            margin-right: 230px;
        }

        .upload-form label {
            font-size: 20px; 
            display: block; 
            margin-bottom: 10px;
            color: #243a69;
            font-weight: bolder;
 
        }

        .upload-form input[type="file"] {
            font-size: 15px; 
            display: block; 
            margin-top: 10px;
            color: #243a69;
            padding: 20px;
            cursor: pointer;
        }

        .upload-form button {
            font-size: 17px;
            padding: 10px 20px;
            background-color: #3b5998;
            color: white;
            border: none;
            cursor: pointer;
            display: block; 
            margin-top: 20px; 
        }

        .upload-form button:hover {
            cursor: pointer;
            background-color: #243a69;
        }

        /* Loader styles */
        .loader {
            display: none; /* Hidden by default */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 8px solid #f3f3f3; /* Light grey */
            border-top: 8px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .results-container {
            max-height: 80vh;
            overflow-y: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .emoji {
            font-size: 20px;
        }

/* Footer Styling */
        /* Footer Styling */
        footer {
            background-color: #003366;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 110px;
        }

        footer p {
            margin: 0;
            color: white;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
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

    <div class="main-content">
        <h1>SENTIMENT ANALYSIS</h1>
        <p>Categorizes Facebook Comments Under Positive, <br> Neutral, or Negative.</p>

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

        <div class="import-form">
            <form class="upload-form" id="upload-form" method="post" enctype="multipart/form-data" target="results-frame">
                <label for="file">Choose CSV file to import:</label><br>
                <input type="file" name="file" accept=".csv" required><br><br>
                <button type="submit" id="analyze-button">Upload and Analyze</button>
                <div class="loader" id="loader"></div> <!-- Loader -->
            </form>
        </div>

        <!-- This iframe will be used to display the results -->
        <iframe name="results-frame" style="display:none;"></iframe>

    <!-- Footer -->
    <footer>
        &copy; 2024 Sentiment Analysis Project. All rights reserved.
    </footer>

    </div>

    <script>

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('upload-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch('https://nlp-sentiment-analysis-f4u4.onrender.com/upload_csv', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.results) {
        // Save results to localStorage and redirect
        localStorage.setItem('analysisResults', JSON.stringify(data.results));
        window.location.href = 'custImportresult.php';
    } else {
        alert('No results found.');
    }
})
.catch(error => {
    console.error('Error fetching results:', error);
    alert('Error fetching results: ' + error.message);
});

    });
});
        const form = document.getElementById('upload-form');
        const loader = document.getElementById('loader');

        form.addEventListener('submit', function() {
            loader.style.display = 'block'; // Show the loader
        });
    </script>
</body>
</html>
