<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red;">'.$_SESSION['error'].'</p>';
        unset($_SESSION['error']);
    }
    ?>
    <form method="POST" action="login_process.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>
