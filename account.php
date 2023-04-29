<?php include "helpers/db.php"; ?>
<?php include "helpers/functions.php"; ?>

<?php 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>

<?php
$userId = $_GET['userId'];
$username = $_GET['username'];
$groupId = $_GET['groupId'];
?>

<?php 
global $connection;

$getUserQuery = "SELECT email, group_ID FROM users ";
$getUserQuery .= "WHERE id = '$userId';";

$userResult = mysqli_query($connection, $getUserQuery);

$userEmail = '';
$userGroupIdsArr = [];
$userGroupNamesArr = [];
$userGroupNamesIdsAssocArr = [];

if (!$userResult) {
  die("Query Failed");
}

while ($row = mysqli_fetch_assoc($userResult)) {
  $userEmail = $row['email'];
  array_push($userGroupIdsArr, $row['group_ID']);
}

/********/
// Now we need to start on the groups linkage with users
foreach ($userGroupIdsArr as $value) {
  $getGroupNameQuery = "SELECT group_name FROM groups ";
  $getGroupNameQuery .= "WHERE id = '$value';";

  $groupName = mysqli_query($connection, $getGroupNameQuery);

  while ($row = mysqli_fetch_assoc($groupName)) {
    array_push($userGroupNamesArr, $row['group_name']);
    $userGroupNamesIdsAssocArr[$value] = $row['group_name'];
  }
}
?>

<?php include "includes/header.php"; ?>
  <title>Projects</title>
</head>
<body>
  <div class="d-flex align-items-center">
    <?php include "includes/accountSideBar.php"; ?>
    <div class="col-9 align-self-start">
      <?php include "includes/topBar.php"; ?>
      <div class="p-5 add-container overflow-scroll">
        <h1 class="account-header text-capitalize">Account Information</h1>

        <div class="account-outer-container mx-auto mt-5 pt-5">
          <div class="account-container w-100">
            <div class="d-flex align-items-center justify-content-between my-5">
              <h2 class="account-username-label">Username:</h2>
              <h2 class="account-username w-50"><?php echo $username; ?></h2>
            </div>

            <div class="d-flex align-items-center justify-content-between my-5">
              <h2 class="account-username-label">Email:</h2>
              <h2 class="account-username w-50 text-decoration-underline"><?php echo $userEmail; ?></h2>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-5 pt-5">
              <h2 class="account-username-label">Groups:</h2>
              <div class="dropdown w-50">
                <a class="btn btn-secondary btn-beige dropdown-toggle d-flex justify-content-center align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <?php 
                  echo $userGroupNamesIdsAssocArr[$groupId]; 
                  ?>
                </a>
                
                <ul class="dropdown-menu">
                  <?php 
                  foreach($userGroupNamesIdsAssocArr as $key => $value) {
                    echo "
                    <li>
                      <a class='dropdown-item' href='account.php?userId=$userId&username=$username&groupId=$key'>$value</a>
                    </li>
                    ";
                  }
                  ?>
                </ul>
              </div>
            </div>

          </div>
        </div>

        <div class="log-out-container text-end">
          <a class="btn btn-primary btn-logout" href="index.php">Log Out</a>
        </div>
      </div>
    </div>
  </div>
<?php include "includes/footer.php"; ?>