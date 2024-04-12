<?php
/******************************
Name: Dawn Avanthay
Date: March, 2024
******************************/

require('connect.php');

if(isset($_GET['post_id']))
{
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
/*"SELECT post_id, title, content, date, status*/
    $query = "SELECT post_id, title, content, date
              FROM blog_posts
              WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
}
else {
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
    <?php if($post_id && $row = $statement->fetch()): ?>
    <form method="get" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this post?');">
        <input type="hidden" name="post_id" value="<?=$row['post_id']?>">
        <h2><?=$row['title']?></h2>
        <p><?=$row['content']?></p>
        <p><?=date("F d, Y, h:i A", strtotime($row['date']))?></p>
        <div class="button-container">
            <button type="submit">Delete</button>
        </div>
    </form>
    <form method="get" action="edit.php">
        <input type="hidden" name="post_id" value="<?=$row['post_id']?>">
        <div class="button-container">
            <button type="submit">Edit</button>
        </div>
    </form>
    <form method="get" action="categories.php">
        <input type="hidden" name="post_id" value="<?=$row['post_id']?>">
        <div class="button-container">
            <button type="submit">Assign Category</button>
        </div>
    </form>
    <?php endif ?>
</body>
</html>