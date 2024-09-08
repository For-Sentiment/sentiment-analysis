<?php
session_start();


// Check for logout request
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$xmlFile = 'users.xml';
if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
} else {
    die('Error: Unable to load user data.');
}

if (isset($_POST['add_user'])) {
    $newUser = $xml->addChild('user');
    $newUser->addChild('username', $_POST['username']);
    $newUser->addChild('password', $_POST['password']);
    $newUser->addChild('user_type', $_POST['user_type']);
    $xml->asXML($xmlFile);
    header("Location: Account.php");
    exit();
}

if (isset($_GET['delete'])) {
    $usernameToDelete = $_GET['delete'];

    $xml = new DOMDocument;
    $xml->load('users.xml'); 

    $users = $xml->getElementsByTagName('user');
    foreach ($users as $user) {
        $username = $user->getElementsByTagName('username')->item(0)->textContent;
        if ($username === $usernameToDelete) {
            $user->parentNode->removeChild($user);
            $xml->save('users.xml');
            header("Location: account.php");
            exit();
        }   
    }

    echo 'Error: User not found.';
}



if (isset($_POST['edit_user'])) {
    $usernameToEdit = $_POST['old_username'];
    foreach ($xml->user as $user) {
        if ((string)$user->username === $usernameToEdit) {
            $user->username = $_POST['username'];
            $user->password = $_POST['password'];
            $user->user_type = $_POST['user_type'];
            $xml->asXML($xmlFile);
            header("Location: account.php"); 
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <link rel="shortcut icon" type="x-icon" href="logoo1.png">
    <title>Account</title>
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
            margin-left: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 70%;
        }

        
        table {
            width: auto;
            border-collapse: collapse;
            background-color:  #456ab6;
            color: white;
            text-align: center;
        }

        th, td {
            border: 1px solid #8b9dc3;
            padding: 20px 60px;
        }

        th {
            background-color: #8b9dc3;
            border: 1px solid #424e66;
        }

.edit {
    margin-top: 2px;
    margin-bottom: 1.5%;
    margin-left: -20%;
    background-color: #8b9dc3;
    padding: 3px 20px;
    font-weight: 300;
    text-decoration: none;
    font-size: 12px;
    color: rgb(0, 0, 0);
    border: 1px solid #424e66;
    border-radius: 5px ;        
    font-family:Arial, Helvetica, sans-serif;
}

.delete {
    text-decoration: none;
    margin-top: 2%;
    margin-bottom: 1.5%;
    margin-left: 0%;
    background-color: #8b9dc3;
    padding: 3px 10px;
    font-weight: 300;
    color: rgb(0, 0, 0);
    font-size: 12px;
    border: 1px solid #424e66;
    border-radius: 5px ;  
    font-family:Arial, Helvetica, sans-serif;
}


.edit, .delete{
    display: inline-block !important;

}
.edit, .delete{
    text-decoration: none !important;
    color: inherit !important; 
}

.edit:hover, .delete:hover{
    background-color: #3b5998;
}

.main-content {
    margin-top: 25px;
    margin-left: 30%; 
    display: flex;
    flex-direction: column; 
    justify-content: flex-start;
    height: 100%;
    width: 70%;
}

.group {
    margin-bottom: 40px; 
    margin-right: 75%;
    color: white;
}

.main-content h1 {
    color: white;
    font-size: 35px;
    font-family: Arial Black, sans-serif; 
    margin-bottom: 10px;
    margin-right: 51%;
}


.admin-icon {
        position: relative;
        padding-left: 70px;
    }

    .admin-icon::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 70px;
        height: 70px;
        background-image: url('images/admin.png');
        background-size: cover;
    }

    .table-actions {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-start;
        }

        .add-button {
            background-color: #8b9dc3;
            padding: 10px 20px;
            font-weight: 300;
            color: white;
            border: 1px solid #424e66;
            border-radius: 5px;
            font-family: Arial Black, sans-serif;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 783px;
            margin-top: -50px;
        }

        .add-button:hover {
            background-color: #3b5998;
        }

        .form-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #456ab6;
            padding: 50px 5%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .form-container h2 {
            margin-top: 0;
            color: white;
            font-family: Arial Black, sans-serif;
        }

        .form-container label {
            display: block;
            margin-bottom: 4px;
            color: white;
            font-family: Arial Black, sans-serif;
        }

        .form-container input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }


        .form-container .save-button , .adduser , .cancel-button , .updateuser{
            background-color: #8b9dc3;
            color: white;
            margin-top: 5px;
            font-family: Arial Black, sans-serif;

        }

        .form-container select{
            padding: 10px;
            background-color: #2e4c8d;
            color: white;  
            font-family: Arial, sans-serif;

        }

        .form-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
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
        <li><a href="Realtime Sentiment Analysis.php" class="realtime-icon"> &nbsp; Realtime Sentiment Analysis</a></li>
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
    <div class="group">
        <p class="admin-icon"><span style="font-weight: bolder; font-size: 20px;"> &nbsp; Group 2</span><br> &nbsp; &nbsp; Admin</p>
    </div>
    <h1>USER ACCOUNTS</h1>

    <div class="table-actions">
        <button class="add-button" onclick="showAddUserForm()">ADD USER</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>USERNAME</th>
                <th>USER TYPE</th>
                <th>PASSWORD</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($xml->user as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user->username); ?></td>
                <td><?php echo htmlspecialchars($user->user_type); ?></td>
                <td><?php echo htmlspecialchars($user->password); ?></td>
                <td>
                    <button class='edit' onclick="showEditUserForm('<?php echo htmlspecialchars($user->username); ?>', '<?php echo htmlspecialchars($user->user_type); ?>', '<?php echo htmlspecialchars($user->password); ?>')">EDIT</button> 
                    <button class='delete' onclick="deleteUser('<?php echo htmlspecialchars($user->username); ?>')">DELETE</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="addUserForm" class="form-container">
