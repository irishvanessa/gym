<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Classes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
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

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .full {
            color: #dc3545;
            font-weight: bold;
        }

        p {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<?php
include "../component/topbar.php";
include "../validity/checker.php";
include "../validity/user.php";
include "../component/membersidebar.php";
include "../activity/conn.php";

if (isset($_GET["who"]) && !empty($_GET["who"])) {
    $who = $_GET["who"];

    // Retrieve the user ID based on the 'who' (username)
    $sql = "SELECT UserID FROM users WHERE Username = '$who'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user["UserID"];
    } else {
        die("<p>User not found.</p>");
    }
} else {
    die("<p>No username specified.</p>");
}

// Handle toggle booking logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ClassID"])) {
    $classId = $_POST["ClassID"];

    // Check if booking exists for this user and class
    $checkSql = "SELECT * FROM bookings WHERE UserID = '$userId' AND ClassID = '$classId'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Booking exists, so delete it (cancel booking)
        $deleteSql = "DELETE FROM bookings WHERE UserID = '$userId' AND ClassID = '$classId'";
        $conn->query($deleteSql);
    } else {
        // Booking doesn't exist, create new booking
        $bookingDate = date("Y-m-d H:i:s");
        $insertSql = "INSERT INTO bookings (UserID, ClassID, BookingDate) VALUES ('$userId', '$classId', '$bookingDate')";
        $conn->query($insertSql);
    }
}
?>

<div class="container">
    <h1>Available Classes</h1>

    <?php
    // Fetch classes from the database
    $sql = "SELECT * FROM classes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>
                <th>Class ID</th>
                <th>Class Name</th>
                <th>Trainer ID</th>
                <th>Date & Time</th>
                <th>Capacity</th>
                <th>Bookings</th>
                <th>Action</th>
              </tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            $classId = $row["ClassID"];
            $capacity = $row["Capacity"];

            // Get the number of current bookings for this class
            $bookingSql = "SELECT COUNT(*) as booking_count FROM bookings WHERE ClassID = '$classId'";
            $bookingResult = $conn->query($bookingSql);
            $bookingData = $bookingResult->fetch_assoc();
            $currentBookings = $bookingData["booking_count"];

            // Check if the class is full
            $isFull = $currentBookings >= $capacity;

            // Check if the user has already booked this class
            $bookingSql = "SELECT * FROM bookings WHERE UserID = '$userId' AND ClassID = '$classId'";
            $bookingResult = $conn->query($bookingSql);
            $isBooked = $bookingResult->num_rows > 0;

            echo "<tr>
                    <td>" .
                htmlspecialchars($row["ClassID"]) .
                "</td>
                    <td>" .
                htmlspecialchars($row["ClassName"]) .
                "</td>
                    <td>" .
                htmlspecialchars($row["TrainerID"]) .
                "</td>
                    <td>" .
                htmlspecialchars($row["ClassDateTime"]) .
                "</td>
                    <td>" .
                htmlspecialchars($capacity) .
                "</td>
                    <td>" .
                htmlspecialchars($currentBookings) .
                " / " .
                htmlspecialchars($capacity) .
                "</td>
                    <td>";

            // Display the booking/cancel button or show "Full"
            if ($isFull) {
                echo "<span class='full'>Full</span>";
            } else {
                echo "<form method='post'>
                        <input type='hidden' name='ClassID' value='" .
                    htmlspecialchars($classId) .
                    "'>
                        <button type='submit'>" .
                    ($isBooked ? "Cancel" : "Book") .
                    "</button>
                      </form>";
            }

            echo "</td>
                  </tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No classes available.</p>";
    }
    ?>
</div>

</body>
</html>
