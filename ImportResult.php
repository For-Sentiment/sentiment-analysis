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
<link rel="shortcut icon" type="x-icon" href="images/logoo1.png">
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
        background-image: url('images/user.png');
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

    .main-content h2 {
    color: white;
    font-size: 25px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: -10px;
    margin-right: 30%;
}

.main-content p {
    color: white;
    font-size: 15px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: 40px;
    margin-right: 25px;
}

.dashboard-button{
    background-color: #8b9dc3;
    position: absolute;
    font-weight: 300;
    color: white;
    width: 10%;
    height: 35px;
    top: 10.5%;
    left: 83%;
    border-radius: 5px;
    font-size: 14px;
    font-family: Arial Black, sans-serif;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    border: 1px solid #424e66;

}


.dashboard-button:hover{
    background-color: #3b5998;
}

.results-container {
    max-height: 80vh;
    overflow-y: auto;
    padding: 20px;
    background-color: #456ab6;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    border: 2px solid white; /* Add white border */
}


table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* Ensures columns are equally spaced */
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd; /* Line between rows */
    overflow: hidden; /* Prevents text overflow */

}

th {
    background-color: #8b9dc3;
    color: white;
    position: -webkit-sticky; /* For Safari */
    position: sticky;
    top: 0; /* Stick to the top */
    z-index: 10; /* Ensure the header stays above other content */
    border-bottom: 2px solid white; /* Border under header */
    text-align: center;
}
td {
    border-right: 1px solid #ddd; /* Line between columns */
    color: white;
}

td:last-child, th:last-child {
    border-right: none; /* Remove right border for last column */
}

.emoji {
            text-align: center;
        }
        .emoji img {
            width: 60px; /* Adjust size as needed */
            height: auto;
        }

/* Center align Sentiments and Emojis */
td:nth-child(2), /* Sentiment column */
td:nth-child(3) { /* Emoji column */
    text-align: center;
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
        <h2>SENTIMENT ANALYSIS</h2>
        <p>Updated Facebook Page.</p>
        <button class="dashboard-button">See Dashboard</button>
        <div class="results-container" id="results-container">
        <!-- Results will be populated here -->
    </div>
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

document.addEventListener('DOMContentLoaded', function() {
            // Get the results from local storage
            const results = JSON.parse(localStorage.getItem('analysisResults'));
            const container = document.getElementById('results-container');

            if (results) {
                let html = '<table><thead><tr><th>Comment</th><th>Sentiment</th><th>Emoji</th></tr></thead><tbody>';
                results.forEach(result => {
                    let emojiSrc;
                    switch(result.sentiment) {
                        case 'POSITIVE':
                            emojiSrc = 'images/happy.png';
                            break;
                        case 'NEUTRAL':
                            emojiSrc = 'images/neutral.png';
                            break;
                        case 'NEGATIVE':
                            emojiSrc = 'images/aangryy.png';
                            break;
                        default:
                            emojiSrc = ''; // Default or empty if no match
                    }
                    html += `<tr><td>${result.comment}</td><td>${result.sentiment}</td><td class="emoji"><img src="${emojiSrc}" alt="${result.sentiment}"></td></tr>`;
                });
                html += '</tbody></table>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p>No results found.</p>';
            }
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
