<?php include "helpers/db.php"; ?>
<?php include "helpers/functions.php"; ?>
<?php userLogin(); ?>
<?php 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>

<?php include "includes/header.php"; ?>
  <title>PACE | Your Workflow Management System</title>
</head>
<body>
  <div class="align-items-center d-none d-lg-flex">
    <div class="col-4 logo-container">
      <img src="/INFS634-A4/imgs/main-logo.svg" alt="main-logo" class="main-logo-img"/>
    </div>
    <div class="col-8 login-container">
      <div class="container login-inner-container p-5">
        <h1 class="login-text">Log In</h1>

        <form action="index.php" method="get" class="form-container w-75">
          <div class="form-group d-flex align-items-center justify-content-between input-form">
            <label for="usernname">Username: </label>
            <input type="text" class="form-control" id="username" name="username"/>
          </div>
          <div class="form-group d-flex align-items-center justify-content-between input-form">
            <label for="password">Password: </label>
            <input type="password" class="form-control" id="password" name="password" />
          </div>
          <div class="d-flex justify-content-end align-items-center">
            <input type="submit" class="btn btn-primary" value="Log In" name="submit">
          </div>
          <hr class="my-5"/>
        </form>

        <div class="text-center register-text">Not a member yet? You can <span><a class="text-decoration-underline register-link" href="register.php">register</a></span> here.</div>
      </div>
    </div>
  </div>
<?php include "includes/footer.php"; ?>