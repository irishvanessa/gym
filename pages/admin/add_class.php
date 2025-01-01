<?php include "../../component/topbar.php"; ?>
<?php include "../../validity/checker.php"; ?>
<?php include "../../validity/user.php"; ?>
<?php include "../../component/adminsidebar.php"; ?>
<?php
include "../../activity/conn.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $className = $_POST["ClassName"];
    $trainerID = $_POST["TrainerID"];
    $classDateTime = $_POST["ClassDateTime"];
    $capacity = $_POST["Capacity"];
    $classDateTime = date("Y-m-d H:i:s", strtotime($classDateTime));
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
        header("Location: /pages/admin/class.php");
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
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
        <h1>Create a New Class</h1>

        <?php if (isset($successMessage)): ?>
            <p class="message success"><?= $successMessage ?></p>
        <?php elseif (isset($errorMessage)): ?>
            <p class="message error"><?= $errorMessage ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div>
                <label for="ClassName">Class Name</label>
                <input type="text" id="ClassName" name="ClassName" required>
            </div>
            <div>
                <label for="TrainerID">Trainer</label>
                <select id="TrainerID" name="TrainerID" required>
                    <?php
                    $trainer_query =
                        "SELECT UserID, UserName FROM users WHERE Role = 'Trainer'";
                    $trainer_result = $conn->query($trainer_query);
                    if ($trainer_result->num_rows > 0) {
                        while ($row = $trainer_result->fetch_assoc()) {
                            echo "<option value='" .
                                htmlspecialchars($row["UserID"]) .
                                "'>" .
                                htmlspecialchars($row["UserName"]) .
                                "</option>";
                        }
                    } else {
                        echo "<option value=''>No trainers found</option>";
                    }
                    ?>
                </select>
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
    </div>
</body>
</html>
