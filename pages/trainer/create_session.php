<?php
include "../../component/topbar.php";
include "../../validity/checker.php";
// include "../../validity/user.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

$classId = isset($_GET["class_id"]) ? $_GET["class_id"] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sessionName = $_POST["session_name"];
    $datetimeStart = $_POST["datetime_start"];
    $datetimeEnd = $_POST["datetime_end"];

    // Insert new session into the database
    $sql =
        "INSERT INTO session (session_name, class_id, datetime_start, datetime_end) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "siss",
        $sessionName,
        $classId,
        $datetimeStart,
        $datetimeEnd
    );

    if ($stmt->execute()) {
        // Redirect back to manage_session.php after successful creation
        echo "<script>
                alert('Session created successfully!');
                window.location.href = './manage_session.php?class_id=$classId'; // Redirect to manage sessions
              </script>";
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to create session. Please try again.</div>";
    }
}

echo "<div class='container'>
        <h1>Create New Session for Class ID: $classId</h1>";
?>

<form method="POST">
    <div class="form-group">
        <label for="session_name">Session Name:</label><br>
        <input type="text" name="session_name" class="form-control" required><br>
    </div>

    <div class="form-group">
        <label for="datetime_start">Start Date and Time:</label><br>
        <input type="datetime-local" name="datetime_start" class="form-control" required><br>
    </div>

    <div class="form-group">
        <label for="datetime_end">End Date and Time:</label><br>
        <input type="datetime-local" name="datetime_end" class="form-control" required><br><br>
    </div>

    <button type="submit" class="btn btn-success">Create Session</button>
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
