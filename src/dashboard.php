<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Display user information
$user = $_SESSION['user'];

// Display expiration date
$timestamp = $user['exp_date'];
$date = new DateTime('@' . $timestamp); // '@' specifies timestamp
$date->setTimezone(new DateTimeZone('Europe/Paris')); // Set Paris timezone

$expiration_date = $date->format('Y-m-d');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>IPTV Dashboard</title>
</head>
<body>
<div class="container py-5">
        <h1 class="text-center mb-4">Welcome, <?= htmlspecialchars($user['username']); ?>!</h1>
        <div class="row g-4">
            <!-- LIVE TV Button -->
            <div class="col-md-4">
                <a href="live_tv.php" class="dashboard-button text-center">
                    <i class="bi bi-tv"></i>
                    LIVE TV
                </a>
            </div>
            <!-- MOVIES Button -->
            <div class="col-md-4">
                <a href="movies.php" class="dashboard-button text-center">
                    <i class="bi bi-film"></i>
                    MOVIES
                </a>
            </div>
            <!-- SERIES Button -->
            <div class="col-md-4">
                <a href="series.php" class="dashboard-button text-center">
                    <i class="bi bi-collection-play"></i>
                    SERIES
                </a>
            </div>
        </div>
    </div>
    <!-- Expiration Date -->
    <div class="expiration-date">
            Subscription Expires on: <strong><?= htmlspecialchars($expiration_date); ?></strong>
        </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>