<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            background-color: #dc3545;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .debug-info {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
            font-size: 14px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>

    <?php include "../../component/topbar.php"; ?>
    <?php include "../../validity/checker.php"; ?>
    <?php include "../../validity/user.php"; ?>
    <?php include "../../component/membersidebar.php"; ?>
    <?php include "../../activity/conn.php"; ?>

    <div class="container">
        <?php
        // Get the username from the URL or session
        $username = isset($_GET["who"]) ? $_GET["who"] : "";

        // Variables for form fields
        $dbUsername = $dbFullName = $dbEmail = $dbPhone = $dbPasswordHash = "";

        // Fetch user data from database
        if ($username) {
            $stmt = $conn->prepare(
                "SELECT Username, PasswordHash, FullName, Email, Phone FROM users WHERE Username = ?"
            );
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result(
                    $dbUsername,
                    $dbPasswordHash,
                    $dbFullName,
                    $dbEmail,
                    $dbPhone
                );
                $stmt->fetch();
            } else {
                echo "<p class='debug-info'>User not found.</p>";
            }
            $stmt->close();
        }
        ?>

        <h1>Edit User Account</h1>

        <form action="/activity/update_user.php" method="POST">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars(
                $dbUsername
            ); ?>">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars(
                $dbUsername
            ); ?>" readonly>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave empty to keep the same">

            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars(
                $dbFullName
            ); ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(
                $dbEmail
            ); ?>">

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars(
                $dbPhone
            ); ?>">

            <button type="submit" name="update_user">Update</button>
        </form>

        <button class="logout-btn" onclick="clearLocalStorage()">Logout</button>
    </div>

    <script>
        function clearLocalStorage() {
            localStorage.clear();
            window.location.reload();
        }
    </script>
</body>
</html>
