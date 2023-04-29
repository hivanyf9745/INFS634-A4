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
$projectId = "";
$taskDescriptionValue = "";

$currentDate = date("Y-m-d");

$getKeys = array_keys($_GET);

$projectIdsNamesArr = []; //$key=>projectId, $value=>projectName
$projectIdsArr = []; //0=>projectId, 1=>projectId...
?>

<!-- Now I need to write codes for add task submissions -->
<!-- Originally all of them are unchecked -->
<?php 
$currentDate = date("Y-m-d");

if(isset($_POST['task-submit'])) {
  global $connection;
  $taskDescription = $_POST['taskDes'];

  $projectId = $_GET['projectId'];

  $addTaskQuery = "INSERT INTO tasks(project_Id, user_ID, description, today_date, checked) ";
  $addTaskQuery .= "VALUES('$projectId', '$userId', '$taskDescription', '$currentDate', '0');";

  $result = mysqli_query($connection, $addTaskQuery);

  if (!$result){
    die("Query Failed!");
  }
}
?>

<?php include "includes/header.php"; ?>
  <title>Project | Today Tasks</title>
</head>
<body>
  <div class="d-flex align-items-center">
    <?php include "includes/todaySideBar.php"; ?>
    <div class="col-9 align-self-start">
      <?php include "includes/topBar.php"; ?>
      <div class="p-5 all-detail-container overflow-scroll">
        <?php 
        /***************/
        // Step 1 retrieve projectId and projectName based on group Id presented in the link. Make it a dropdown
        global $connection, $groupId;
        $getProjectInfoQuery = "SELECT id, name FROM projects ";
        $getProjectInfoQuery .= "WHERE group_id = '$groupId';";

        $projects = mysqli_query($connection, $getProjectInfoQuery);

        if (!$projects){
          die ("Query Failed");
        }

        while ($row = mysqli_fetch_assoc($projects)) {
          $projectIdsNamesArr[$row['id']] = $row['name'];
          array_push($projectIdsArr, $row['id']);
        }
        ?>
        <!-- First thing is to create a drop down menu to show the group listings -->
        <div class="projects-today-tasks w-100 d-flex align-items-center justify-content-start">
          <div class="dropdown me-5">
            <a href="#" class="btn btn-secondary btn-beige dropdown-toggle d-flex justify-content-center align-items-center" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php 
              if (!in_array('projectId', $getKeys)) {
                $projectId = $projectIdsArr[0];
                echo $projectIdsNamesArr[$projectId];
              } else {
                $projectId = $_GET['projectId'];
                echo $projectIdsNamesArr[$projectId];
              }
              ?>
            </a>

            <ul class="dropdown-menu">
              <?php 
              foreach($projectIdsNamesArr as $key => $value) {
                echo "
                <li>
                  <a class='dropdown-item' href='today.php?userId=$userId&username=$username&groupId=$groupId&projectId=$key'>$value</a>
                </li>
                ";
              }
              ?>
            </ul>
          </div>

          <h2>For Today's Tasks</h2>
        </div>

        <!-- 2 tasks display flex -->
        <div class="d-flex justify-content-around align-items center tasks-outer-container">
          <!-- First off,  we need to get all the tasks from mysql database -->
          <?php 
          global $connection;
          $getTaskInfoQuery = "SELECT id, description, today_date, checked FROM tasks ";
          $getTaskInfoQuery .= "WHERE project_Id = '$projectId' AND today_date = '$currentDate';";

          $taskInfoResult = mysqli_query($connection, $getTaskInfoQuery);

          if(!$taskInfoResult) {
            die("Query Failed");
          }

          $taskDescriptionsArr = [];
          $tasksUnchecked = [];
          $tasksChecked = [];
          $taskIdsArr =[];
          $havetasks = '';

          while($row = mysqli_fetch_assoc($taskInfoResult)) {
            array_push($taskIdsArr, $row['id']);

            if (count($taskIdsArr) === 0) {
              $havetasks = "false";
            } else {
              $havetasks = "true";

              if ($row['checked'] == 0) {
                array_push($tasksUnchecked, $row['description']);
              } elseif ($row['checked'] == 1) {
                array_push($tasksChecked, $row['description']);
              }
            }
          }
          ?>

          <div class="tasks-container p-4">
            <h2 class="need-finish">Need to Finish:</h2>
            <ul class="list-unstyled overflow-scroll h-75">
              <?php
              if (count($taskIdsArr) !== 0 and count($tasksUnchecked) !== 0) {
                global $connection;
                foreach($tasksUnchecked as $value) {
                  $projectId = $_GET['projectId'];

                  $taskDescriptionValue = $value;

                  if (isset($_POST['check-task'])) {
                    $updateTaskCheckStatusQuery = "UPDATE tasks ";
                    $updateTaskCheckStatusQuery .= "SET checked = '1' ";
                    $updateTaskCheckStatusQuery .= "WHERE description = '$taskDescriptionValue' ";
                  
                    $updateTaskCheckStatus = mysqli_query($connection, $updateTaskCheckStatusQuery);

                    array_push($tasksChecked, $taskDescriptionValue);
                    $idx = array_search($taskDescriptionValue, $tasksUnchecked);
                    unset($tasksUnchecked[$idx]);
                  }

                foreach($tasksUnchecked as $value) {
                  echo "
                  <li class='task-list-item-active mt-3'>
                    <form action='today.php?userId=$userId&username=$username&groupId=$groupId&projectId=$projectId' method='post' class='d-flex justify-content-around align-items-center'>
                      <input type='submit' name='check-task' value='check' class='btn btn-primary btn-task-check'/>
                      <h4 class='task-check-des'>$value</h4>
                    </form>
                  </li>
                  ";
                }
                }
              } elseif (count($taskIdsArr) === 0 and count($tasksUnchecked) === 0) {
                echo "
                <li class='task-list-item text-center mt-5'>Please add some new tasks!</li>
                ";
              }
              ?>
            </ul>
          </div>

          <div class="tasks-container p-4">
            <h2 class="need-finish">Finished Tasks:</h2>
            <ul class="list-unstyled overflow-scroll h-75">
              <?php
              if (count($taskIdsArr) === 0 or count($tasksChecked) === 0) {
                echo "
                <li class='task-list-item text-center mt-5'>
                  There's no task finished for today!
                </li>
                ";
              } elseif (count($taskIdsArr) !== 0 and count($tasksChecked) !== 0) {
                foreach ($tasksChecked as $value) {
                  echo "
                  <li class='task-list-item-acive'>
                    <div class='d-flex justify-content-around align-items-center'>
                      <div>
                        <i class='bi bi-check-square-fill'></i>
                      </div>
                      <h4 class='task-check-des'>$value</h4>
                    </div>
                  </li>
                  ";
                }
              }
              ?>
            </ul>
          </div>
        </div>

        <!-- project milestone(phases) and form submission for tasks for certain project -->
        <div class="w-100 project-task-flex-outer-container">
          <div class="p-5 h-100 w-100">
            <div class="w-100 project-task-flex-container d-flex justify-content-center align-items-start p-4">
              <div class="col-6 h-100 p-3 overflow-scroll border-end">
                <ul class="list-unstyled border-start d-flex flex-column justify-cotent-around align-items-start">
                  <!-- We need to list out the phases in projectId -->
                  <?php 
                  global $connection;
                  $getPhasesInfoQuery = "SELECT id, due_date, description FROM phases ";
                  $getPhasesInfoQuery .= "WHERE project_ID = '$projectId';";

                  $phaseInfo = mysqli_query($connection, $getPhasesInfoQuery);
                  $phaseIdsArr = [];

                  while ($row = mysqli_fetch_assoc($phaseInfo)) {
                    array_push($phaseIdsArr, $row['id']);
                  }

                  if (count($phaseIdsArr) === 0) {
                    echo "
                    <li class='w-100 phase-list-item text-center'>You don't have any phases</li>
                    ";
                  }
                  ?>
                </ul>
              </div>
              <div class="col-6 h-100 p-3 overflow-scroll">

                <h3 class="add-new-task">Add A New Task for <?php echo $projectIdsNamesArr[$projectId]; ?>:</h3>
                <div class="add-task-form mx-auto mt-5">
                  <form action="today.php?userId=<?php echo $userId; ?>&username=<?php echo $username; ?>&groupId=<?php echo $groupId; ?>&projectId=<?php echo $projectId; ?>" method="post" class='w-100'>
                    <div class="form-group d-flex justify-content-between align-items-center">
                      <label for="taskDes" class="task-header">Task Description: </label>
                      <input type="text" class="form-control w-50" id="taskDes" name="taskDes" />
                    </div>
                    <input type="submit" class="btn btn-secondary btn-task w-100" name="task-submit" value="ADD A NEW TASK" />
                  </form>
                </div>
              </div>
            </div>
            <h2 class="text-center mt-2 phase-milestone">Upcoming Phase Milestones & Add Tasks</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include "includes/footer.php"; ?>