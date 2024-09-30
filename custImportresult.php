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
            background: #8b9dc3; /* Background color of the scrollbar track */
        }

        ::-webkit-scrollbar-thumb {
            background: #8b9dc3; /* Color of the scrollbar handle */
            border-radius: 5px; /* Rounded corners of the scrollbar handle */
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #888; /* Color on hover */
        }

        /* Firefox scrollbar styling */
        * {
            scrollbar-width: thin; /* Set scrollbar width */
            scrollbar-color: #555 #8b9dc3; /* Handle color and track color */
        }

        body {
            font-family: Arial, sans-serif; /* Default font for the body */
            background-color: #f4f4f4; /* Light background color for the page */
            margin: 0; /* Remove default margin */
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure the body takes full viewport height */
        }



        /* Main Content */
        .main-content {
            margin: 20px auto; /* Center content with margin */
            display: flex;
            flex-direction: column; 
            justify-content: flex-start;
            width: 90%; /* Width of the main content */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            padding: 20px; /* Space inside main content */
        }

        .header-container {
            position:relative;
    display: flex; /* Use flexbox for alignment */
    align-items: center; /* Center items vertically */
    justify-content: space-between; /* Space out items */
    margin-bottom: 20px; /* Space below the header */
}

.main-content h2 {
    color: #243a69;
    font-size: 25px;
    font-family: Arial Black, sans-serif; 
    margin: 0; /* Remove margin for better alignment */
}

.main-content p {
    color: #243a69;
    font-size: 15px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: -10px;
    margin-right: 25px;
}

.dashboard-button {
    margin-left: 20px; /* Space between heading and button */
}

        .dashboard-button {
            background-color: #8b9dc3; /* Button color */
            font-weight: 600; /* Bold font for button */
            color: #243a69;
            width: 15%; /* Wider button */
            height: 40px; /* Height of the button */
            border-radius: 5px; /* Rounded corners */
            font-size: 16px; /* Larger font size */
            cursor: pointer; /* Pointer cursor on hover */
            text-decoration: none; /* Remove underline */
            border: none; /* Remove border */
            transition: background-color 0.3s; /* Smooth transition */
            margin-bottom: 20px; /* Space below button */
        }

        .dashboard-button:hover {
            background-color: #3b5998; /* Darker background on hover */
        }

        .results-container {
    max-height: 60vh; /* Max height for results */
    overflow-y: auto; /* Scroll if too many results */
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
    padding: 20px; /* Inner padding */
    margin-top: 20px; /* Space above results */
    color: #243a69; /* Dark color for headings */
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse; /* Remove space between cells */
    table-layout: fixed; /* Ensures columns are equally spaced */

}

th, td {
    padding: 10px; /* Inner cell padding */
    text-align: left; /* Align text to the left */
    border: 1px solid #243a69; /* Same border for both th and td */
    overflow: hidden; /* Prevent text overflow */
    min-height: 50px; /* Set minimum height */
}

th {
    background-color: #8b9dc3; /* Header background */
    color: #243a69; /* Dark color for headings */
    position: sticky; /* Make header sticky */
    top: -20px; /* Stick to the top */
    z-index: 10; /* Ensure it stays above other content */
    border-bottom: 2px #243a69; /* Border under header */
    text-align: center; /* Center align header */
    min-height: 50px; /* Set minimum height for header */
}

td {
    border-right: 1px solid #243a69; /* Line between columns */
    color: #243a69; /* Dark color for headings */
    font-weight: bolder;
}

td:last-child, th:last-child {
    border-right: none; /* Remove right border for last column */
}

.emoji {
    text-align: center; /* Center align emojis */
}

.emoji img {
    width: 60px; /* Adjust size as needed */
    height: auto; /* Maintain aspect ratio */
}

/* Center align Sentiments and Emojis */
td:nth-child(2), /* Sentiment column */
    td:nth-child(3) { /* Emoji column */
        text-align: center; /* Center align these columns */
    }

    td:nth-child(2) { /* Targeting sentiment column */
        text-transform: uppercase; /* Transform text to uppercase */
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


        /* Footer Styles */
        footer {
            margin-top: auto; /* Push footer to the bottom */
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
        <!-- Logo on the left -->
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

    <div class="main-content">
    <div class="header-container">
        <h2>SENTIMENT ANALYSIS</h2>
        <button class="dashboard-button" onclick="window.location.href='custDashboard.php'">See Dashboard</button>
    </div>
    <p>Imported Facebook Comments.</p>
    <div class="results-container" id="results-container">
        <!-- Results will be populated here -->
    </div>
</div>


    <!-- Footer -->
    <footer>
        &copy; 2024 Sentiment Analysis Project. All rights reserved.
    </footer>


    <script>
document.addEventListener('DOMContentLoaded', function() {
    const results = JSON.parse(localStorage.getItem('analysisResults'));
    const container = document.getElementById('results-container');

    if (results) {
        let positiveCount = 0;
        let neutralCount = 0;
        let negativeCount = 0;

        let html = '<table><thead><tr><th>Comment</th><th>Sentiment</th><th>Emoji</th></tr></thead><tbody>';
        results.forEach(result => {
            let emojiSrc;
            switch(result.sentiment) {
                case 'positive':
                    emojiSrc = 'images/happy.png';
                    positiveCount++;
                    break;
                case 'neutral':
                    emojiSrc = 'images/neutral.png';
                    neutralCount++;
                    break;
                case 'negative':
                    emojiSrc = 'images/aangryy.png';
                    negativeCount++;
                    break;
                default:
                    emojiSrc = '';
            }
            html += `<tr><td>${result.comment}</td><td>${result.sentiment}</td><td class="emoji"><img src="${emojiSrc}" alt="${result.sentiment}"></td></tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;

        // Store sentiment counts in local storage
        const sentimentCounts = { positive: positiveCount, neutral: neutralCount, negative: negativeCount };
        localStorage.setItem('sentimentCounts', JSON.stringify(sentimentCounts));
    } else {
        container.innerHTML = '<p>No results found.</p>';
    }
});
    </script>

</body>
</html>
