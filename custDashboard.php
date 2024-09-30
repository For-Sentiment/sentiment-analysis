<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View - Sentiment Analysis</title>
    <link rel="stylesheet" href="customer.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">


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
            background-color: #003366;
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

/* Sentiment Counts Section (Landscape layout for emojis and counts) */
#sentiment-counts {
    background-color: white;
    display: flex;
    justify-content: space-around;
    align-items: center;
    border: 3px solid #003366;
    padding: 20px;
    margin: 40px auto; /* Increased margin-top to give more space from the top */
    border-radius: 10px;
    width: 80%;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

h1 {
    font-family: 'Roboto', sans-serif; /* Google Font */
    font-size: 4em;
    color: #2e4c8d;
    font-weight: 700;
    margin-left: 20px;

}


        .sentiment-item {
            text-align: center;
            display: inline-block;
            font-size: 1.2em;
        }

        .sentiment-item img {
            width: 50px;
            height: 50px;
            display: block;
            margin: 0 auto 10px auto;
        }

        .sentiment-item span {
            font-weight: bold;
            font-size: 1.2em;
            color: #2e4c8d;
            display: block;
        }

        /* Chart Styling */
        #sentimentChart {
            background-color: white;
            border: 3px solid #003366;
            display: block;
            margin: 40px auto;
            max-width: 90%;
            height: 300px; /* Adjust height for a more compact view */
            border-radius: 10px;

        }

        /* Footer Styling */
        footer {
            background-color: #003366;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            margin-top: 110px;
        }

        footer p {
            margin: 0;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
        }

        @media (max-width: 480px) {
            nav .nav-links a {
                font-size: 0.9rem; /* Adjust for very small screens */
            }

            nav .logo {
                width: 100px; /* Smaller logo for mobile */
            }

            h1 {
                font-size: 1.5rem; /* Smaller font for small devices */
            }
        }

        @media (orientation: landscape) {
            h1 {
                font-size: 1.5rem; /* Smaller font size in landscape */
            }

            #sentiment-counts {
                flex-direction: row; /* Change to row layout in landscape */
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
            <a href="custSentiment.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'custSentiment.php' || 
                basename($_SERVER['PHP_SELF']) == 'custtypeown.php' || 
                basename($_SERVER['PHP_SELF']) == 'custImport.php' || 
                basename($_SERVER['PHP_SELF']) == 'custImportresult.php' || 
                basename($_SERVER['PHP_SELF']) == 'custtyperesult.php') ? 'active' : ''; ?>">Sentiment Analysis</a>
            <a href="custAbout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'custAbout.php' ? 'active' : ''; ?>">About Us</a>
        </div>
    </nav>

    <!-- Main Heading -->
    <h1>Sentiment Analysis Dashboard</h1>

    <!-- Sentiment Counts Section (Landscape layout) -->
    <div id="sentiment-counts">
        <!-- Positive sentiment with happy image -->
        <div class="sentiment-item">
            <img src="images/happy.png" alt="Happy Emoji"> 
            <span id="positive-count">1000</span> <!-- Positive sentiment count -->
            <span id="positive-percent">50%</span> <!-- Positive sentiment percentage -->
        </div>

        <!-- Neutral sentiment with neutral image -->
        <div class="sentiment-item">
            <img src="images/neutral.png" alt="Neutral Emoji"> 
            <span id="neutral-count">300</span> <!-- Neutral sentiment count -->
            <span id="neutral-percent">30%</span> <!-- Neutral sentiment percentage -->
        </div>

        <!-- Negative sentiment with angry image -->
        <div class="sentiment-item">
            <img src="images/aangryy.png" alt="Angry Emoji"> 
            <span id="negative-count">100</span> <!-- Negative sentiment count -->
            <span id="negative-percent">20%</span> <!-- Negative sentiment percentage -->
        </div>
    </div>

    <!-- Chart Section -->
    <canvas id="sentimentChart" width="700" height="150"></canvas>

    <div id="results-container"></div> <!-- Results will be displayed here -->

    <!-- Footer -->
    <footer>
        &copy; 2024 Sentiment Analysis Project. All rights reserved.
    </footer>


    <script>
document.addEventListener('DOMContentLoaded', function() {
    const sentimentCounts = JSON.parse(localStorage.getItem('sentimentCounts'));

    if (sentimentCounts) {
        const total = sentimentCounts.positive + sentimentCounts.neutral + sentimentCounts.negative;
        const positivePercentage = ((sentimentCounts.positive / total) * 100).toFixed(2);
        const neutralPercentage = ((sentimentCounts.neutral / total) * 100).toFixed(2);
        const negativePercentage = ((sentimentCounts.negative / total) * 100).toFixed(2);

        // Update the counts and percentages in the HTML
        document.getElementById('positive-count').textContent = sentimentCounts.positive;
        document.getElementById('positive-percent').textContent = positivePercentage + '%';
        document.getElementById('neutral-count').textContent = sentimentCounts.neutral;
        document.getElementById('neutral-percent').textContent = neutralPercentage + '%';
        document.getElementById('negative-count').textContent = sentimentCounts.negative;
        document.getElementById('negative-percent').textContent = negativePercentage + '%';

        const ctx = document.getElementById('sentimentChart').getContext('2d');
        const sentimentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Positive', 'Neutral', 'Negative'],
                datasets: [{
                    label: 'Sentiment Percentages',
                    data: [positivePercentage, neutralPercentage, negativePercentage],
                    backgroundColor: ['rgba(50, 150, 250, 0.6)', 'rgba(150, 150, 150, 0.6)', 'rgba(200, 50, 50, 0.6)'], // Darker colors
                    borderColor: ['rgba(50, 150, 250, 1)', 'rgba(150, 150, 150, 1)', 'rgba(200, 50, 50, 1)'], // Darker borders
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '1px solid black', // Darker tick font color
                            font: {
                        weight: 'bold' // Bold text for y-axis ticks
                    },
                    callback: function(value) {
                        return value + '%';
                    }

                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)', // Subtle darker grid line
                        }
                    },
                    x: {
                ticks: {
                    color: '#333', // Darker tick font color
                    font: {
                        weight: 'bold' // Bold text for x-axis ticks
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)', // Subtle darker grid line
                }

                    }
                }
            }
        });
    }
});


    </script>
</body>
</html>
