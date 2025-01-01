<?php
include "conn.php"; // Adjust the path based on your directory structure

// Styling for the UI
echo <<<CSS
<style>
    .delete-confirmation {
        font-family: Arial, sans-serif;
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .delete-confirmation p {
        font-size: 16px;
        color: #333;
        margin-bottom: 20px;
    }
    .delete-confirmation a {
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
        margin: 5px;
        display: inline-block;
    }
    .delete-confirmation .confirm {
        background-color: #dc3545;
        border: none;
    }
    .delete-confirmation .cancel {
        background-color: #007bff;
        border: none;
    }
    .delete-confirmation .confirm:hover {
        background-color: #c82333;
    }
    .delete-confirmation .cancel:hover {
        background-color: #0056b3;
    }
    .success-message, .error-message {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        text-align: center;
        border-radius: 8px;
        font-family: Arial, sans-serif;
    }
    .success-message {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
CSS;

// Handle the delete confirmation prompt
if (isset($_GET["delete_user"])) {
    $user_to_delete = $_GET["delete_user"];

    // Display a confirmation message
    echo <<<HTML
<div class="delete-confirmation">
    <p>Are you sure you want to delete the user with ID: <strong>$user_to_delete</strong>?</p>
    <a class="confirm" href="?delete_user=$user_to_delete&confirm_delete=yes">Yes, delete</a>
    <a class="cancel" href="/pages/dashboard.php">No, cancel</a>
</div>
HTML;
}

// Handle the deletion after confirmation using GET parameter
if (
    isset($_GET["confirm_delete"]) &&
    $_GET["confirm_delete"] === "yes" &&
    isset($_GET["delete_user"])
) {
    $user_to_delete = $_GET["delete_user"];

    // Ensure the database connection is valid
    if ($conn && !$conn->connect_error) {
        // Check if the user exists before deleting
        $check_user = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
        $check_user->bind_param("i", $user_to_delete);
        $check_user->execute();
        $check_user_result = $check_user->get_result();

        if ($check_user_result->num_rows > 0) {
            // If user exists, proceed with deletion
            $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
            $stmt->bind_param("i", $user_to_delete);

            if ($stmt->execute()) {
                // Success: Display a success message
                echo <<<HTML
<div class="success-message">
    <p>User deleted successfully.</p>
    <a href="/pages/dashboard.php">Go back to Dashboard</a>
</div>
HTML;
            } else {
                // Error deleting the user
                echo <<<HTML
<div class="error-message">
    <p>Error deleting user: {$stmt->error}</p>
    <a href="/pages/dashboard.php">Go back to Dashboard</a>
</div>
HTML;
            }
            $stmt->close();
        } else {
            // Error: User not found
            echo <<<HTML
<div class="error-message">
    <p>Error: User not found in the database.</p>
    <a href="/pages/dashboard.php">Go back to Dashboard</a>
</div>
HTML;
        }
        $check_user->close();
    } else {
        // Connection error
        echo <<<HTML
<div class="error-message">
    <p>Connection failed: {$conn->connect_error}</p>
    <a href="/pages/dashboard.php">Go back to Dashboard</a>
</div>
HTML;
    }
}
?>
