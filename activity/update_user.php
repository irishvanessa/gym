<?php
include "conn.php";

if (isset($_POST["update_user"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Check if the database connection is valid
    if (!$conn) {
        die("Database connection failed.");
    }

    // If password is provided, hash it
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $updateQuery =
            "UPDATE users SET PasswordHash = ?, FullName = ?, Email = ?, Phone = ? WHERE Username = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param(
            "sssss",
            $passwordHash,
            $full_name,
            $email,
            $phone,
            $username
        );
    } else {
        // If password is not provided, update only other fields
        $updateQuery =
            "UPDATE users SET FullName = ?, Email = ?, Phone = ? WHERE Username = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssss", $full_name, $email, $phone, $username);
    }

    if ($stmt->execute()) {
        // Fetch the user's role to determine redirection
        $roleQuery = "SELECT Role FROM users WHERE Username = ?";
        $roleStmt = $conn->prepare($roleQuery);
        $roleStmt->bind_param("s", $username);
        $roleStmt->execute();
        $roleStmt->bind_result($role);
        $roleStmt->fetch();
        $roleStmt->close();

        // Redirect based on the user's role
        if ($role === "Admin") {
            header("Location: /pages/admin/account.php");
        } elseif ($role === "Trainer") {
            header("Location: /pages/trainer/account.php");
        } elseif ($role === "Member") {
            header("Location: /pages/member/account.php");
        } else {
            echo "Unknown role.";
        }
        exit();
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    $stmt->close();
}
?>
