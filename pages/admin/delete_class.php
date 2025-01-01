<?php
// Include database connection
include "../../activity/conn.php";

// Get the class ID from the URL
if (isset($_GET["id"])) {
    $classID = $_GET["id"];
} else {
    // Redirect if no ID is provided
    header("Location: /pages/admin/class.php");
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
    header("Location: /pages/admin/class.php");
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
            header("Location: /pages/admin/class.php");
            exit();
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Redirect if user chose "No"
        header("Location: /pages/admin/class.php");
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
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 100px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h3 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #555;
            margin: 20px 0;
        }
        button, a {
            display: inline-block;
            margin: 10px 10px 0 0;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        button {
            background-color: #d9534f;
            color: white;
        }
        button:hover {
            background-color: #c9302c;
        }
        a {
            background-color: #6c757d;
            color: white;
        }
        a:hover {
            background-color: #5a6268;
        }
        .message {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
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
            <p>Are you sure you want to delete the class: <strong><?= htmlspecialchars(
                $classData["ClassName"]
            ) ?></strong>?</p>
            <button type="submit" name="confirm" value="yes">Yes, Delete</button>
            <a href="/pages/admin/class.php">No, Cancel</a>
        </form>
    </div>
</body>
</html>
