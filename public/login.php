<?php
session_start();

$current_time = time();

if (isset($_SESSION['lockout_time']) && $current_time < $_SESSION['lockout_time']) {
    $_SESSION['error'] = "Too many failed attempts. Please try again later.";
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 4;
}

$xmlFile = 'users.xml';

if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
} else {
    $_SESSION['error'] = "User data not found.";
    header("Location: index.php");
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$valid = false;
foreach ($xml->user as $user) {
    if ((string)$user->username === $username && (string)$user->password === $password) {
        $valid = true;
        break;
    }
}

if ($valid) {
    $_SESSION['attempts'] = 4;
    $_SESSION['lockout_time'] = null; 
    $_SESSION['user'] = $username;
    header("Location: home.php"); 
    exit();
} else {
    // Invalid credentials
    $_SESSION['attempts'] -= 1;
    if ($_SESSION['attempts'] > 0) {
        $_SESSION['error'] = "Username and Password incorrect. {$_SESSION['attempts']} more attempts.";
    } else {
        $_SESSION['error'] = "Account locked. Please try again in 30 seconds.";
        $_SESSION['lockout_time'] = $current_time + 30; 
        $_SESSION['attempts'] = 4; 
    }
    header("Location: index.php"); 
    exit();
}
?>
