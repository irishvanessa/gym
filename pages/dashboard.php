<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
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

        /* Styling for the form buttons */
        .user-type-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .user-type-buttons button {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user-type-buttons button:hover {
            background-color: #0056b3;
        }

        /* Styling for the user table */
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .user-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .user-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .user-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Styling for the delete button in the table */
        .delete-button {
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .user-table th, .user-table td {
                font-size: 14px;
                padding: 8px;
            }

            .user-type-buttons button {
                font-size: 14px;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <?php include "../component/topbar.php"; ?>
    <?php include "../validity/checker.php"; ?>
    <?php include "../validity/user.php"; ?>
    <?php include "../component/adminsidebar.php"; ?>

    <div class="container">
        <h1>User Management</h1>

        <form method="post" class="user-type-buttons">
            <button type="submit" name="type" value="Admin">Admin</button>
            <button type="submit" name="type" value="Trainer">Trainer</button>
            <button type="submit" name="type" value="Member">Member</button>
        </form>

        <?php include "../activity/conn.php"; ?>

        <?php
        // Ensure $conn is initialized properly
        if (!isset($conn)) {
            echo "<p>Database connection not established.</p>";
            exit();
        }

        $type = isset($_POST["type"]) ? $_POST["type"] : "Admin";

        $query = "SELECT * FROM users WHERE ROLE = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            echo "<table class='user-table'>";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Action</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td># " . htmlspecialchars($row["UserID"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Username"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                echo "<td><button class='delete-button' onclick=\"confirmDelete('" .
                    htmlspecialchars($row["UserID"]) .
                    "')\">Delete</button></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No users found for the selected role.</p>";
        }

        $stmt->close();
        ?>
    </div>

    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = `/activity/delete_user.php?delete_user=${userId}`;
            }
        }
    </script>
</body>
</html>
