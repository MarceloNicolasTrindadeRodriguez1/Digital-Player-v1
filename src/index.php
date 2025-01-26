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
            <form action="login.php" method="POST" id="loginForm">
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
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
     // Get the form and modal
     document.getElementById('loginForm').addEventListener('submit', function() {
        document.getElementById('loader').style.display = 'block';
    });

</script>
</body>
</html>




