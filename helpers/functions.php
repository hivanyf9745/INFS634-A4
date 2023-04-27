<?php include "db.php"; ?>

<?php 
function createUser () {
  global $connection;

  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $rePassword = $_POST['retype-password'];
    $groupname = $_POST['groupname'];

    /**********/
    //Now I need to make sure that the group name does not exist in the database.
    $readGroup = "SELECT group_name FROM groups;";
    $existingGroupNames = mysqli_query($connection, $readGroup);
    $groupnameArr = [];

    while ($row = mysqli_fetch_assoc($existingGroupNames)) {
      array_push($groupnameArr, $row['group_name']);
    }

    if (!in_array($groupname, $groupnameArr)) {
      $addGroupQuery = "INSERT INTO groups(group_name) ";
      $addGroupQuery .= "VALUES('$groupname');";

      $addtoGroup = mysqli_query($connection, $addGroupQuery);

      if (!$addtoGroup) {
        die("Query Failed");
      }
    }

    /**********/
    //fetch the group id from the groups database
    $fetchGroupId = "SELECT id FROM groups ";
    $fetchGroupId .= "WHERE group_name = '$groupname';";

    $preGroupId = mysqli_query($connection, $fetchGroupId);
    $groupId = 0;

    while ($row = mysqli_fetch_assoc($preGroupId)) {
      $groupId = $row['id'];
    }

    /**********/
    //Now I need to make sure that the email does not exist in the database.
    $readQuery = "SELECT email FROM users;";
    $existResults = mysqli_query($connection, $readQuery);
    $emailArr = [];
    
    while ($row = mysqli_fetch_assoc($existResults)) {
      array_push($emailArr, $row['email']);
    }

    if ($rePassword === $password and !in_array($email, $emailArr)) {
      $query = "INSERT INTO users(username, group_ID, email, password) ";
      $query .= "VALUES('$username', '$groupId', '$email', '$password') ";
    
      $result = mysqli_query($connection, $query);
    
      if (!$result) {
        die("Query Failed!");
      } 
    } else {
      echo "<script>alert('Something went wrong! Either your password did not match or the email is already taken')</script>";
    }

    $redirectQuery = "SELECT id FROM users ";
    $redirectQuery .= "WHERE username = '$username' AND password = '$password';";

    $redirectedResult = mysqli_query($connection, $redirectQuery);

    while($row = mysqli_fetch_assoc($redirectedResult)) {
      $redirectedId = $row['id'];
      header("Location: projects.php?userId=$redirectedId&username=$username&groupId=$groupId");
    }
  }
}

function userLogin() {
  if (isset($_GET['submit'])) {
    global $connection;
  
    $username = $_GET['username'];
    $password = $_GET['password'];
  
    $query = "SELECT id, group_ID FROM users ";
    $query .= "WHERE username = '$username' AND password = '$password';";
  
    $result = mysqli_query($connection, $query);
  
    if (!$result) {
      die("Query Failed!");
    }
  
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      $groupId = $row['group_ID'];
      header("Location: projects.php?userId=$id&username=$username&groupId=$groupId");
    }
  }
}

function createProject () {
  if (isset($_POST['add-submit'])) {
    global $connection;
    global $userId;
    global $username;
    global $groupId;
  
    $projectName = $_POST['projectName'];
    $startDate = $_POST['projectStart'];
    $endDate = $_POST['projectEnd'];
    
    $startDate = date("Y-m-d", strtotime($startDate));
    $endDate = date("Y-m-d", strtotime($endDate));
  
    $projectQuery = "INSERT INTO projects(name, start_date, end_date, group_id) ";
    $projectQuery .= "VALUES('$projectName', '$startDate', '$endDate', '$groupId');";
  
    $generalResult = mysqli_query($connection, $projectQuery);
  
    /***********/
    // Retrieve the project Id
  
    $retrieveProjectId = "SELECT id FROM projects ";
    $retrieveProjectId .= "WHERE name = '$projectName';";
  
    $retrievedResult = mysqli_query($connection, $retrieveProjectId);
    $projectId = '';
  
    while ($row = mysqli_fetch_assoc($retrievedResult)) {
      $projectId = $row['id'];
    }
  
    /***********/
    // Starting to deal with phases
  
    $postKeys = array_keys($_POST);
    $phasesArr = [];
    $duesArr = [];
    foreach($postKeys as $value) {
      if (str_contains($value, 'phase') !== false) {
        array_push($phasesArr, $value);
      }
    }
  
    foreach($postKeys as $value) {
      if (str_contains($value, 'due_date')) {
        array_push($duesArr, $value);
      }
    }
  
    foreach($phasesArr as $value) {
      $phaseDes = $_POST[$value];
      $idx = array_search($value, $phasesArr);
      $dueDateName = $duesArr[$idx];
      $dueDate = $_POST[$dueDateName];
  
      $dueDate = date("Y-m-d", strtotime($dueDate));
  
      $query = "INSERT INTO phases(project_ID, due_date, description) ";
      $query .= "VALUES('$projectId', '$dueDate', '$phaseDes');"; 
  
      $result = mysqli_query($connection, $query);
  
      if (!$result) {
        die("Query FAILED!");
      }
    }
  
  
    
    if (!$generalResult) {
      die("Query Failed!");
    } else {
      header("Location: projects.php?userId=$userId&username=$username&groupId=$groupId");
    }
  }
}
?>