<?php 
  require __DIR__ . '/config.php';

  try{
    $db = new PDO($dsn, $username, $password);
  }
  catch(PDOException $e){
    $error_message = $e->getMessage();
    echo "Error connecting to databse: {$error_message}";
    exit();
  }


  $sql = "SELECT * FROM statuses";
  $result = $db->query($sql);
  $statuses = $result->fetchAll(PDO::FETCH_COLUMN,1);
  array_unshift($statuses, 'all');