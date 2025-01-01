<?php
include "../../activity/conn.php";

// Check if member ID and class ID are provided
if (
    isset($_GET["member"]) &&
    !empty($_GET["member"]) &&
    isset($_GET["class_id"]) &&
    !empty($_GET["class_id"])
) {
    $userId = $_GET["member"];
    $classId = $_GET["class_id"];

    // Display confirmation form
    echo "<div class='confirmation-container'>";
    echo "<h1>Are you sure you want to remove User ID: <span class='highlight'>$userId</span> from Class ID: <span class='highlight'>$classId</span>?</h1>";
    echo "<form method='POST'>
            <button type='submit' name='confirm' value='yes' class='confirm-button'>Yes</button>
            <a href='view_members.php?class_id=$classId' class='cancel-link'>
                <button type='button' class='cancel-button'>No</button>
            </a>
          </form>";
    echo "</div>";

    // Handle the confirmation action
    if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        isset($_POST["confirm"]) &&
        $_POST["confirm"] == "yes"
    ) {
        // Query to delete user from the class
        $deleteSql = "DELETE FROM bookings WHERE UserID = ? AND ClassID = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("ii", $userId, $classId);

        if ($stmt->execute()) {
            // Redirect to the member view page after success
            header("Location: view_members.php?class_id=$classId");
            exit();
        } else {
            echo "<p class='error-message'>Failed to remove the member. Please try again.</p>";
        }
    }
} else {
    echo "<p class='error-message'>No member or class selected for removal.</p>";
}

$conn->close();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .confirmation-container {
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

    .confirm-button,
    .cancel-button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        margin: 10px;
    }

    .confirm-button {
        background-color: #28a745;
        color: white;
    }

    .confirm-button:hover {
        background-color: #218838;
    }

    .cancel-button {
        background-color: #dc3545;
        color: white;
    }

    .cancel-button:hover {
        background-color: #c82333;
    }

    .cancel-link {
        text-decoration: none;
    }

    .error-message {
        color: red;
        font-weight: bold;
        text-align: center;
        margin-top: 20px;
    }
</style>
