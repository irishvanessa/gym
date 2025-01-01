<?php
include "../../component/topbar.php";
include "../../validity/checker.php";
include "../../component/trainersidebar.php";
include "../../activity/conn.php";

$sessionId = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$classId = isset($_GET["class_id"]) ? intval($_GET["class_id"]) : 6; // Default to 6 if class_id is not provided

// Check if session ID and class ID are valid
if (!$sessionId || !$classId) {
    echo "<p>Invalid session or class ID.</p>";
    echo "<a href='./manage_session.php?class_id=$classId'>Back to Manage Sessions</a>";
    exit();
}

// Handle the confirmation response
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["confirm"]) && $_POST["confirm"] === "yes") {
        // Delete the session from the database
        $sql = "DELETE FROM session WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $sessionId);

        if ($stmt->execute()) {
            // Redirect to manage session with class_id after deletion
            header(
                "Location: ./manage_session.php?class_id=$classId&message=Session+deleted+successfully"
            );
            exit();
        } else {
            echo "<p>Failed to delete session. Please try again.</p>";
        }
    } else {
        // Redirect back to manage_session.php if the user cancels
        header("Location: ./manage_session.php?class_id=$classId");
        exit();
    }
}
?>

<h1>Confirm Delete Session</h1>
<p>Are you sure you want to delete this session?</p>

<form method="POST">
    <button type="submit" name="confirm" value="yes">Yes</button>
    <button type="submit" name="confirm" value="no">No</button>
</form>

<a href="./manage_session.php?class_id=<?php echo $classId; ?>">Cancel</a>

<?php $conn->close(); ?>
