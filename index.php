<?php
require('dbconnect.php');

/**
 * SELECT & ORDER
 */

 if(isset($_GET['order'])) {
  $order = $_GET['order'];
} else {
  $order = 'id';
}

if(empty($_GET['order'])) {
  $order = 'id';
}

$stmt = $pdo->query("SELECT * FROM posts ORDER BY $order");  
$posts = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


<!-- Force css-file to reload -->
  <link rel="stylesheet" href="style/style.css?v=<?php echo time(); ?>">

  <title>Kontrolluppgift-1 blogg sida</title>

</head>
<body>

  <div class="menubar"> 
    <div class="main-container menu">
    <a href="admin.php" class="links">ADMIN</a>
    </div>
  </div>
  
  <div class="main-container">
  
      <h1>Blogginlägg</h1>

      <div>
        <h5>Sortera inlägg efter</h5>
        <form action="" method="GET">
          <select class="form-select" name="order" aria-label="Default select example" onchange="this.form.submit()">
            <option value=""> Välj</option>
            <option value="author" <?php if(isset($_GET['order']) && $_GET['order'] == "author"){ echo "selected";} ?> >Författare </option>
            <option value="id" <?php if(isset($_GET['order']) && $_GET['order'] == "id"){ echo "selected";} ?> >Datum </option>
          </select>
        </form>
      </div>
    
      <div class="blogposts">
      <?php foreach ($posts as $post) { 

        $createDate = new DateTime($post['published_date']);
        $newDate = $createDate->format('Y-m-d');
        $string = preg_replace('/\s+?(\S+)?$/', '', substr($post['content'], 0, 100));

        ?>

          <div class="post">
              <h2><?=htmlentities($post['title']) ?></h2>
              <div class="author-date">
               <p><b><?=htmlentities($post['author']) ?></b> <i><?=htmlentities($newDate) ?></i></p>
             </div>
              <p><?=htmlentities($string ) ?>...
              <br>
              <br>
              <a href="post.php?id=<?=$post['id'] ?>" class="btn btn-success">Läs mer →</a></p>
          </div>
          <?php }?>
        </div>
  </div>
  <?php include('footer.php'); ?>