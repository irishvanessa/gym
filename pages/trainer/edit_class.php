<?php include "../../validity/checker.php"; ?>
<?php include "../../component/topbar.php"; ?>
<?php include "../../component/trainersidebar.php"; ?>

<?php // Include database connection


include "../../activity/conn.php"; // Get the ID from the URL
if (isset($_GET["id"])) {
    $classID = $_GET["id"];
} else {
    // Redirect if ID is not provided
    header("Location: /pages/Trainer.php");
    exit();
} // Fetch the existing class details for the given ID
$query =
    "SELECT ClassName, TrainerID, ClassDateTime, Capacity FROM classes WHERE ClassID = ?";
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
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $className = $_POST["ClassName"];
    $trainerID = $_POST["TrainerID"];
    $classDateTime = $_POST["ClassDateTime"];
    $capacity = $_POST["Capacity"];
    // Convert datetime-local format to MySQL datetime format
    $classDateTime = date("Y-m-d H:i:s", strtotime($classDateTime)); // Prepare and execute the update query
    $stmt = $conn->prepare(
        "UPDATE classes SET ClassName = ?, TrainerID = ?, ClassDateTime = ?, Capacity = ? WHERE ClassID = ?"
    );
    $stmt->bind_param(
        "ssssd",
        $className,
        $trainerID,
        $classDateTime,
        $capacity,
        $classID
    );
    if ($stmt->execute()) {
        $successMessage = "Class updated successfully!";
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
    <title>Edit Class</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
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

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            text-align: center;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Edit Class</h3>

    <?php if (isset($successMessage)): ?>
        <div class="message success"><?= $successMessage ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="message error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="ClassName">Class Name</label>
            <input type="text" id="ClassName" name="ClassName" value="<?= htmlspecialchars(
                $classData["ClassName"]
            ) ?>" required>
        </div>

        <div class="form-group">
            <label for="TrainerID">Trainer</label>
            <select id="TrainerID" name="TrainerID" required>
                <?php
                $trainer_query =
                    "SELECT UserID, UserName FROM users WHERE Role = 'Trainer'";
                $trainer_result = $conn->query($trainer_query);
                if ($trainer_result->num_rows > 0) {
                    while ($row = $trainer_result->fetch_assoc()) {
                        $selected =
                            $row["UserID"] == $classData["TrainerID"]
                                ? "selected"
                                : "";
                        echo "<option value='" .
                            $row["UserID"] .
                            "' $selected>" .
                            htmlspecialchars($row["UserName"]) .
                            "</option>";
                    }
                } else {
                    echo "<option value=''>No trainers found</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="ClassDateTime">Class Date & Time</label>
            <input type="datetime-local" id="ClassDateTime" name="ClassDateTime" value="<?= date(
                "Y-m-d\TH:i",
                strtotime($classData["ClassDateTime"])
            ) ?>" required>
        </div>

        <div class="form-group">
            <label for="Capacity">Capacity</label>
            <input type="number" id="Capacity" name="Capacity" value="<?= htmlspecialchars(
                $classData["Capacity"]
            ) ?>" required>
        </div>

        <button type="submit">Update Class</button>
    </form>
</div>

</body>
</html>

<?php // Close the connection
$conn->close(); ?>
