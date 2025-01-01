<?php
include "../../component/topbar.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

// Validate and get the progress_id, user_id, and class_id
if (
    isset($_GET["progress_id"]) &&
    isset($_GET["user_id"]) &&
    isset($_GET["class_id"])
) {
    $progressId = $_GET["progress_id"];
    $userId = $_GET["user_id"];
    $classId = $_GET["class_id"];

    // Fetch the existing assessment details
    $sql = "SELECT * FROM progress WHERE ProgressID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $progressId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $weight = $row["Weight"];
        $bmi = $row["BMI"];
        $notes = $row["PerformanceNotes"];
        $progressDate = $row["ProgressDate"];
    } else {
        echo "<p class='error-message'>Invalid progress record.</p>";
        exit();
    }

    // If the form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $updatedWeight = $_POST["weight"];
        $updatedBmi = $_POST["bmi"];
        $updatedNotes = $_POST["notes"];
        $updatedDate = $_POST["progress_date"];

        // Update the progress record in the database
        $updateSql =
            "UPDATE progress SET Weight = ?, BMI = ?, PerformanceNotes = ?, ProgressDate = ? WHERE ProgressID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param(
            "ddssi",
            $updatedWeight,
            $updatedBmi,
            $updatedNotes,
            $updatedDate,
            $progressId
        );

        if ($updateStmt->execute()) {
            // Redirect to assess.php on success
            header("Location: assess.php?user_id=$userId&class_id=$classId");
            exit();
        } else {
            echo "<p class='error-message'>Failed to update the progress. Please try again.</p>";
        }
    }
} else {
    echo "<p class='error-message'>Invalid request parameters.</p>";
    exit();
}
?>

<div class="container">
    <h1>Edit Assessment for Member ID: <span class="highlight"><?php echo $userId; ?></span> (Class ID: <span class="highlight"><?php echo $classId; ?></span>)</h1>

    <form method="POST">
        <label for="weight">Weight:</label>
        <input type="number" step="0.1" name="weight" id="weight" value="<?php echo $weight; ?>" required class="input-field">
        <br><br>

        <label for="bmi">BMI:</label>
        <input type="number" step="0.1" name="bmi" id="bmi" value="<?php echo $bmi; ?>" required class="input-field">
        <br><br>

        <label for="notes">Performance Notes:</label>
        <textarea name="notes" id="notes" rows="4" cols="50" required class="input-field"><?php echo $notes; ?></textarea>
        <br><br>

        <label for="progress_date">Progress Date:</label>
        <input type="date" name="progress_date" id="progress_date" value="<?php echo $progressDate; ?>" required class="input-field">
        <br><br>

        <button type="submit" class="submit-button">Update Assessment</button>
        <a href="assess.php?user_id=<?php echo $userId; ?>&class_id=<?php echo $classId; ?>" class="cancel-link">Cancel</a>
    </form>
</div>

<?php $conn->close(); ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .highlight {
        color: #007bff;
        font-weight: bold;
    }

    .input-field {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        border-radius: 4px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }

    .input-field:focus {
        outline: none;
        border-color: #007bff;
    }

    label {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .submit-button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .submit-button:hover {
        background-color: #218838;
    }

    .cancel-link {
        text-decoration: none;
        color: #dc3545;
        font-size: 16px;
    }

    .cancel-link:hover {
        text-decoration: underline;
    }

    .error-message {
        color: red;
        font-weight: bold;
        text-align: center;
    }
</style>
