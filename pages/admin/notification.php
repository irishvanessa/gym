<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Account</title>
    </head>
    <body>
        <?php include "../../component/topbar.php"; ?>
        <?php include "../../validity/checker.php"; ?>
        <?php include "../../validity/user.php"; ?>
        <?php include "../../component/adminsidebar.php"; ?>

        <?php include "../../activity/conn.php"; ?>

        <?php
        $username = $_GET["who"]; // Assuming 'who' is passed as a GET parameter

        $sql = "SELECT * FROM users WHERE Username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userId = $row["UserID"];

            $sql = "SELECT * FROM notifications WHERE UserID = '$userId'";
            $notifications = $conn->query($sql);

            if ($notifications->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>NotificationID</th><th>UserID</th><th>Message</th><th>SentDate</th></tr>";
                while ($notification = $notifications->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $notification["NotificationID"] . "</td>";
                    echo "<td>" . $notification["UserID"] . "</td>";
                    echo "<td>" . $notification["Message"] . "</td>";
                    echo "<td>" . $notification["SentDate"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No notifications found for this user.";
            }
        } else {
            echo "User not found.";
        }
        $conn->close();
        ?>
    </body>
</html>
