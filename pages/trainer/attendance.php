<?php
include "../../component/topbar.php";
include "../../validity/checker.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

// Get session ID from URL
$sessionId = isset($_GET["session_id"]) ? intval($_GET["session_id"]) : 0;

// Get the session details
$sql = "SELECT * FROM session WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sessionId);
$stmt->execute();
$result = $stmt->get_result();
$session = $result->fetch_assoc();

// Check if the session exists
if (!$session) {
    echo "<p>Session not found.</p>";
    exit();
}

echo "<h1>Manage Attendance for Session: " .
    htmlspecialchars($session["session_name"]) .
    "</h1>";

// Get the users who booked this class
$sql = "SELECT b.UserID, u.FullName FROM bookings b
        JOIN users u ON b.UserID = u.UserID
        WHERE b.ClassID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session["class_id"]);
$stmt->execute();
$bookings = $stmt->get_result();

echo "<table border='1'>
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Attendance</th>
        </tr>";

while ($booking = $bookings->fetch_assoc()) {
    $userId = $booking["UserID"];

    // Check if the user already has an attendance record for this session
    $checkSql = "SELECT * FROM attendance WHERE session_id = ? AND UserID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $sessionId, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    $attendanceStatus = "Absent"; // Default status if no record found
    if ($checkResult->num_rows > 0) {
        $attendanceStatus = "Present";
    }

    // Button to toggle attendance
    $buttonText =
        $attendanceStatus == "Present" ? "Mark Absent" : "Mark Present";
    $toggleAction = $attendanceStatus == "Present" ? "delete" : "add";

    echo "<tr>
            <td>" .
        htmlspecialchars($userId) .
        "</td>
            <td>" .
        htmlspecialchars($booking["FullName"]) .
        "</td>
            <td>
                <form method='POST'>
                    <button type='submit' name='toggle_attendance' value='$userId'>$buttonText</button>
                    <input type='hidden' name='action' value='$toggleAction'>
                </form>
            </td>
        </tr>";
}

echo "</table>";

// Handle attendance toggle action (add or delete)
if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["toggle_attendance"])
) {
    $userId = $_POST["toggle_attendance"];
    $action = $_POST["action"];

    if ($action == "add") {
        // Add attendance
        $attendanceDate = date("Y-m-d H:i:s");
        $attendanceStatus = "Present"; // Mark as Present

        $insertSql = "INSERT INTO attendance (UserID, AttendanceDate, Status, session_id)
                      VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param(
            "issi",
            $userId,
            $attendanceDate,
            $attendanceStatus,
            $sessionId
        );
        $insertStmt->execute();
    } elseif ($action == "delete") {
        // Delete attendance
        $deleteSql =
            "DELETE FROM attendance WHERE session_id = ? AND UserID = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("ii", $sessionId, $userId);
        $deleteStmt->execute();
    }

    // Redirect back to the same page to reflect changes
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit();
}

$conn->close();
?>
<style>
body{
    padding:0px;
    margin:0px;
    box-sizing:border-box;
}
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    td {
        color: #555;
    }

    button {
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>
