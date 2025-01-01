<?php

include "conn.php";

$username = $_POST["username"];
$pass = $_POST["password"];

if (isset($conn) && $conn) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare(
        "SELECT PasswordHash, Role FROM users WHERE Username = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $role);
        $stmt->fetch();

        if (password_verify($pass, $hashed_password)) {
            echo "Login successful";
            echo "<script>
                    localStorage.setItem('username', '$username');
                    localStorage.setItem('password', '$pass');
                    ";

            if ($role == "Admin") {
                echo "window.location.href = '../pages/dashboard.php';";
            } elseif ($role == "Trainer") {
                echo "window.location.href = '/pages/Trainer.php';";
            } else {
                echo "window.location.href = '/pages/Member.php';";
            }

            echo "
                  </script>";
            exit();
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Invalid username or password";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Connection failed: " .
        ($conn ? $conn->connect_error : "No connection object");
}
