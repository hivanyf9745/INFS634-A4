<?php 
  $connection = mysqli_connect('localhost', 'root', '', 'pace');

  if (!$connection) {
    die("Database connection failed");
  }
?>