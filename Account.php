<?php
session_start();

// Load the XML file
$xmlFile = 'nlp.xml';
$xml = simplexml_load_file($xmlFile);

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $userFound = false;

    // Check for existing user credentials
    foreach ($xml->user as $user) {
        if ($username === (string)$user->username && $password === (string)$user->password) {
            $userFound = true;

            // Check if user is ADMIN
            if ($user->user_type == 'ADMIN') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['username'] = $username; // Store username in session
                $_SESSION['user_type'] = 'ADMIN'; // Store user type in session
                header('Location: Account.php'); // Redirect to admin account page
            } else {
                // If user is CUSTOMER
                $_SESSION['customer_logged_in'] = true;
                $_SESSION['username'] = $username; // Store username in session
                $_SESSION['user_type'] = 'USER'; // Automatically set user type to USER
                header('Location: Customer.php'); // Redirect to customer dashboard
            }
            break;
        }
    }

    // If the user does not exist, add them as a USER
    if (!$userFound) {
        // Check if the username already exists to prevent duplicates
        $usernameExists = false;
        foreach ($xml->user as $user) {
            if ($username === (string)$user->username) {
                $usernameExists = true;
                break;
            }
        }

        // Only add new user if the username does not already exist
        if (!$usernameExists) {
            $new_user = $xml->addChild('user');
            $new_user->addChild('username', $username);
            $new_user->addChild('password', $password); // Store the password directly
            $new_user->addChild('user_type', 'USER'); // Automatically assign user type as USER

            // Save back to the XML file
            $xml->asXML($xmlFile);
            
            // Log in the new user
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['username'] = $username; // Store username in session
            $_SESSION['user_type'] = 'USER'; // Set user type to USER
            header('Location: Customer.php'); // Redirect to customer dashboard
        } else {
            // Username already exists, handle accordingly
            header('Location: custLogin.php?error=Username already exists'); // Redirect with an error
            exit();
        }
    }
}

// Display user details (username and user type) after login
if (isset($_SESSION['username'])) {
    $userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Not set';
} else {
    $userType = '';
}


// Handle adding a new user
if (isset($_POST['add_user'])) {
    $newUser = $xml->addChild('user');
    $newUser->addChild('username', htmlspecialchars($_POST['username']));
    $newUser->addChild('password', htmlspecialchars($_POST['password']));
    $newUser->addChild('user_type', htmlspecialchars($_POST['user_type']));

    // Save back to the XML file
    $xml->asXML($xmlFile);
    header("Location: Account.php");
    exit();
}

// Handle deleting a user
if (isset($_GET['delete'])) {
    $usernameToDelete = $_GET['delete'];

    $xml = new DOMDocument;
    $xml->load($xmlFile); 

    $users = $xml->getElementsByTagName('user');
    foreach ($users as $user) {
        $username = $user->getElementsByTagName('username')->item(0)->textContent;
        if ($username === $usernameToDelete) {
            $user->parentNode->removeChild($user);
            $xml->save($xmlFile);
            header("Location: Account.php");
            exit();
        }   
    }

    echo 'Error: User not found.';
}

// Handle editing a user
if (isset($_POST['edit_user'])) {
    $usernameToEdit = $_POST['old_username'];
    foreach ($xml->user as $user) {
        if ((string)$user->username === $usernameToEdit) {
            $user->username = htmlspecialchars($_POST['username']);
            $user->password = htmlspecialchars($_POST['password']);
            $user->user_type = htmlspecialchars($_POST['user_type']);
            $xml->asXML($xmlFile);
            header("Location: Account.php"); 
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <link rel="shortcut icon" type="x-icon" href="images/logoo1.png">
    <link rel="stylesheet" href="acc.css">
    <title>Account</title>
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
        <form action="Account.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label>
            <div class="form-group">
                <input type="password" id="add_password" name="password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('add_password', this)"></i>
            </div>

            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="ADMIN">Admin</option>
                <option value="USER">User</option>
            </select><br>

            <input class="adduser" type="submit" name="add_user" value="Add User">
            <button type="button" class="cancel-button" onclick="hideAddUserForm()">Cancel</button>
        </form>
    </div>

    <div id="editUserForm" class="form-container">
        <h2>Edit User</h2>
        <form action="Account.php" method="post">
            <input type="hidden" id="old_username" name="old_username">

            <label for="username">Username:</label>
            <input type="text" id="edit_username" name="username" required><br>

            <label for="password">Password:</label>
            <div class="form-group">
                <input type="password" id="edit_password" name="password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('edit_password', this)"></i>
            </div>

            <label for="user_type">User Type:</label>
            <select id="edit_user_type" name="user_type" required>
                <option value="ADMIN">Admin</option>
                <option value="USER">User</option>
            </select><br>

            <input class="updateuser" type="submit" name="edit_user" value="Update User">
            <button type="button" class="cancel-button" onclick="hideEditUserForm()">Cancel</button>
        </form>
    </div>


<div id="formOverlay" class="form-overlay"></div>


<div id="notification" class="notification">
    <p>Your notification message here</p>
    <button onclick="closeNotification()">Close</button>
</div>

<div id="logout-confirmation" class="logout-confirmation">
    <h2>Logging Out?</h2>
    <button onclick="logout()">Yes</button>
    <button onclick="closeLogoutConfirmation()">No</button>
</div>



<script>
    function togglePassword(passwordId, icon) {
        const passwordInput = document.getElementById(passwordId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash'); 
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye'); 
        }
    }

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
