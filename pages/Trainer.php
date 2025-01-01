<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php
include "../component/topbar.php";
include "../validity/checker.php";
include "../validity/user.php";
include "../component/trainersidebar.php";
?>

<div class="container">
    <h1>Trainer Dashboard</h1>
    <a href="./trainer/add_class.php">Add Class</a>

    <?php
    include "../activity/conn.php"; // Include the database connection

    // Get the username from the query string
    $username = isset($_GET["who"]) ? $_GET["who"] : "";

    // Check if the username is provided
    if (empty($username)) {
        echo "<p class='error-message'>Error: No username provided.</p>";
        exit();
    }

    // Fetch the UserID based on the provided username
    $stmt = $conn->prepare("SELECT UserID FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userID);
        $stmt->fetch();
        $stmt->close();

        // Fetch classes for the fetched UserID (TrainerID)
        $sql = "SELECT c.*, u.Username AS TrainerName
                FROM classes c
                LEFT JOIN users u ON c.TrainerID = u.UserID
                WHERE c.TrainerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display the classes
        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th>Username</th>
                            <th>Class Date/Time</th>
                            <th>Capacity (Total/Limit)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $result->fetch_assoc()) {
                $classId = $row["ClassID"];
                $capacity = $row["Capacity"];

                // Get the number of current bookings for this class
                $bookingSql =
                    "SELECT COUNT(*) as booking_count FROM bookings WHERE ClassID = ?";
                $bookingStmt = $conn->prepare($bookingSql);
                $bookingStmt->bind_param("i", $classId);
                $bookingStmt->execute();
                $bookingResult = $bookingStmt->get_result();
                $bookingData = $bookingResult->fetch_assoc();
                $currentBookings = $bookingData["booking_count"];

                // Display the class information
                echo "<tr>
                        <td>" .
                    htmlspecialchars($row["ClassID"]) .
                    "</td>
                        <td>" .
                    htmlspecialchars($row["ClassName"]) .
                    "</td>
                        <td>" .
                    htmlspecialchars($row["TrainerName"]) .
                    "</td>
                        <td>" .
                    htmlspecialchars($row["ClassDateTime"]) .
                    "</td>
                        <td>" .
                    $currentBookings .
                    " / " .
                    $capacity .
                    "</td>
                        <td>
                            <a href='./trainer/edit_class.php?id=" .
                    $row["ClassID"] .
                    "'>Edit</a> |
                            <a href='./trainer/delete_class.php?id=" .
                    $row["ClassID"] .
                    "'>Delete</a> |
                            <a href='./trainer/view_members.php?class_id=" .
                    $row["ClassID"] .
                    "'>View Members</a> |
                            <a href='./trainer/manage_session.php?class_id=" .
                    $row["ClassID"] .
                    "'>Manage Session</a>
                        </td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No classes found for this trainer.</p>";
        }
    } else {
        echo "<p class='error-message'>Error: User not found.</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</div>

</body>
</html>
