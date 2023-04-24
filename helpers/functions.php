<?php include "db.php"; ?>

<?php 
function createUser () {
  global $connection;

  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $rePassword = $_POST['retype-password'];

    if ($rePassword === $password) {
      $query = "INSERT INTO users(username, email, password) ";
      $query .= "VALUES('$username', '$email', '$password') ";
    
      $result = mysqli_query($connection, $query);
    
      if (!$result) {
        die("Query Failed!");
      }
    } else {
      echo "<script>alert('Password did not match!')</script>";
    }
  }
}
?>