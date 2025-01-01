<?php
include "../../component/topbar.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

// Validate and get the user_id and class_id
if (isset($_GET["user_id"]) && isset($_GET["class_id"])) {
    $userId = $_GET["user_id"];
    $classId = $_GET["class_id"];

    // Link to create a new assessment
    echo "<div class='container'>
            <a class='create-assessment-link' href='create_assessment.php?user_id=$userId&class_id=$classId'>Create Assessment</a>
          </div>";

    // Query to fetch progress records for the specified member
    $sql = "SELECT * FROM progress WHERE MemberID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='container'>
            <h1>Progress for Member ID: <span class='user-id'>$userId</span> (Class ID: <span class='class-id'>$classId</span>)</h1>
            <table class='progress-table'>
                <thead>
                    <tr>
                        <th>Progress ID</th>
                        <th>Weight</th>
                        <th>BMI</th>
                        <th>Performance Notes</th>
                        <th>Progress Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";

    // Display progress records
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $progressId = $row["ProgressID"];
            echo "<tr>
                    <td>{$row["ProgressID"]}</td>
                    <td>{$row["Weight"]}</td>
                    <td>{$row["BMI"]}</td>
                    <td>" .
                htmlspecialchars($row["PerformanceNotes"]) .
                "</td>
                    <td>{$row["ProgressDate"]}</td>
                    <td>
                        <a class='action-link' href='edit_assessment.php?progress_id=$progressId&user_id=$userId&class_id=$classId'>Edit Assessment</a> |
                        <form method='post' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to delete this assessment?');\">
                            <input type='hidden' name='delete_progress_id' value='$progressId'>
                            <button type='submit' class='delete-button'>Delete</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No progress records found for this member.</td></tr>";
    }
    echo "</tbody></table></div>";
} else {
    echo "<div class='container'><p>Invalid member or class selection.</p></div>";
}

// Handle the delete assessment functionality
if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["delete_progress_id"])
) {
    $progressIdToDelete = $_POST["delete_progress_id"];
    $deleteSql = "DELETE FROM progress WHERE ProgressID = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $progressIdToDelete);
    if ($stmt->execute()) {
        echo "<div class='container'><p>Progress record with ID $progressIdToDelete has been deleted.</p></div>";
        header("Refresh:0"); // Refresh the page to update the list
    } else {
        echo "<div class='container'><p>Failed to delete the progress record. Please try again.</p></div>";
    }
}

$conn->close();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .user-id, .class-id {
        color: #007bff;
        font-weight: bold;
    }

    .create-assessment-link {
        display: block;
        margin-bottom: 20px;
        color: #007bff;
        font-size: 18px;
        text-decoration: none;
        font-weight: bold;
    }

    .create-assessment-link:hover {
        text-decoration: underline;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        color: #333;
    }

    td {
        background-color: #ffffff;
    }

    .action-link {
        color: #007bff;
        text-decoration: none;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    .delete-button {
        background-color: #f44336;
        color: white;
        border: none;
        padding: 6px 12px;
        cursor: pointer;
    }

    .delete-button:hover {
        background-color: #d32f2f;
    }

    p {
        text-align: center;
        font-size: 18px;
        color: #666;
    }

    .progress-table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .progress-table th,
    .progress-table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .progress-table th {
        background-color: #f2f2f2;
        color: #333;
    }
</style>
