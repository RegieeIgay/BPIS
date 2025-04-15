<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card" style="width: 100%; max-width: 450px;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Sign Up</h2>

                <?php
                if (isset($_SESSION['signup_error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['signup_error'] . '</div>';
                    unset($_SESSION['signup_error']);
                }

                if (isset($_SESSION['signup_success'])) {
                    echo '<div class="alert alert-success" role="alert">' . $_SESSION['signup_success'] . '</div>';
                    unset($_SESSION['signup_success']);
                }
                ?>

                <form method="POST" action="signup_process.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Sign Up</button>
                </form>

                <!-- Link to login -->
                <div class="text-center mt-3">
                    <a href="login.php">Already have an account? Login here</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
