<?php
// custProcsignup.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Basic validation to check if fields are empty
    if (empty($username) || empty($password)) {
        header('Location: custSignup.php?error=Please fill in all fields');
        exit();
    }

    // Check if the user already exists
    $xmlFile = 'nlp.xml';
    $userExists = false;

    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
        foreach ($xml->user as $user) {
            if ((string)$user->username === $username) {
                $userExists = true;
                break;
            }
        }
    } else {
        // Create a new XML file if it doesn't exist
        $xml = new SimpleXMLElement('<users/>');
    }

    if (!$userExists) {
        // Add new user to the XML
        $newUser = $xml->addChild('user');
        $newUser->addChild('username', $username);

        // Store the password as plain text
        $newUser->addChild('password', $password); 

        // Set user type to USER
        $newUser->addChild('user_type', 'USER'); // Add user type for new users

        // Save the XML file
        $xml->asXML($xmlFile);

        // Redirect to login page
        header('Location: custLogin.php?success=Account created, please login');
        exit();
    } else {
        // Handle case where user already exists
        header('Location: custSignup.php?error=User already exists');
        exit();
    }
}
?>
