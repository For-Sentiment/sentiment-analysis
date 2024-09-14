<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="shortcut icon" type="x-icon" href="logoo1.png">
<title>Home</title>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #456ab6;
            overflow: hidden;
        }

        .sidebar {
            width: 29.5%;
            height: 99.3%;
            background-color: #2e4c8d;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            position: fixed;
            transition: filter 0.3s ease;
        }

       .blurred {
        filter: blur(5px);
        pointer-events: none;
    }

        .notification, .logout-confirmation {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            text-align: center;
            display: none;
        }
        .notification button, .logout-confirmation button {
            padding: 10px 50px;
            background-color: white;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: large;
            font-weight: bolder;
        }

        .notification button:hover, .logout-confirmation button:hover {
            background-color: #3b5998;
        }

        .logout-confirmation button {
            margin: 0 10px;
        }

        .dashboard {
        text-align: left;
        color: white;
        position: absolute;
        top: 10%;
        left: 6%;
        padding: 0;
        margin: 0;
        font-size: 23px;
        font-family: 'Open Sans';
        display: flex;
        flex-direction: column;
        height: 80%;
        }

        .dashboard p {
        line-height: 1.1;   
        margin-bottom: 20px;  
        }

        .dashboard ul {
        padding: 0;
        margin: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        }

        .dashboard .top-items {
        display: flex;
        flex-direction: column;
    }

    .dashboard .bottom-items {
        margin-top: auto;
        display: flex;
        flex-direction: column;
    }


        .dashboard ul li {
        padding-left: 10px;
        margin-top: 20px;
        list-style-type: none;
        }

        .dashboard li a {
        color: white;
        line-height: 1.5;
        margin-bottom: 5px;
        text-decoration: none;
        font-weight: normal;
        transition: font-weight 0.1s ease-in-out;

        }

        .dashboard a:hover {
            font-weight: 600; 
        }

        .facebook-logo {
        position: absolute;
        right: 30px; 
        top: 0;
        background-color: #2e4c8d;
        color: white;
        padding: 10px;
        border-radius: 50%;
        display: inline-block;
        text-align: center;
        width: 40px;
        height: 95%;
        line-height: 20px;
        font-size: 90px;   
        }

        .main-content {
            margin-top: -20px;
            margin-left: 540px; 
            display: flex;
            flex-direction: column; 
            justify-content: flex-start;
            height: 100%;
            width: 70%;
        }

    .main-content h1 {
        color: white;
        font-size: 50px;
        font-family: Arial Black, sans-serif; 
        margin-bottom: 10px;
        margin-right: 30%;
    }

    .main-content p {
        color: white;
        font-size: 25px;
        font-family: Arial Black, sans-serif; 
        margin-bottom: 10px;
        margin-right: 25px;
    }

.emoji-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    margin-right: 10px;
}

.Positive img, .Neutral img, .Negative img {
    width: 110px;
    height: auto;
    margin: 0 10px;
    cursor: pointer;
}

.facebooklogo {
    position: absolute;
    right: 270px; 
    top: 18%;
    background-color: #456ab6;
    color: rgba(255, 255, 255, 0.5);
    padding: 20px; /* Reduce padding for smaller size */
    border-radius: 50%;
    display: inline-block;
    text-align: center;
    width: 80px; /* Adjust width */
    height: 80px; /* Adjust height */
    line-height: 90px; /* Match height for centering the logo vertically */
    font-size: 580px; /* Adjust font size */
    z-index: -99;
}

.import-form {
    position: absolute;
}

.upload-form {
    background-color: #456ab6;
    padding: 40px; /* Adjust padding as needed */
    margin-top: 400px;
    width: 150%; /* Adjust width to control border length */
    border-radius: 5px;
    border: 1px solid #ddd;
    box-sizing: border-box; /* Include padding and border in the element’s total width and height */
    position: relative;
}


