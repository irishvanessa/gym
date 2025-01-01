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

echo "<h1>Manage Payments for Session: " .
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
            <th>Payment Status</th>
        </tr>";

while ($booking = $bookings->fetch_assoc()) {
    $userId = $booking["UserID"];

    // Check if the user already has a payment record
    $checkSql = "SELECT * FROM payments WHERE MemberID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    $paymentStatus = "Unpaid"; // Default status if no record found
    if ($checkResult->num_rows > 0) {
        $paymentStatus = "Paid";
    }

    // Button to toggle payment
    $buttonText = $paymentStatus == "Paid" ? "Mark Unpaid" : "Mark Paid";
    $toggleAction = $paymentStatus == "Paid" ? "delete" : "add";

    echo "<tr>
            <td>" .
        htmlspecialchars($userId) .
        "</td>
            <td>" .
        htmlspecialchars($booking["FullName"]) .
        "</td>
            <td>
                <form method='POST'>
                    <button type='submit' name='toggle_payment' value='$userId'>$buttonText</button>
                    <input type='hidden' name='action' value='$toggleAction'>
                </form>
            </td>
        </tr>";
}

echo "</table>";

// Handle payment toggle action (add or delete)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle_payment"])) {
    $userId = $_POST["toggle_payment"];
    $action = $_POST["action"];

    if ($action == "add") {
        // Add payment
        $paymentDate = date("Y-m-d H:i:s");
        $amount = 100; // Example payment amount, you can set it dynamically

        $insertSql = "INSERT INTO payments (MemberID, Amount, PaymentDate)
                      VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ids", $userId, $amount, $paymentDate);
        $insertStmt->execute();
    } elseif ($action == "delete") {
        // Delete payment
        $deleteSql = "DELETE FROM payments WHERE MemberID = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $userId);
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
