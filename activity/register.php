<?php

include "conn.php";

$username = isset($_POST["username"]) ? trim($_POST["username"]) : null;
$pass = isset($_POST["password"]) ? trim($_POST["password"]) : null;
$role = isset($_POST["role"]) ? trim($_POST["role"]) : null;
$fullname =
    isset($_POST["firstName"]) && isset($_POST["lastName"])
        ? trim($_POST["firstName"]) .
            "_" .
            trim($_POST["middleName"]) .
            "_" .
            trim($_POST["lastName"])
        : null;
$email = isset($_POST["email"]) ? trim($_POST["email"]) : null;
$phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : null;

// Validate required fields
if (!$username || !$pass || !$role || !$fullname || !$email || !$phone) {
    echo "All fields are required.";
    exit();
}

if (isset($conn) && $conn) {
    // Check for duplicate username, email, or phone
    $stmt = $conn->prepare(
        "SELECT COUNT(*) FROM users WHERE Username = ? OR Email = ? OR Phone = ?"
    );
    $stmt->bind_param("sss", $username, $email, $phone);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Username, email, or phone already exists.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare(
            "INSERT INTO users (Username, PasswordHash, `Role`, FullName, Email, Phone) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssssss",
            $username,
            $hashed_password,
            $role,
            $fullname,
            $email,
            $phone
        );

        if ($stmt->execute()) {
            echo "New record created successfully";
            header("Location: ../pages/account.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
} else {
    echo "Connection failed: " .
        ($conn ? $conn->connect_error : "No connection object");
}
?>
