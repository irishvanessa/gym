<?php
include "../../component/topbar.php";
include "../../validity/checker.php";
// include "../../validity/user.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

// Get session ID and class ID from URL
$sessionId = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$classId = isset($_GET["class_id"]) ? intval($_GET["class_id"]) : 6; // Default to 6 if class_id is not provided

// Check if session ID is missing
if (empty($sessionId)) {
    echo "<div class='alert alert-danger'>Missing or invalid session ID.</div>";
    echo "<a href='./manage_session.php?class_id=$classId' class='btn btn-primary'>Back to Manage Sessions</a>"; // Use class_id in the back link
    exit(); // Stop further execution if session ID is missing
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sessionName = $_POST["session_name"];
    $datetimeStart = $_POST["datetime_start"];
    $datetimeEnd = $_POST["datetime_end"];

    // Update the session in the database
    $sql =
        "UPDATE session SET session_name = ?, datetime_start = ?, datetime_end = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssi",
        $sessionName,
        $datetimeStart,
        $datetimeEnd,
        $sessionId
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Session updated successfully!');
                window.location.href = './manage_session.php?class_id=$classId'; // Redirect to manage_session.php with the class_id
              </script>";
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update session. Please try again.</div>";
    }
} else {
    // Fetch the session details for editing
    $sql = "SELECT * FROM session WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $sessionName = $row["session_name"];
        $datetimeStart = $row["datetime_start"];
        $datetimeEnd = $row["datetime_end"];
    } else {
        echo "<div class='alert alert-danger'>Session not found.</div>";
        echo "<a href='./manage_session.php?class_id=$classId' class='btn btn-primary'>Back to Manage Sessions</a>"; // Back to manage session with class_id
        $conn->close();
        exit();
    }
}

echo "<div class='container'>
        <h1>Edit Session</h1>";
?>

<form method="POST">
    <div class="form-group">
        <label for="session_name">Session Name:</label><br>
        <input type="text" name="session_name" value="<?php echo htmlspecialchars(
            $sessionName
        ); ?>" class="form-control" required><br>
    </div>

    <div class="form-group">
        <label for="datetime_start">Start Date and Time:</label><br>
        <input type="datetime-local" name="datetime_start" value="<?php echo htmlspecialchars(
            $datetimeStart
        ); ?>" class="form-control" required><br>
    </div>

    <div class="form-group">
        <label for="datetime_end">End Date and Time:</label><br>
        <input type="datetime-local" name="datetime_end" value="<?php echo htmlspecialchars(
            $datetimeEnd
        ); ?>" class="form-control" required><br><br>
    </div>

    <button type="submit" class="btn btn-success">Update Session</button>
</form>

<a href="./manage_session.php?class_id=<?php echo $classId; ?>" class="btn btn-secondary">Cancel</a>

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
        padding: 30px;
        background-color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h1 {
        color: #333;
        font-size: 26px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-size: 16px;
        font-weight: bold;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border-radius: 4px;
        border: 1px solid #ddd;
        margin-top: 8px;
    }

    .btn {
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        margin: 10px 0;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .alert {
        padding: 10px;
        background-color: #f8d7da;
        color: #721c24;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>
