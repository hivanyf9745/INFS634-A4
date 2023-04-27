<?php include "helpers/db.php"; ?>
<?php include "helpers/functions.php"; ?>
<?php 
createUser()
?>

<?php 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>

<?php include "includes/header.php"; ?>
  <title>PACE | Registration</title>
</head>
<body>
  <div class="align-items-center d-none d-lg-flex">'
    <div class="col-4 logo-container">
      <img src="/INFS634-A4/imgs/main-logo.svg" alt="main-logo" class="main-logo-img"/>
    </div>
    <div class="col-8 container p-5 align-self-start">
      <h1 class="register-header">Register</h1>

      <form action="register.php" method="post" class="w-75 register-form-container">
        <div class="form-group d-flex align-items-center justify-content-between input-form">
          <label for="username">Username: </label>
          <input type="text" class="form-control register-form" id="username" name="username"/>
        </div>
        <div class="form-group d-flex align-items-center justify-content-between input-form">
          <label for="email">Email: </label>
          <input type="email" class="form-control register-form" id="email" name="email"/>
        </div>
        <div class="form-group d-flex align-items-center justify-content-between input-form">
            <label for="password">Password: </label>
            <input type="password" class="form-control register-form" id="password" name="password" />
        </div>
        <div class="form-group d-flex align-items-center justify-content-between input-form">
            <label for="retype-password">Re-type password: </label>
            <input type="password" class="form-control register-form" id="retype-password" name="retype-password" />
        </div>
        <div class="form-group d-flex align-items-center justify-content-between input-form">
            <label for="groupname">Group Name: </label>
            <input type="text" class="form-control register-form" id="groupname" name="groupname" />
        </div>
        <div class="d-flex justify-content-end align-items-center">
            <input type="submit" class="btn btn-primary btn-login" value="Sign In" name="submit">
        </div>
      </form>
    </div>
  </div>
<?php include "includes/footer.php"; ?>