<?php
include "../../component/topbar.php";
include "../../validity/checker.php";
include "../../validity/user.php";
include "../../component/adminsidebar.php";
include "../../activity/conn.php";

// Get the username from the query string
$username = isset($_GET["who"]) ? $_GET["who"] : "";

if ($username) {
    // Fetch the UserID based on the provided username
    $stmt = $conn->prepare("SELECT UserID FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($trainerID);
        $stmt->fetch();
    } else {
        die("Invalid trainer username.");
    }
    $stmt->close();
} else {
    die("Trainer username is required.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $className = $_POST["ClassName"];
    $classDateTime = $_POST["ClassDateTime"];
    $capacity = $_POST["Capacity"];

    // Convert datetime-local format to MySQL datetime format
    $classDateTime = date("Y-m-d H:i:s", strtotime($classDateTime));

    // Prepare and execute the insert query
    $stmt = $conn->prepare(
        "INSERT INTO classes (ClassName, TrainerID, ClassDateTime, Capacity) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "sssd",
        $className,
        $trainerID,
        $classDateTime,
        $capacity
    );

    if ($stmt->execute()) {
        $successMessage = "Class created successfully!";
        header("Location: /pages/Trainer.php");
        exit();
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Class</title>
</head>
<body>
    <h1>Create a New Class</h1>

    <?php if (isset($successMessage)): ?>
        <p style="color: green;"><?= $successMessage ?></p>
    <?php elseif (isset($errorMessage)): ?>
        <p style="color: red;"><?= $errorMessage ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="ClassName">Class Name</label>
            <input type="text" id="ClassName" name="ClassName" required>
        </div>
        <div>
            <label>Trainer</label>
            <p><?= htmlspecialchars($username) ?></p>
            <input type="hidden" id="TrainerID" name="TrainerID" value="<?= $trainerID ?>">
        </div>
        <div>
            <label for="ClassDateTime">Class Date & Time</label>
            <input type="datetime-local" id="ClassDateTime" name="ClassDateTime" required>
        </div>
        <div>
            <label for="Capacity">Capacity</label>
            <input type="number" id="Capacity" name="Capacity" required>
        </div>
        <div>
            <button type="submit">Create Class</button>
        </div>
    </form>
</body>
</html>
