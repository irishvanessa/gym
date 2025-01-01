<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Box Selection Menu</title>
    <style>
        /* Wrapper for box menu */
        .box-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 20px;
            padding: 0;
            list-style-type: none;
        }

        /* Each box styling */
        .box-menu li {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .box-menu li:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .box-menu li a {
            display: block;
            padding: 20px;
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px; /* Ensure rounded corners match */
        }

        .box-menu li a:hover {
            color: #007BFF; /* Highlighted text color */
        }

        /* Add a responsive layout for smaller screens */
        @media (max-width: 600px) {
            .box-menu li {
                font-size: 14px;
            }

            .box-menu li a {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <ul class="box-menu">
        <li><a href="/pages/Member.php">Booking</a></li>
        <li><a href="/pages/member/progress.php">Progress</a></li>
        <li><a href="/pages/member/payment.php">Payment</a></li>
        <!-- <li><a href="/pages/member/notification.php">Notification</a></li> -->
        <li><a href="/pages/member/account.php">Account</a></li>
    </ul>
</body>
</html>
