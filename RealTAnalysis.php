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

        .dashboard-icon {
        position: relative;
        padding-left: 24px;
    }

    .dashboard-icon::before {
        content: '';
        position: absolute;
        left: 0;
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
    margin-top: 50px;
    margin-right: 50px;
}

.Positive img, .Neutral img, .Negative img {
    width: 110px;
    height: auto;
    margin: 0 10px;
    cursor: pointer;
}

.facebooklogo {
    position: absolute;
    right: 260px; 
    top: 20%;
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

/* Search Box Container */
.search-container {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 430px;
    margin-right: 350px;
}

/* Styling the Search Box */
.search-box {
    background-color: white;
    border-radius: 50px;
    display: flex;
    align-items: center;
    padding: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 500px; /* Set desired search box width */
}

/* Styling the Input Field */
.search-box input[type="text"] {
    border: none;
    outline: none;
    font-size: 16px;
    padding: 10px;
    border-radius: 50px;
    width: 100%; /* Make the input take full width */
    margin-right: 10px; /* Add spacing between input and button */
    flex-grow: 1; /* Allow the input to grow with the available space */
}

/* Styling the Search Button */
.search-btn {
    background-color: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Styling the Icon with Circular Background */
.search-icon {
    background-color: #3b5998; /* Blue background for the circle */
    color: white;
    font-size: 20px;
    border-radius: 50%;
    padding: 10px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#result {
    position: absolute;
    font-size: 40px;
    color: white;
    margin-top: 650px;
    margin-right: 370px;
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
        <h1>REAL TIME <br> SENTIMENT ANALYSIS</h1>
        <p>Update the Facebook link weekly.</p>
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

            
<div class="search-container">
    <form action="RealTresult.php" method="POST">
        <div class="search-box">
            <input type="text" id="comment" name="comment" placeholder="Enter comment to be analyzed" required>
            <button type="submit" class="search-btn">
                <i class="search-icon">&#128269;</i>
            </button>
        </div>
    </form>
</div>


    <div class="facebooklogo">
        <i class="fab fa-facebook-f"></i>
    </div>


<!-- Overlay -->
<div id="formOverlay" class="form-overlay"></div>


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
