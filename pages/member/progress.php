<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        .no-records {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<?php
include "../../component/topbar.php";
include "../../validity/checker.php";
include "../../validity/user.php";
include "../../component/membersidebar.php";

// Check if the 'who' parameter is provided in the URL
if (isset($_GET["who"]) && !empty($_GET["who"])) {
    $userNameInput = $_GET["who"]; // Get the username from the URL parameter

    // Connect to the database
    include "../../activity/conn.php";

    // Query to get the UserID for the given username
    $sql = "SELECT UserID FROM users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userNameInput);
    $stmt->execute();
    $stmt->store_result();

    // If the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId); // Bind the result to the UserID variable
        $stmt->fetch(); // Fetch the result to populate the UserID variable

        // Now fetch the progress records for the user
        $progressSql = "SELECT * FROM progress WHERE MemberID = ?";
        $progressStmt = $conn->prepare($progressSql);
        $progressStmt->bind_param("i", $userId);
        $progressStmt->execute();
        $progressResult = $progressStmt->get_result();

        echo "<div class='container'>";
        echo "<h1>Progress Records for Username: " .
            htmlspecialchars($userNameInput) .
            "</h1>";

        // Display progress records
        if ($progressResult->num_rows > 0) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>
                    <th>Progress ID</th>
                    <th>Weight</th>
                    <th>BMI</th>
                    <th>Performance Notes</th>
                    <th>Progress Date</th>
                  </tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($row = $progressResult->fetch_assoc()) {
                echo "<tr>
                        <td>" .
                    htmlspecialchars($row["ProgressID"]) .
                    "</td>
                        <td>" .
                    htmlspecialchars($row["Weight"]) .
                    "</td>
                        <td>" .
                    htmlspecialchars($row["BMI"]) .
                    "</td>
                        <td>" .
                    htmlspecialchars($row["PerformanceNotes"]) .
                    "</td>
                        <td>" .
                    htmlspecialchars($row["ProgressDate"]) .
                    "</td>
                      </tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='no-records'>No progress records found for this member.</p>";
        }

        echo "</div>";
    } else {
        echo "<p class='error-message'>User not found.</p>";
    }

    // Close the connection
    $conn->close();
} else {
    echo "<p class='error-message'>No username provided in the URL.</p>";
}
?>

</body>
</html>
