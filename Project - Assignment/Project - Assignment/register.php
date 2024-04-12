<?php
/******************************
Name: Dawn Avanthay
Date: March, 2024
******************************/
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if(isset($_POST['username'], $_POST['password'], $_POST['role']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role     = $_POST['role'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO Users (username, password, role)
                  VALUES (:username, :password, :role)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashedPassword);
        $statement->bindValue(':role', $role);
        $result = $statement->execute();

        if ($result)
        {
            $registration_success = "Registration successful. You can now log in.";
        } else {
            $registration_error = "Error occurred during registration.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Register</title>
</head>
<body>
<div class="title">
    <h1><a href="index.php">SEF Blog!</a></h1>
</div>
    <h2>Register</h2>
    <?php if (isset($registration_error)) echo "<p>$registration_error</p>"; ?>
    <?php if (isset($registration_success)) echo "<p>$registration_success</p>"; ?>
    <form action="" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="role">Role:</label><br>
        <select id="role" name="role">
            <option value="Admin">Admin</option>
            <option value="Non-Admin">Non-Admin</option>
        </select><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
