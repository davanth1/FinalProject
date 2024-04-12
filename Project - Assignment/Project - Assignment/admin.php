<?php
/*********************************
Name: Dawn Avanthay
Date: March, 2024
*********************************/

require('connect.php');
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

// Function to log out user
function logout()
{
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Log out if logout button is clicked
if (isset($_POST['logout'])) {
    logout();
}

function getAllUsers()
{
    global $db;
    $query = "SELECT * FROM users";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function addUser($username, $password, $role)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if(isset($_POST['username'], $_POST['password'], $_POST['role']))
        {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role     = $_POST['role'];

    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password, role) 
                  VALUES (:username, :password, :role)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashedPassword);
        $statement->bindValue(':role', $role);
        return $statement->execute();
        }
    }
}

function updateUser($user_id, $username, $password, $role)
{
    global $db;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET username = :username, password = :password, role = :role WHERE user_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $hashedPassword);
    $statement->bindValue(':role', $role);
    return $statement->execute();
}

function deleteUser($user_id)
{
    global $db;
    $query = "DELETE FROM users WHERE user_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    return $statement->execute();
}

// Blog Management

function getAllCategories()
{
    global $db;
    $query = "SELECT * FROM categories";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Comment Management

function getAllCommentsForPost($post_id)
{
    global $db;
    $query = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function addComment($post_id, $user_id, $content)
{
    global $db;
    $query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (:post_id, :user_id, :comment_text)";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':comment_text', $comment_text);
    return $statement->execute();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
<div class="title">
    <h1><a href="index.php">SEF Blog!</a></h1>
</div>
<div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?> (Admin)</h2>
        <form action="" method="post">
            <input type="submit" name="logout" value="Logout">
        </form>
        <div class="user-management">
            <h3>User Management</h3>
            <p>List of registered users:</p>
            <ul>
                <?php $users = getAllUsers();
                foreach ($users as $user) : ?>
                    <li><?php echo $user['username']; ?>
                        <form action="" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="submit" name="delete_user" value="Delete">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <h4>Add User</h4>
            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" required><br>
                <label for="password">Password:</label>
                <input type="password" name="password" required><br>
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="non-admin">Non-admin</option>
                </select><br>
                <input type="submit" name="add_user" value="Add User">
            </form>
        </div>
        <div class="blog-management">
            <h3>Blog Management</h3>
            <p>List of categories:</p>
            <ul>
                <?php $categories = getAllCategories();
                foreach ($categories as $category) : ?>
                    <li><?php echo $category['category_name']; ?></li>
                <?php endforeach; ?>
            </ul>
            <!-- blog management goes here -->
        </div>
        <div class="comment-management">
            <!-- comment management goes here -->
        </div>
    </div>
</body>

</html>
