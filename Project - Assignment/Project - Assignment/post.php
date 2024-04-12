<?php

/************************
Name: Dawn Avanthay
Date: March, 2024
************************/

require('connect.php');
require('authenticate.php');

session_start();

/*if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)
{
    header("Location: post.php");
    exit;
}

if($_SESSION['role'] !== 'admin') 
{
    header("Location: index.php");
    exit;
}*/

if($_POST && !empty($_POST['title']) && (!empty($_POST['content'])))
{
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO blog_posts(title, content)
              VALUES (:title, :content)";

    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->execute();

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>My Blog Post!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div class="title">
        <h1><a href="index.php">SEF Blog!</a></h1>
    </div>
    <div class = "postForm">
        <form id=postForm method="post" action="post.php">
            <label for=title>Title:</label>
            <input type="text" id=title name=title>
            <label for=content>What's on your mind?:</label>
            <input type="type" id=content name=content>
            <input type="submit" value="Confirm">
        </form>
    </div>
</body>
</html>
