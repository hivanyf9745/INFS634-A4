<?php include "db.php"; ?>

<?php 
function createUser () {
  global $connection;

  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $rePassword = $_POST['retype-password'];

    /**********/
    //Now I need to make sure that the email do not exist in the database.
    $readQuery = "SELECT email FROM users";
    $existResults = mysqli_query($connection, $readQuery);
    $emailArr = [];
    
    while ($row = mysqli_fetch_assoc($existResults)) {
      array_push($emailArr, $row['email']);
    }

    if ($rePassword === $password and !in_array($email, $emailArr)) {
      $query = "INSERT INTO users(username, email, password) ";
      $query .= "VALUES('$username', '$email', '$password') ";
    
      $result = mysqli_query($connection, $query);
    
      if (!$result) {
        die("Query Failed!");
      }
    } else {
      echo "<script>alert('Something went wrong! Either your password did not match or the email is already taken')</script>";
    }
  }
}

function userLogin() {
  if (isset($_GET['submit'])) {
    global $connection;
  
    $username = $_GET['username'];
    $password = $_GET['password'];
  
    $query = "SELECT id FROM users ";
    $query .= "WHERE username = '$username' AND password = '$password';";
  
    $result = mysqli_query($connection, $query);
  
    if (!$result) {
      echo "<script>alert('Something went wrong: Either your password or your username is incorrect')</script>";
    }
  
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      header("Location: projects.php?userId=$id");
    }
  }
}
?>