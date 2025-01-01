<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Menu</title>
    <style>
        /* Navigation Menu Styles */
        .nav-menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            background-color: #333; /* Dark background for the menu */
            overflow: hidden; /* Clear floats */
        }

        .nav-menu li {
            float: left; /* Arrange menu items horizontally */
        }

        .nav-menu li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-menu li a:hover {
            background-color: #575757; /* Darker shade for hover */
            color: #ffffff;
        }

        .nav-menu li a:active {
            background-color: #444; /* Even darker shade for active state */
        }

        /* For smaller screens */
        @media (max-width: 600px) {
            .nav-menu li {
                float: none; /* Stack menu items vertically */
            }

            .nav-menu li a {
                text-align: left;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <ul class="nav-menu">
        <li><a href="/">Home</a></li>
        <li><a href="/pages/about.php">About</a></li>
        <li><a href="/pages/account.php">Account</a></li>
    </ul>
</body>
</html>
