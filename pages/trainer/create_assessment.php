<?php
include "../../component/topbar.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

// Get user_id and class_id from the URL
if (isset($_GET["user_id"]) && isset($_GET["class_id"])) {
    $userId = $_GET["user_id"];
    $classId = $_GET["class_id"];

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $weight = $_POST["weight"];
        $bmi = $_POST["bmi"];
        $performanceNotes = $_POST["performanceNotes"];
        $progressDate = $_POST["progressDate"];

        // Prepare the SQL statement to insert the new assessment
        $sql =
            "INSERT INTO progress (MemberID, Weight, BMI, PerformanceNotes, ProgressDate) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisss",
            $userId,
            $weight,
            $bmi,
            $performanceNotes,
            $progressDate
        );

        if ($stmt->execute()) {
            echo "<p class='success-message'>New assessment successfully created!</p>";
            header("Location: assess.php?user_id=$userId&class_id=$classId"); // Redirect back to the progress page
            exit();
        } else {
            echo "<p class='error-message'>Failed to create the assessment. Please try again.</p>";
        }
    }

    // Display the form to create a new assessment
    echo "<div class='container'>
            <h1>Create New Assessment for Member ID: <span class='highlight'>$userId</span> (Class ID: <span class='highlight'>$classId</span>)</h1>
            <form method='post'>
                <label for='weight'>Weight:</label><br>
                <input type='text' id='weight' name='weight' required class='input-field'><br>
                <label for='bmi'>BMI:</label><br>
                <input type='text' id='bmi' name='bmi' required class='input-field'><br>
                <label for='performanceNotes'>Performance Notes:</label><br>
                <textarea id='performanceNotes' name='performanceNotes' required class='input-field'></textarea><br>
                <label for='progressDate'>Progress Date:</label><br>
                <input type='date' id='progressDate' name='progressDate' required class='input-field'><br><br>
                <input type='submit' value='Create Assessment' class='submit-button'>
            </form>
          </div>";
} else {
    echo "<div class='container'><p class='error-message'>Invalid member or class selection.</p></div>";
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

    textarea {
        height: 100px;
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

    .success-message {
        color: green;
        font-weight: bold;
        text-align: center;
    }

    .error-message {
        color: red;
        font-weight: bold;
        text-align: center;
    }
</style>