<h2>ADD USER</h2>
<form action="account.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="ADMIN">Admin</option>
            <option value="USER">User</option>
        </select><br>

        <input class="adduser" type="submit" name="add_user" value="Add User">
        <button type="button" class="cancel-button" onclick="hideAddUserForm()">Cancel</button>
    </form>
</div>

<!-- Edit User Form -->
<div id="editUserForm" class="form-container">
    <h2>Edit User</h2>
    <form action="account.php" method="post">
        <input type="hidden" id="old_username" name="old_username">

        <label for="username">Username:</label>
        <input type="text" id="edit_username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="edit_password" name="password" required><br>

        <label for="user_type">User Type:</label>
        <select id="edit_user_type" name="user_type" required>
            <option value="ADMIN">Admin</option>
            <option value="USER">User</option>
        </select><br>

        <input class="updateuser "type="submit" name="edit_user" value="Update User">
        <button type="button" class="cancel-button" onclick="hideEditUserForm()">Cancel</button>
    </form>
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
function showAddUserForm() {
    document.getElementById('addUserForm').style.display = 'block';
    document.getElementById('formOverlay').style.display = 'block';
}

function hideAddUserForm() {
    document.getElementById('addUserForm').style.display = 'none';
    document.getElementById('formOverlay').style.display = 'none';
}

function showEditUserForm(username, userType, password) {
    document.getElementById('editUserForm').style.display = 'block';
    document.getElementById('formOverlay').style.display = 'block';
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_user_type').value = userType;
    document.getElementById('edit_password').value = password;
    document.getElementById('old_username').value = username;
}

function hideEditUserForm() {
    document.getElementById('editUserForm').style.display = 'none';
    document.getElementById('formOverlay').style.display = 'none';
}

function deleteUser(username) {
    if (confirm("Are you sure you want to delete this user?")) {
        window.location.href = 'Account.php?delete=' + encodeURIComponent(username);
    }
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
