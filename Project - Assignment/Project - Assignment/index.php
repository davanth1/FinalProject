<?php

/**********************
Name: Dawn Avanthay
Date: March, 2024
**********************/

require('connect.php');

$query = "SELECT title, post_id, date
          FROM blog_posts
          ORDER BY date DESC";

$statement = $db->prepare($query);
$statement->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Welcome to SEF Blog!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div class="header">
        <h1><a href= "index.php">SEF Blog</a></h1>
            <a href="post.php">New Post</a>
            <a href="login.php">Log In</a>
            <a href="register.php">Register</a>
    </div>
    <div class="post">
    <h2>What's new?</h2>
    <?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
        <h4><a href="display.php?post_id=<?= $row['post_id'] ?>"><?= $row['title'] ?></a></h4>
        <h5><?= date("F d, Y, h:i A", strtotime($row['date']))?></h5>
    <?php endwhile ?>
    </div>    
</body>
</html>