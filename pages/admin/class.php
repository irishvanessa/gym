<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Classes</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f9;
            }
            h1 {
                text-align: center;
                margin-top: 20px;
                color: #333;
            }
            .content {
                max-width: 1200px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            a.btn-custom, a.btn-danger-custom {
                text-decoration: none;
                padding: 8px 12px;
                border-radius: 5px;
                font-size: 14px;
                color: #fff;
            }
            a.btn-custom {
                background-color: #007bff;
            }
            a.btn-custom:hover {
                background-color: #0056b3;
            }
            a.btn-danger-custom {
                background-color: #dc3545;
            }
            a.btn-danger-custom:hover {
                background-color: #c82333;
            }
            .add-class-btn {
                display: inline-block;
                margin-bottom: 20px;
                padding: 10px 20px;
                background-color: #28a745;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                font-size: 16px;
            }
            .add-class-btn:hover {
                background-color: #218838;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            table th, table td {
                text-align: left;
                padding: 10px;
                border: 1px solid #ddd;
            }
            table th {
                background-color: #f8f9fa;
                color: #333;
            }
            table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            table tr:hover {
                background-color: #f1f1f1;
            }
            .no-results {
                text-align: center;
                font-size: 18px;
                color: #666;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <?php include "../../component/topbar.php"; ?>
        <?php include "../../validity/checker.php"; ?>
        <?php include "../../validity/user.php"; ?>
        <?php include "../../component/adminsidebar.php"; ?>

        <div class="content">
            <h1>Manage Classes</h1>
            <a href="add_class.php" class="add-class-btn">Add Class</a>
            <?php
            include "../../activity/conn.php";

            $sql = "SELECT c.*, u.Username AS TrainerName
                    FROM classes c
                    LEFT JOIN users u ON c.TrainerID = u.UserID";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th>Trainer Name</th>
                            <th>Class Date/Time</th>
                            <th>Capacity</th>
                            <th>Action</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" .
                        htmlspecialchars($row["ClassID"]) .
                        "</td>
                            <td>" .
                        htmlspecialchars($row["ClassName"]) .
                        "</td>
                            <td>" .
                        htmlspecialchars($row["TrainerName"]) .
                        "</td>
                            <td>" .
                        htmlspecialchars($row["ClassDateTime"]) .
                        "</td>
                            <td>" .
                        htmlspecialchars($row["Capacity"]) .
                        "</td>
                            <td>
                                <a href='./edit_class.php?id=" .
                        htmlspecialchars($row["ClassID"]) .
                        "' class='btn btn-custom'>Edit</a>
                                <a href='./delete_class.php?id=" .
                        htmlspecialchars($row["ClassID"]) .
                        "' class='btn btn-danger-custom'>Delete</a>
                            </td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='no-results'>No classes available.</p>";
            }
            $conn->close();
            ?>
        </div>
    </body>
</html>
