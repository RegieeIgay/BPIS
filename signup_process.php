<?php
session_start();
include 'db_connect.php'; // change this if your DB file has a different name

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($password)) {
        $_SESSION['signup_error'] = "All fields are required.";
        header("Location: signup.php");
        exit();
    }

    // Check if user already exists
    $checkQuery = "SELECT id FROM users WHERE username = ?";  
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['signup_error'] = "Username already taken.";
        $stmt->close();
        header("Location: signup.php");
        exit();
        
    }

    $stmt->close();

    // Insert new user (without hashing - you can enable hash if needed)
    $insertQuery = "INSERT INTO users (username, password, user_type) VALUES (?, ?, 'user')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ss", $username, $password); // store password as plain text (not recommended)

    if ($stmt->execute()) {
        $_SESSION['signup_success'] = "Registration successful! You can now log in.";
        header("Location: signup.php");
    } else {
        $_SESSION['signup_error'] = "Something went wrong. Please try again.";
        header("Location: signup.php");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: signup.php");
    exit();
}
