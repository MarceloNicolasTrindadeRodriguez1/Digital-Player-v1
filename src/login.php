<?php

session_start();

$loginOption = $_POST['loginOption'];

if ($loginOption === 'credentials') {
    // Get user's username and password
    $server = $_POST['server'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // XtreamUI API end point 
    $api_url = "$server/player_api.php?username=$username&password=$password";

    // Make the API request
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);

    if ($data['user_info']['auth'] === 1) {
        // Authentication successful
        $_SESSION['user'] = $data['user_info'];
        header("Location: dashboard.php");
    } else {
        // Authentication failed
        echo "<h1>Login Failed: Invalid credentials</h1>";
        echo "<a href='index.php'>Go back</a>";
    }
} elseif ($loginOption === 'link') {
    // Get user's link
    $link = $_POST['link'];

    // Make the API request
    $response = file_get_contents($link);

    // Check if the response contains M3U content
    if (strpos($response, '#EXTM3U') !== false) {
        // Authentication successful
        $_SESSION['m3u_link'] = $link;
        header("Location: dashboard.php");
    } else {
        // Authentication failed
        echo "<h1>Login Failed: Invalid link</h1>";
        echo "<a href='index.php'>Go back</a>";
    }
} else {
    // Invalid login option
    echo "<h1>Login Failed: Invalid login option</h1>";
    echo "<a href='index.php'>Go back</a>";
}

?>

