<?php

session_start();

//Get user's username and password
$server = $_POST['server'];
$username = $_POST['username'];
$password = $_POST['password'];

//XtreamUI API end point 
$api_url = "$server/player_api.php?username=$username&password=$password";

//Make the API request
$response = file_get_contents($api_url);
$data = json_decode($response, true);

if ($data['user_info']['auth'] === 1){
    // Auhtentification successful
    $_SESSION['user'] = $data['user_info'];
    header("Location: dashboard.php");
} else {
    // Authentication failed
    echo "<h1>Login Failed: Invalid credentials</h1>";
    echo "<a href='index.php'>Go back</a>";
}


?>


