<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up</title>
    </head>
    <body>
        <?php include "../component/topbar.php"; ?>

        <form action="../activity/register.php" method="POST">
            <p>Username</p>
            <input type="text" name="username" required>
            <p>Password</p>
            <input type="password" name="password" required>
            <p>Role</p>
            <select name="role" required>
                <option value="trainer">Trainer</option>
                <option value="member">Member</option>
            </select>
            <p>First Name</p>
            <input type="text" name="firstName" required>
            <p>Middle Name</p>
            <input type="text" name="middleName">
            <p>Last Name</p>
            <input type="text" name="lastName" required>
            <p>Email</p>
            <input type="email" name="email" required>
            <p>Phone</p>
            <input type="tel" name="phone" required>
            <button type="submit">Register Now</button>
            <a href="../pages/account.php">Back</a>
        </form>
    </body>
</html>
