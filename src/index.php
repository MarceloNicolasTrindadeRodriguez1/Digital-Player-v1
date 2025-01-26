<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $_SESSION['user'] = [
                'server' => $server,
                'username' => $username,
                'password' => $password,
                'user_info' => $data['user_info'],
                'exp_date' => $data['user_info']['exp_date'],
            ];
            header("Location: dashboard.php");
            exit();
        } else {
            // Authentication failed
            $error = "Login Failed: Invalid credentials";
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
            exit();
        } else {
            // Authentication failed
            $error = "Login Failed: Invalid link";
        }
    } else {
        // Invalid login option
        $error = "Login Failed: Invalid login option";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <title>IPTV Player Login</title>
</head>
<body>
<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="" method="POST" id="loginForm">
            <!-- Login Option -->
            <div class="mb-3">
                <label for="loginOption" class="form-label">Login Option</label>
                <select class="form-select" id="loginOption" name="loginOption" onchange="toggleLoginFields()">
                    <option value="credentials">XtreamCode API</option>
                    <option value="link">M3U Link</option>
                </select>
            </div>
            <!-- Server Field -->
            <div class="mb-3" id="serverField">
                <label for="server" class="form-label">Server</label>
                <input type="text" class="form-control" id="server" name="server" placeholder="e.g., http://example.com" required>
            </div>
            <!-- Username Field -->
            <div class="mb-3" id="usernameField">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <!-- Password Field -->
            <div class="mb-3" id="passwordField">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <!-- Link Field -->
            <div class="mb-3" id="linkField" style="display: none;">
                <label for="link" class="form-label">M3U Link</label>
                <input type="url" class="form-control" id="link" name="link" placeholder="Enter your link">
            </div>
            <!-- Login Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="#" class="text-muted">Forgot your password?</a>
        </div>
    </div>
</div>
<!-- Loading Modal -->
<div id="loader" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    function toggleLoginFields() {
        const loginOption = document.getElementById('loginOption').value;
        const serverField = document.getElementById('serverField');
        const usernameField = document.getElementById('usernameField');
        const passwordField = document.getElementById('passwordField');
        const linkField = document.getElementById('linkField');

        if (loginOption === 'credentials') {
            serverField.style.display = 'block';
            serverField.querySelector('input').setAttribute('required', 'required');
            usernameField.style.display = 'block';
            usernameField.querySelector('input').setAttribute('required', 'required');
            passwordField.style.display = 'block';
            passwordField.querySelector('input').setAttribute('required', 'required');
            linkField.style.display = 'none';
            linkField.querySelector('input').removeAttribute('required');
        } else {
            serverField.style.display = 'none';
            serverField.querySelector('input').removeAttribute('required');
            usernameField.style.display = 'none';
            usernameField.querySelector('input').removeAttribute('required');
            passwordField.style.display = 'none';
            passwordField.querySelector('input').removeAttribute('required');
            linkField.style.display = 'block';
            linkField.querySelector('input').setAttribute('required', 'required');
        }
    }

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('loader').style.display = 'block';
    });
</script>
</body>
</html>