<?php
/*********************************
Name: Dawn Avanthay
Date: March, 2024
*********************************/

require('connect.php');
session_start();

function verifyPassword($password, $hashedPassword)
{
    return password_verify($password, $hashedPassword);
}

function redirectToIndex()
{
    header("Location: index.php");
    exit;
}

function redirectToAdminPage()
{
    header("Location: admin.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT user_id, username, password, role
              FROM users
              WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if($user)
    { var_dump ($password, $user['password'], password_hash($password, PASSWORD_DEFAULT));
        if(verifyPassword($password, $user['password']))
        {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if($_SESSION['role'] === 'Admin')
            {
                redirectToAdminPage();
            } else {
                redirectToIndex();
            }
        } else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Invalid username or password. Try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Login Page</title>
</head>
<body>
<div class="title">
    <h1><a href="index.php">SEF Blog!</a></h1>
</div>
    <h2>Login</h2>
    <?php if (isset($login_error)) echo "<p>$login_error</p>"; ?>
    <form action="" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>