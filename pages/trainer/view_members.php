<?php include "../../component/topbar.php"; ?>
<?php include "../../component/trainersidebar.php"; ?>
<?php
include "../../activity/conn.php";
include "../../validity/checker.php";
// Check if class_id is provided
if (isset($_GET["class_id"]) && !empty($_GET["class_id"])) {
    $classId = $_GET["class_id"]; // Fetch members in the class
    $sql = "SELECT b.UserID, u.Username, u.Email
            FROM bookings b
            JOIN users u ON b.UserID = u.UserID
            WHERE b.ClassID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<div class='container'>
                <h1>Members of Class ID: <span class='class-id'>$classId</span></h1>
                <table class='members-table'>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>"; // Display each member
        while ($row = $result->fetch_assoc()) {
            $userId = $row["UserID"];
            echo "<tr>
                    <td>" .
                $userId .
                "</td>
                    <td>" .
                htmlspecialchars($row["Username"]) .
                "</td>
                    <td>" .
                htmlspecialchars($row["Email"]) .
                "</td>
                    <td>
                        <a class='action-link' href='assess.php?user_id=$userId&class_id=$classId'>Assess</a> |
                        <a class='action-link' href='kick_member.php?member=$userId&class_id=$classId'>Kick Out</a>
                    </td>
                  </tr>";
        }

        echo "</tbody>
            </table>
        </div>";
    } else {
        echo "<div class='container'><p>No members found for this class.</p></div>";
    }
} else {
    echo "<div class='container'><p>No class selected.</p></div>";
}
$conn->close();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7fa;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .class-id {
        color: #007bff;
        font-weight: bold;
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
        background-color: #f2f2f2;
        color: #333;
    }

    td {
        background-color: #ffffff;
    }

    .action-link {
        color: #007bff;
        text-decoration: none;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    p {
        text-align: center;
        font-size: 18px;
        color: #666;
    }
</style>
