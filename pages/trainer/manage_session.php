<?php
include "../../component/topbar.php";
include "../../validity/checker.php";
// include "../../validity/user.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

// Get class ID from URL or set to 0 if not provided
$classId = isset($_GET["class_id"]) ? $_GET["class_id"] : 0;

echo "<div class='container'>";
echo "<h1>Manage Sessions for Class ID: <span class='highlight'>$classId</span></h1>";

// Query to fetch sessions for the given class
$sql = "SELECT * FROM session WHERE class_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $classId);
$stmt->execute();
$result = $stmt->get_result();

// Create session button
echo "<div class='create-session'>
        <a href='./create_session.php?class_id=$classId' class='btn create-btn'>Create New Session</a>
      </div>";

// Display sessions in a table
echo "<table class='table'>
        <thead>
            <tr>
                <th>Session ID</th>
                <th>Session Name</th>
                <th>Start Date & Time</th>
                <th>End Date & Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>";

while ($row = $result->fetch_assoc()) {
    $sessionId = $row["id"];
    echo "<tr>
            <td>{$row["id"]}</td>
            <td>{$row["session_name"]}</td>
            <td>{$row["datetime_start"]}</td>
            <td>{$row["datetime_end"]}</td>
            <td>
                <a href='./edit_session.php?id=$sessionId' class='btn edit-btn'>Edit</a> |
                <a href='./delete_session.php?id=$sessionId' class='btn delete-btn' onclick=\"return confirm('Are you sure you want to delete this session?');\">Delete</a> |
                <a href='./attendance.php?session_id=$sessionId' class='btn attendance-btn'>Manage Attendance</a> |
                <a href='./payments.php?session_id=$sessionId' class='btn payments-btn'>Manage Payments</a>
            </td>
        </tr>";
}

echo "</tbody></table>";
echo "</div>";

$conn->close();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }

    h1 {
        color: #333;
        font-size: 24px;
    }

    .highlight {
        color: #007bff;
        font-weight: bold;
    }

    .create-session {
        margin: 20px 0;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 16px;
        text-decoration: none;
        display: inline-block;
        margin: 5px;
        cursor: pointer;
    }

    .create-btn {
        background-color: #28a745;
        color: white;
    }

    .create-btn:hover {
        background-color: #218838;
    }

    .edit-btn {
        background-color: #ffc107;
        color: white;
    }

    .edit-btn:hover {
        background-color: #e0a800;
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .attendance-btn {
        background-color: #17a2b8;
        color: white;
    }

    .attendance-btn:hover {
        background-color: #138496;
    }

    .payments-btn {
        background-color: #007bff;
        color: white;
    }

    .payments-btn:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f8f9fa;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
