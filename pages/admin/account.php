<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout {
            margin-top: 20px;
            background-color: #dc3545;
        }
        .logout:hover {
            background-color: #c82333;
        }
        .debug-info {
            background-color: #f8f9fa;
            border-left: 4px solid #17a2b8;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include "../../component/topbar.php"; ?>
    <?php include "../../validity/checker.php"; ?>
    <?php include "../../validity/user.php"; ?>
    <?php include "../../component/adminsidebar.php"; ?>
    <?php include "../../activity/conn.php"; ?>

    <div class="container">
        <h1>Edit User Account</h1>

        <?php
        $username = isset($_GET["who"]) ? $_GET["who"] : "";
        $dbUsername = $dbFullName = $dbEmail = $dbPhone = $dbPasswordHash = "";

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
                echo "<div class='debug-info'>Debugging Info: User details fetched successfully.</div>";
            } else {
                echo "<p>User not found.</p>";
            }
            $stmt->close();
        }
        ?>

        <form action="/activity/update_user.php" method="POST">
            <input type="hidden" name="username" value="<?= htmlspecialchars(
                $dbUsername
            ) ?>">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars(
                $dbUsername
            ) ?>" readonly>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave empty to keep the same">

            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars(
                $dbFullName
            ) ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars(
                $dbEmail
            ) ?>">

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars(
                $dbPhone
            ) ?>">

            <button type="submit" name="update_user">Update</button>
        </form>

        <button class="logout" onclick="clearLocalStorage()">Logout</button>
    </div>

    <script>
        function clearLocalStorage() {
            localStorage.clear();
            window.location.reload();
        }
    </script>
</body>
</html>
