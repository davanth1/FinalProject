<?php

/*****************************
Name: Dawn Avanthay
Date: March, 2024
*****************************/

require('connect.php');
require('authenticate.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['post_id']))
{
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "UPDATE blog_posts SET title = :title, content = :content
              WHERE post_id = :post_id
              LIMIT 1";

    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);

    $statement->execute();

    header("Location: index.php");
    exit;
}
else if(isset($_GET['post_id']))
{
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT post_id, title, content
              FROM blog_posts
              WHERE post_id = :post_id";

    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
}
else{
    $post_id = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit this Post!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div class="header">
        <h1><a href="index.php">SEF Blog</a></h1>
    </div>
    <?php if($post_id): ?>
    <?php while ($row = $statement->fetch()): ?>
            <form id=postForm method="post">
                <input type="hidden" name="post_id" value="<?=$row['post_id']?>"> 
                <label for=title>Title:</label>
                <input type="text" id=title name=title value="<?= $row['title'] ?>">
                <label for=content>Edit Content:</label>
                <input type="type" id=content name=content value="<?= $row['content']?>">
                <input type="submit" value="Confirm Changes">
            </form>
    <?php endwhile ?>
    <?php else: ?>
        <div class="error">
            <p>Title and Content must be at least 1 character long.</p>
        </div>
    <?php endif ?>
</body>
</html>