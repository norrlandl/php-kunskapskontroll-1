<?php
require('dbconnect.php');


/**
 * READ (SELECT )
 */

$stmt = $pdo->query("SELECT * FROM posts");  
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
    
      <div class="blogposts">
      <?php foreach ($posts as $post) { 
        $createDate = new DateTime($post['published_date']);
        $newDate = $createDate->format('Y-m-d');

        $string = preg_replace('/\s+?(\S+)?$/', '', substr($post['content'], 0, 100));

        ?>

          <div class="post">
              <!-- <h1><?=htmlentities($post['id']) ?></h1> -->
              <h2><?=htmlentities($post['title']) ?></h2>
              <div class="author-date">
               <p><b><?=htmlentities($post['author']) ?></b> <i><?=htmlentities($newDate) ?></i></p>
             </div>
              <p><?=htmlentities($string ) ?>...
              <br>
              <br>
              <a href="post.php?id=<?=$post['id'] ?>" class="btn btn-success">Läs mer</a></p>
          </div>
          <?php }?>
        </div>
  </div>
</body>
</html>