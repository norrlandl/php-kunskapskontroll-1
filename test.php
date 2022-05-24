<?php
require('dbconnect.php');

$stmt = $pdo->query("SELECT * FROM posts");  
$posts = $stmt->fetchAll();

// $mysqli = NEW $mysqli ('localhost', 'root', '', 'blog');

if(isset($_GET['order'])) {
  $order = $_GET['order'];
} else {
  $order = 'title';
}

if(isset($_GET['sort'])) {
  $sort = $_GET['sort'];
} else {
  $sort = 'ASC';
}

$resultSet = $pdo->query("SELECT * FROM posts ORDER BY $order $sort"); 


  // $sort == 'DESC' 

  echo"
    <table border='1'>
      <tr>
        <th>Title</th>
        <th>Author</th>
    ";

    foreach ($posts as $post)
    {
        $title = $post['title'];
        $author = post['author'];
        echo "
        <tr>
          <th>$title</th>
          <th>$author</th>
        </tr>
        ";
    }
  
    echo"
    </tr>
    </table>
    ";

?>

if ($stmt->)