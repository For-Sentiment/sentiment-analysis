<?php
// custProclogin.php

session_start();

// Get the submitted credentials and sanitize input
$username = htmlspecialchars(trim($_POST['username']));
$password = htmlspecialchars(trim($_POST['password']));

// XML file path
$xmlFile = 'nlp.xml';
$userExists = false;
$newUserCreated = false;

if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    
    // Loop through existing users to check credentials
    foreach ($xml->user as $user) {
        if ((string)$user->username === $username) {
            if ((string)$user->password === $password) {
                // User exists and credentials match
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = (string)$user->user_type; // Get user type from XML
                $userExists = true;
                header('Location: Customer.php'); // Redirect to customer dashboard
                exit();
            } else {
                // User exists but wrong password
                header('Location: custLogin.php?error=Invalid credentials');
                exit();
            }
        }
    }
    
    // If user does not exist, create a new user
    if (!$userExists) {
        // Check if username already exists
        $usernameExists = false;
        foreach ($xml->user as $user) {
            if ((string)$user->username === $username) {
                $usernameExists = true;
                break;
            }
        }
        
        // Only add new user if the username does not already exist
        if (!$usernameExists) {
            $new_user = $xml->addChild('user');
            $new_user->addChild('username', $username);
            $new_user->addChild('password', $password); // Store the password directly (consider hashing for security)
            $new_user->addChild('user_type', 'USER'); // Automatically assign user type as USER
            
            // Save back to the XML file
            $xml->asXML($xmlFile);
            
            // Log in the new user
            $_SESSION['username'] = $username; // Store username in session
            $_SESSION['user_type'] = 'USER'; // Set user type to USER
            header('Location: Customer.php'); // Redirect to customer dashboard
            exit();
        } else {
            // Username already exists, handle accordingly
            header('Location: custLogin.php?error=Username already exists');
            exit();
        }
    }
} else {
    // Handle the case when the XML file doesn't exist
    die('Error: XML file does not exist.');
}
?>
