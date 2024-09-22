<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Check if login notification should be shown
$showNotification = false;
if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
    $showNotification = true;
    unset($_SESSION['login_success']); // Unset it so it doesn't show again
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
        font-family: 'Open Sans';
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #456ab6;
        overflow: hidden;
    }

    .content {
        position: relative;
        width: 20.5%;
        right: 80px;
        height: 99.3%;
        background-color: #2e4c8d;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: filter 0.3s ease;
        margin-right: 70%;
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

    .notification h2, .logout-confirmation h2 {
        margin: 0 0 20px 0;
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
        left: 1%;
        padding: 50px;
        margin: 0;
        font-size: 19px;
        font-family: 'Open Sans';
        display: flex;
        flex-direction: column;
        height: 80%;
    }

    .dashboard p {
        position: absolute;
        line-height: 1.1;
        margin-top: -100px;
        right: -20px;
        margin-bottom: 0;
    }

    .dashboard ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Spread items evenly */
    height: 100%;
}


    .dashboard .top-items {
        position: absolute;
        display: flex;
        flex-direction: column;
    }

    

    .dashboard .bottom-items {
        margin-top: auto;
        display: flex;
        flex-direction: column;
    }

    .dashboard li {
    margin-bottom: 20px; /* Ensure space between each item */
}

.dashboard a {
    color: white;
    text-decoration: none;
    padding: 10px;
    display: block;
    font-size: 18px;
}

.dashboard a:hover {
    font-weight: 600; 
}
    


    .facebook-logo {
        position: absolute;
        right: 260px; 
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

    .dashboard-icon {
        position: relative;
        padding-left: 35px;
    }

    .dashboard-icon::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/dashb.png');
        background-size: cover;
    }

    .realtime-icon {
        position: relative;
        padding-left: 24px;
    }

    .realtime-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/realt.png');
        background-size: cover;
    }

    .sentiment-icon {
        position: relative;
        padding-left: 24px;
    }

    .sentiment-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/sentit.png');
        background-size: cover;
    }

    .acc-icon {
        position: relative;
        padding-left: 24px;
    }

    .acc-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/account.png');
        background-size: cover;
    }

    .logout-icon {
        position: relative;
        padding-left: 24px;
    }

    .logout-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background-image: url('images/logout.png');
        background-size: cover;
    }

    .dashboard-icon::before, .realtime-icon::before, .sentiment-icon::before, 
.acc-icon::before, .logout-icon::before {
    left: -20px; /* Adjust this for better alignment */
}


    .dashboard-layout {
        position: absolute;
        right: 2%;
        top: 5%;
        width: 65%;
        height: 90%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 20px;
    }

    .chart-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        color: black;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .chart-card h3 {
        margin: 0 0 15px;
        font-size: 18px;
        color: #333;
    }

    .chart {
        height: 200px;
        background-color: #f0f0f0;
        border-radius: 10px;
    }
</style>
</head>
<body>

<div class="content blurred">
    <div class="facebook-logo">
        <i class="fab fa-facebook-f"></i>
    </div>
    <div class="dashboard">
        <p>Exploring Voices: <br> Pre and Post-Pandemic <br>Facebook Insights at BulSU</p>

        <ul>
            <li class="top-items">
                <li><a href="Dashboard.php" class="dashboard-icon"> &nbsp; Dashboard</a></li>
                <li><a href="RealTAnalysis.php" class="realtime-icon"> &nbsp; Realtime Sentiment <br class="Analys"> Analysis</a></li>
                <li><a href="SentAnalysis.php" class="sentiment-icon"> &nbsp; Sentiment Analysis</a></li>
                </li>
            <li class="bottom-items">
                <li><a href="Account.php" class="acc-icon"> &nbsp; Account</a></li>
                <li><a href="#" onclick="showLogoutConfirmation()" class="logout-icon"> &nbsp; Logout</a></li>
            </li>
        </ul>
    </div>
</div>

<div class="notification" id="notification">
    <h2>Login <br> Successfully</h2>
    <button onclick="closeNotification()">OK</button>
</div>

<div class="logout-confirmation" id="logout-confirmation">
    <h2>Logging Out?</h2>
    <button onclick="logout()">Yes</button>
    <button onclick="closeLogoutConfirmation()">No</button>
</div>

<div class="dashboard-layout blurred">
    <div class="chart-card">
        <h3>Number Of Comments By Year</h3>
        <div class="chart"></div>
    </div>
    <div class="chart-card">
        <h3>Sentiment Over Time</h3>
        <div class="chart"></div>
    </div>
    <div class="chart-card">
        <h3>Realtime Sentiment</h3>
        <div class="chart"></div>
    </div>
    <div class="chart-card">
        <h3>Post-Pandemic Sentiment</h3>
        <div class="chart"></div>
    </div>
    <div class="chart-card">
        <h3>Comments Per Post</h3>
        <div class="chart"></div>
    </div>
    <div class="chart-card">
        <h3>Topic Frequency</h3>
        <div class="chart"></div>
    </div>
</div>

<script>
    function showNotification() {
        document.getElementById('notification').style.display = 'block';
        document.querySelector('.content').classList.add('blurred');
        document.querySelector('.dashboard-layout').classList.add('blurred');
    }

    function closeNotification() {
        document.getElementById('notification').style.display = 'none';
        document.querySelector('.content').classList.remove('blurred');
        document.querySelector('.dashboard-layout').classList.remove('blurred');
    }

    function showLogoutConfirmation() {
        document.getElementById('logout-confirmation').style.display = 'block';
        document.querySelector('.content').classList.add('blurred');
        document.querySelector('.dashboard-layout').classList.add('blurred');
    }

    function closeLogoutConfirmation() {
        document.getElementById('logout-confirmation').style.display = 'none';
        document.querySelector('.content').classList.remove('blurred');
        document.querySelector('.dashboard-layout').classList.remove('blurred');
    }

    function logout() {
        window.location.href = 'index.php';
    }

    document.addEventListener('DOMContentLoaded', function() {
        showNotification();
    });
    
</script>
</body>
</html>