.upload-form label {
    font-size: 20px; /* Set font size of label */
    color: white;
    display: block; /* Ensure label takes up the full width */
    margin-bottom: 10px; /* Add margin below the label for spacing */
}

.upload-form input[type="file"] {
    font-size: 15px; /* Set font size of label */
    display: block; /* Ensure file input takes up full width */
    margin-top: 10px;
    color:white;
    padding: 20px;
    background-color: #3b5998
}


.upload-form button {
    font-size: 17px;
    padding: 10px 20px;
    background-color: #3b5998;
    color: white;
    border: none;
    cursor: pointer;
    display: block; /* Ensure button takes up the full width */
    margin-top: 70px; /* Ensure button is below the file input */
}

.upload-form button:hover, input[type="file"]:hover {
    background-color: #2e4c8d;
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


</style>
</head>
<body>

<div class="sidebar">
    <div class="facebook-logo">
        <i class="fab fa-facebook-f"></i>
    </div>
    <div class="dashboard">
        <p>Exploring Voices: <br> Pre and Post-Pandemic <br>Facebook Insights at BulSU</p>
        <ul>
            <li class="top-items">
                <li><a href="home.php" class="dashboard-icon"> &nbsp; Dashboard</a></li>
                <li><a href="RealTAnalysis.php" class="realtime-icon"> &nbsp; Realtime Sentiment Analysis</a></li>
                <li><a href="SentAnalysis.php" class="sentiment-icon"> &nbsp; Sentiment Analysis</a></li>
            </li>
            <li class="bottom-items">
                <li><a href="Account.php" class="acc-icon"> &nbsp; Account</a></li>
                <li><a href="#" onclick="showLogoutConfirmation()" class="logout-icon"> &nbsp; Logout</a></li>
            </li>
        </ul>
    </div>
</div>

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
<form class="upload-form" id="upload-form" method="post" enctype="multipart/form-data">
    <label for="file">Choose CSV file to import:</label><br>
        <input type="file" name="file" accept=".csv" required><br><br>
        <button type="submit">Upload and Analyze</button>
    </form>

    </div>

    <!-- This iframe will be used to display the results -->
    <iframe name="results-frame" style="display:none;"></iframe>

<div class="facebooklogo">
        <i class="fab fa-facebook-f"></i>
    </div>

<!-- Notification -->
<div id="notification" class="notification">
    <p>Your notification message here</p>
    <button onclick="closeNotification()">Close</button>
</div>

<!-- Logout Confirmation Dialog -->
<div id="logout-confirmation" class="logout-confirmation">
    <h2>Logging Out?</h2>
    <button onclick="logout()">Yes</button>
    <button onclick="closeLogoutConfirmation()">No</button>
</div>

<!-- Your other scripts for notifications, logout, etc. -->
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
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.results) {
                localStorage.setItem('analysisResults', JSON.stringify(data.results));
                window.location.href = 'ImportResult.php';
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


        function showLogoutConfirmation() {
            document.getElementById('logout-confirmation').style.display = 'block';
            document.querySelector('.sidebar').classList.add('blurred');
            document.querySelector('.main-content').classList.add('blurred');
        }

        function closeLogoutConfirmation() {
            document.getElementById('logout-confirmation').style.display = 'none';
            document.querySelector('.sidebar').classList.remove('blurred');
            document.querySelector('.main-content').classList.remove('blurred');
        }

        function logout() {
            window.location.href = 'Account.php?logout=true';
        }

        function showNotification() {
            document.getElementById('notification').style.display = 'block';
            document.querySelector('.sidebar').classList.add('blurred');
            document.querySelector('.main-content').classList.add('blurred');
        }

        function closeNotification() {
            document.getElementById('notification').style.display = 'none';
            document.querySelector('.sidebar').classList.remove('blurred');
            document.querySelector('.main-content').classList.remove('blurred');
        }
    </script>

</body>
</html>
