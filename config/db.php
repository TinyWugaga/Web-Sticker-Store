<?php
  $servername = "localhost";
  $dbname = "practice";
  $username = "web_class";
  $password = "2333";
  //echo "test";
  try {
    $conn = new PDO(
      "mysql:host=$servername;dbname=$dbname;",
      $username, $password
    );
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
?>