<?php
/*****************************
Name: Dawn Avanthay
Date: March, 2024
*****************************/

require('connect.php');

$query = "SELECT COUNT(*) AS count
          FROM categories";
$statement = $db->prepare($query);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);

$query = "SELECT category_id, category_name 
          FROM categories";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['post_id'])) {
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
} else {
    $post_id = false;
}

if($post_id) {
    $query = "SELECT post_id, title, content, date
              FROM blog_posts
              WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);
}

if(isset($_POST['category']) && $post_id) 
{
    $category_name = $_POST['category'];

    $query = "SELECT category_id
              FROM categories
              WHERE category_name = :category_name";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_name', $category_name, PDO::PARAM_STR);
    $statement->execute();
    $category_result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($category_result) {
        $category_id = $category_result['category_id'];

        $query = "INSERT INTO blog_posts_categories (post_id, category_id)
                  VALUES (:post_id, :category_id)";
        $statement = $db->prepare($query);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $statement->execute();
    }
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Assign Category</title>
</head>
<body>
    <div class="header">
        <h1><a href="index.php">SEF Blog</a></h1>
    </div>

    <?php if($post_id && $post): ?>
    <h2><?=$post['title']?></h2>
    <h2><?=$post['content']?></h2>
    <h2><?=date("F d, Y, h:i A", strtotime($post['date']))?></h2>

    <form method="post" action="categories.php?post_id=<?=$post['post_id']?>">
        <label for="category">Select Category:</label>
        <select name="category" id="category">
           <?php foreach($categories as $category): ?>
                <option value="<?=$category['category_id']?>"><?=$category['category_name']?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Assign Category</button>
    </form>

    <?php endif; ?>
</body>
</html>