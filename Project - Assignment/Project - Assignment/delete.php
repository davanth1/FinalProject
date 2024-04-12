<?php
/****************************
Name: Dawn Avanthay
Date: March, 2024
****************************/

require('connect.php');
require('authenticate.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) 
{
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    if(isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === 'yes')
    {
        $query = "DELETE FROM blog_posts 
                  WHERE post_id = :post_id
                  LIMIT 1";

        $statement = $db->prepare($query);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: index.php");
        exit;
    } 
 else {
    header("Location: index.php");
    exit;
    } 
} elseif (isset($_GET['post_id']))
{
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT *
              FROM blog_posts
              WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Delete this Post!</title>
</head>
<body>
    <h1>Delete Post</h1>
    <?php if (isset($post)) : ?>
        <p>Are you sure you want to delete the following post?</p>
        <p><strong>Title:</strong> <?php echo $post['title']; ?></p>
        <p><strong>Content:</strong> <?php echo $post['content']; ?></p>
        <form method="post">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <label for="confirm_delete">Confirm Deletion (type 'yes' to confirm):</label>
            <input type="text" name="confirm_delete" id="confirm_delete">
            <button type="submit">Delete</button>
        </form>
    <?php else : ?>
        <p>No post found for deletion.</p>
    <?php endif; ?>
</body>
</html>
