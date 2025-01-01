<?php
// Include database connection
include "../../activity/conn.php";

// Get the class ID from the URL
if (isset($_GET["id"])) {
    $classID = $_GET["id"];
} else {
    // Redirect if no ID is provided
    header("Location: /pages/Trainer.php");
    exit();
}

// Fetch the class details for the given ID
$query = "SELECT ClassName FROM classes WHERE ClassID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $classID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Redirect if no class is found for this ID
    header("Location: /pages/Trainer.php");
    exit();
}

$classData = $result->fetch_assoc();
$stmt->close();

// Handle deletion if the user confirms
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["confirm"])) {
    if ($_POST["confirm"] === "yes") {
        // Prepare and execute the delete query
        $stmt = $conn->prepare("DELETE FROM classes WHERE ClassID = ?");
        $stmt->bind_param("i", $classID);

        if ($stmt->execute()) {
            $successMessage = "Class deleted successfully!";
            header("Location: /pages/Trainer.php");
            exit();
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Redirect if user chose "No"
        header("Location: /class.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Class</title>
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

        h3 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 20px;
            padding: 15px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .form-group {
            text-align: center;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #c82333;
        }

        a {
            text-decoration: none;
            font-size: 16px;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Delete Class</h3>

    <?php if (isset($successMessage)): ?>
        <div class="message success"><?= $successMessage ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="message error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <p style="text-align: center;">
            Are you sure you want to delete the class: <strong><?= htmlspecialchars(
                $classData["ClassName"]
            ) ?></strong>?
        </p>
        <div class="form-group">
            <button type="submit" name="confirm" value="yes">Yes, Delete</button>
        </div>
        <div class="form-group">
            <a href="/pages/Trainer.php">No, Cancel</a>
        </div>
    </form>
</div>

</body>
</html>

<?php // Close the connection
$conn->close();
?>
