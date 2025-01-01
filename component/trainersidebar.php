<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Menu</title>
    <style>
        /* Styling for box menu */
        .box-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 20px;
            padding: 0;
            list-style: none;
        }

        /* Individual box styling */
        .box-menu li {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box-menu li:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Links inside the box */
        .box-menu li a {
            display: block;
            padding: 20px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            border-radius: 10px;
        }

        .box-menu li a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <ul class="box-menu">
        <li><a href="/pages/Trainer.php">Classes</a></li>
        <!-- <li><a href="/pages/trainer/payment.php">Payment</a></li> -->
        <!-- <li><a href="/pages/trainer/notification.php">Notification</a></li> -->
        <li><a href="/pages/trainer/account.php">Account</a></li>
    </ul>
</body>
</html>
