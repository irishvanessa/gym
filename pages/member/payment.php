<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Payments</title>
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

        h1, h2 {
            text-align: center;
            color: #333;
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

        .user-info {
            text-align: center;
            margin-top: 20px;
        }

        .user-info p {
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
include "../../activity/conn.php";

// Get the username from the URL
$username = isset($_GET["who"]) ? $_GET["who"] : "";

// Check if the username is provided
if (empty($username)) {
    echo "<p class='error-message'>Error: No username provided.</p>";
    exit();
}

// Fetch user information based on the username
$userSql = "SELECT * FROM users WHERE Username = ?";
$userStmt = $conn->prepare($userSql);
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();

// Check if the user exists
if ($userResult->num_rows === 0) {
    echo "<p class='error-message'>Error: User not found.</p>";
    exit();
}

$user = $userResult->fetch_assoc();

// Fetch payment records for the user
$paymentSql = "SELECT * FROM payments WHERE MemberID = ?";
$paymentStmt = $conn->prepare($paymentSql);
$paymentStmt->bind_param("i", $user["UserID"]);
$paymentStmt->execute();
$paymentResult = $paymentStmt->get_result();
?>

<div class="container">
    <h1>Payment Details for <?php echo htmlspecialchars(
        $user["FullName"]
    ); ?></h1>

    <div class="user-info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars(
            $user["Email"]
        ); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars(
            $user["Phone"]
        ); ?></p>
    </div>

    <h2>Payment History</h2>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Amount</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($paymentResult->num_rows > 0) {
                while ($payment = $paymentResult->fetch_assoc()) {
                    echo "<tr>
                            <td>" .
                        htmlspecialchars($payment["PaymentID"]) .
                        "</td>
                            <td>" .
                        htmlspecialchars($payment["Amount"]) .
                        "</td>
                            <td>" .
                        htmlspecialchars($payment["PaymentDate"]) .
                        "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No payment records found.</td></tr>";
            } ?>
        </tbody>
    </table>
</div>

<?php $conn->close(); ?>
</body>
</html>
