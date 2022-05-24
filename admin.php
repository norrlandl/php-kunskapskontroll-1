<?php
require('dbconnect.php');

$errorText = "";
$errorTitle = "";
$errorAuthor = "";
$createSuccess = "";
$updateSuccess = "";

echo "<pre>";
print_r($_GET);
echo "</pre>";


/**
 * DELETE 
 */

 if (isset($_POST['deletePost'])) {
   $sql = "DELETE FROM posts WHERE id = :id;";

   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(":id", $_POST['postId']);
   $stmt->execute();
 }


/**
 * CREATE 
 */


if (isset($_POST['addPost'])) {
  $post = trim($_POST['post']); 
  $title = trim($_POST['title']); 
  $author = trim($_POST['author']); 

// Check if empty
  if(empty($title)) {
    $errorTitle = '
    <div class="alert alert-danger error">
    Title missing.
    </div>';
  }

  if (empty($post)) {
    $errorText = '
    <div class="alert alert-danger error">
    Text missing.
    </div>';
  }  
  
  if(empty($author)) {
    $errorAuthor = '
    <div class="alert alert-danger error">
    Author missing.
    </div>';
  }

// If not empty create
  if ($author != "" AND $title != "" AND $post != "") {
    $sql = "
    INSERT INTO posts (title, content, author) 
    VALUES (:title, :content, :author);
    ";

    $createSuccess = '
    <div class="alert alert-success success">
    Ditt inlägg är postat!
    </div>';
  
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":content", $post);
    $stmt->bindParam(":author", $author);
    $stmt->execute();
  }

};


/**
 * UPDATE 
 */

if (isset($_POST['editPost'])) {

$post = trim($_POST['post']); 
$title = trim($_POST['title']); 
$author = trim($_POST['author']); 

  if(empty($title)) {
    $errorTitle = '
    <div class="alert alert-danger error">
    Title missing.
    </div>';
  }

  if (empty($post)) {
    $errorText = '
    <div class="alert alert-danger error">
    Text missing.
    </div>';
  }  

  if(empty($author)) {
    $errorAuthor = '
    <div class="alert alert-danger error">
    Author missing.
    </div>';
  } 
  
  if ($author != "" AND $title != "" AND $post != "") {
    $sql = "
    UPDATE posts 
    SET   content = :editContent,
          title = :editTitle,
          author = :editAuthor
    WHERE id = :id;";

    $updateSuccess = '
    <div class="alert alert-success success">
    Ditt inlägg är uppdaterat!
    </div>';
  
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $_POST['id']);
    $stmt->bindParam(":editContent", $_POST['post']);
    $stmt->bindParam(":editTitle", $_POST['title']);
    $stmt->bindParam(":editAuthor", $_POST['author']);
    $stmt->execute();
  }
}

/**
 * SELECT & ORDER
 */

if(isset($_GET['order'])) {
  $order = $_GET['order'];
} else {
  $order = 'id';
}

if(isset($_GET['sort'])) {
  $sort = $_GET['sort'];
} else {
  $sort = 'ASC';
  echo "<pre>";
  print_r($sort);
  echo "</pre>";
} 

echo "<pre>";
print_r($sort);
echo "</pre>";

$sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';

$stmt = $pdo->query("SELECT * FROM posts ORDER BY $order $sort");  
$posts = $stmt->fetchAll();

?>


<!-- HTML -->

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
    <a href="index.php">START</a>
    </div>
  </div>

<div class="main-container">
  <h1>Admin</h1>

  <table class="table">
  <thead>
    <tr>
      <th><a href='?order=title&sort=$sort'>Title<i></i></a></th>
      <th><a href='?order=author&sort=$sort'>Author</a></th>
      <th>Inlägg</th>
      <th><a href='?order=id&sort=$sort'>Date</a></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>

    <?php foreach ($posts as $post) :

      $createDate = new DateTime($post['published_date']);
      $newDate = $createDate->format('Y-m-d');

      ?>

      <tr>
        <td><b><?=htmlentities($post['title']) ?></b></td>
        <td><?=htmlentities($post['author']) ?></td>
        <td><?=htmlentities(substr($post['content'], 0, 15)) ?></td>
        <td><i><?=htmlentities($newDate) ?></i></td>
        <td>
<!-- // DELETE -->
        <form action="" method="POST">
          <input type="hidden" name="postId" value="<?=$post['id']?>">
          <input type="submit" class="btn btn-secondary" name="deletePost" value="Delete">
        </form>
        </td>
        <td>
<!-- // EDIT -->
        <button type="button" data-toggle="modal" class="btn btn-warning float-end" data-target="#updateModal" data-post="<?=htmlentities($post['content'])?>" data-title="<?=htmlentities($post['title'])?>"
        data-author="<?=htmlentities($post['author'])?>" data-id="<?=htmlentities($post['id'])?>">Update</button>

        </td>
      </tr>

      <?php endforeach; ?>
  </tbody>
</table>

<div class="messages"> 
<?=$errorTitle?>
<?=$errorText?>
<?=$errorAuthor?> 
<?=$createSuccess?>
<?=$updateSuccess?>
</div>

<!-- FORM CREATE -->
  <div class="form">
    <h2>Skapa nytt inlägg</h2>
      <form action="" method="POST">
        <div class="modal-body form-style">
          <div class="form-group">
            <label  for="recipient-name" class="col-form-label">Title:</label>
            <input  id="test" type="text" class="form-control" name="title">
           
            <label for="recipient-name" class="col-form-label">Text:</label>
            <textarea class="form-control" id="form-height" name="post" ></textarea>  
                  
            <label for="recipient-name" class="col-form-label">Author:</label>
            <input type="text" class="form-control" name="author">
      
            <input type="hidden" name="id">
        </div>
          </div>
          <div class="modal-footer form-style">
            <input type="submit" name="addPost" value="Create" class="btn btn-success">
          </div>
      </form>
        <div>
  </div>


<!-- MODAL UPDATE -->
<div id="updateModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update post</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="POST">
            <div class="modal-body">
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">Title:</label>
                <input type="text" class="form-control" name="title">
                <label for="recipient-name" class="col-form-label">Text:</label>
                <textarea class="form-control" id="form-height" name="post" ></textarea>   
                <label for="recipient-name" class="col-form-label">Author:</label>
                <input type="text" class="form-control" name="author">
                <input type="hidden" name="id">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <input type="submit" name="editPost" value="Update" class="btn btn-success">
            </div>
          </form>
        </div>
      </div>
</div>




<!-- jQuery AND Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

<!-- jQuery Modal -->

<script>

$('#updateModal').on('show.bs.modal', function (event) {

  var button = $(event.relatedTarget) // Button that triggered the modal
  var post = button.data('post');       // Extract the info from the attribute data-pun
  var author = button.data('author');        // Extract the info from the attribute data-id
  var title = button.data('title'); 
  var id = button.data('id');        // Extract the info from the attribute data-id
  console.log(post);
  console.log(author);
  console.log(id);
  
  
  var modal = $(this)
  modal.find('.modal-body textarea[name="post"]').val(post);
  modal.find('.modal-body input[name="id"]').val(id);
  modal.find('.modal-body input[name="author"]').val(author);
  modal.find('.modal-body input[name="title"]').val(title);
})

</script>


</body>
</html>