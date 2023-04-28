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

$haveProjects = "";
$testArr = [];
$completionPercentage = [];
$projectStartDatesArr = [];
$projectEndDatesArr = [];
$projectIdsArr = [];

$sampleProjectNamesArr = [];
$sampleProjectIdsArr = [];

$projectId = '';
$completion = '';
$projectName = '';
$projectStart = '';
$projectEnd ='';

$taskDescriptionsArr = [];
?>

<?php include "includes/header.php"; ?>
  <title>Projects</title>
</head>
<body>
  <div class="d-flex align-items-center">
    <?php include "includes/projectSideBar.php"; ?>
    <div class="col-9 align-self-start">
      <?php include "includes/topBar.php"; ?>
      <div class="p-5 all-detail-container overflow-scroll">
        <div class="w-100 p-3 d-flex justify-content-around align-items-center">
          <?php 
          /************/
          // To find the related project Id
          global $connection, $groupId;

          $getProjectInfoQuery = "SELECT id, name, DATEDIFF(end_date, start_date) as days, end_date, start_date FROM projects ";
          $getProjectInfoQuery .= "WHERE group_id = '$groupId'";

          $projects = mysqli_query($connection, $getProjectInfoQuery);

          if (!$projects) {
            die("Query Failed");
          }

          while ($row = mysqli_fetch_assoc($projects)) {
            array_push($testArr, $row['id']);
            $projectId = $row['id'];
            $projectName = $row['name'];
            $projectDays = $row['days'];
            $projectStartDate = $row['start_date'];
            $projectEndDate = $row['end_date'];

            $currentDate = date("Y-m-d");

            // Calculate the difference between due date and current date
            $dateDiff = strtotime($projectEndDate) - strtotime($currentDate);
            $dateDiff = round($dateDiff / (60 * 60 *24));

            /************/
            // To see about the date difference and determine the project completion percentage;
            if ($dateDiff <= 0) {
              // it means the completion rate is 100%;
              $completionPercentage[$projectName] = '100';
              // Now other info stored in project assoc_arrays
              $projectIdsArr[$projectName] = $projectId;
              $projectStartDatesArr[$projectName] = $projectStartDate;
              $projectEndDatesArr[$projectName] = $projectEndDate;
            } else  {
              // it means the completion rate is NOT 100%; Need to Calculate
              $projectCompletionPercentage = ($projectDays - $dateDiff) / $projectDays * 100;
              $completionPercentage[$projectName] = round($projectCompletionPercentage);
              // Other info stored in the project assoc_arrays
              $projectIdsArr[$projectName] = $projectId;
              $projectStartDatesArr[$projectName] = $projectStartDate;
              $projectEndDatesArr[$projectName] = $projectEndDate;
            }
          }

          /************/
          // To see if the group id will have projects
          if(count($testArr) === 0) {
            // if false, check in the $haveprojects variable
            $haveProjects = "false";
          } else {
            $haveProjects = "true";

            /************/
            // Extract first three projects and related info
            $projectIdsArrKeys = array_keys($projectIdsArr);
            // print_r($projectIdsArrKeys);
            $x = 0;
            foreach($projectIdsArrKeys as $value) {
              if ($x <= 2) {
                array_push($sampleProjectNamesArr, $value);
                $x++;
              } else {
                break;
              }
            }

            $projectIdsArrValues = array_values($projectIdsArr);
            $y = 0;
            foreach($projectIdsArrValues as $value) {
              if($y <= 2) {
                array_push($sampleProjectIdsArr, $value);
                $y++;
              } else {
                break;
              }
            }
          }
          ?>

          <?php 
          if ($haveProjects === "false") {
            echo "
            <div class='w-100 mt-5'>
              <h2 class='text-center'>You don't have any Projects</h2>
            </div>
            ";
          } elseif ($haveProjects === "true") {
            // print_r($sampleProjectNamesArr);
            foreach($sampleProjectNamesArr as $value) {
              $projectId = $projectIdsArr[$value];
              $projectEndDate = $projectEndDatesArr[$value];

              /***********/
              // Completion calculations,so that we can get the correct progress bar circle
              $completionSpecific = $completionPercentage[$value];
              $leftDegree = 360 * ($completionSpecific/100) -180;
              $rightDegree = 360 * ($completionSpecific/100);
              $finalLeftDegree = '';
              $finalRightDegree = '';

              if ($leftDegree <= 0) {
                $finalLeftDegree = '0';
              } else {
                $finalLeftDegree = $leftDegree;
              }

              if ($rightDegree >= 180) {
                $finalRightDegree = '180';
              } else {
                $finalRightDegree = $rightDegree;
              }

              /***********/
              // Decide who is going to be progress active (with box shadow and darker background)
              $getKeys = array_keys($_GET);
              if (in_array('projectId', $getKeys) and $projectId === $_GET['projectId']) {
                echo "
                  <a class='progress-container progress-active d-flex justify-content-center align-items-center' href='projects.php?userId=$userId&username=$username&groupId=$groupId&projectId=$projectId'>
                    <h2 class='project-name'>$value</h2>
                    <div class='progress blueone'>
                      <span class='progress-left'>
                        <span class='progress-bar' style='transform: rotate($finalLeftDegree"."deg)'></span>
                      </span>
                      <span class='progress-right'>
                        <span class='progress-bar' style='transform: rotate($finalRightDegree"."deg)'></span>
                      </span>
                      <div class='progress-value'>
                        $completionSpecific%
                      </div>
                    </div>
                    <div class='project-due'>
                      Deadline:
                      <span>$projectEndDate</span>
                    </div>
                  </a>
                ";
              } elseif(!in_array('projectId', $getKeys)) {
                echo "
                  <a class='progress-container d-flex justify-content-center align-items-center' href='projects.php?userId=$userId&username=$username&groupId=$groupId&projectId=$projectId'>
                    <h2 class='project-name'>$value</h2>
                    <div class='progress blueone'>
                      <span class='progress-left'>
                        <span class='progress-bar' style='transform: rotate($finalLeftDegree"."deg)'></span>
                      </span>
                      <span class='progress-right'>
                        <span class='progress-bar' style='transform: rotate($finalRightDegree"."deg)'></span>
                      </span>
                      <div class='progress-value'>
                        $completionSpecific%
                      </div>
                    </div>
                    <div class='project-due'>
                      Deadline:
                      <span>$projectEndDate</span>
                    </div>
                  </a>
                ";
              } else {
                echo "
                  <a class='progress-container d-flex justify-content-center align-items-center' href='projects.php?userId=$userId&username=$username&groupId=$groupId&projectId=$projectId'>
                    <h2 class='project-name'>$value</h2>
                    <div class='progress blueone'>
                      <span class='progress-left'>
                        <span class='progress-bar' style='transform: rotate($finalLeftDegree"."deg)'></span>
                      </span>
                      <span class='progress-right'>
                        <span class='progress-bar' style='transform: rotate($finalRightDegree"."deg)'></span>
                      </span>
                      <div class='progress-value'>
                        $completionSpecific%
                      </div>
                    </div>
                    <div class='project-due'>
                      Deadline:
                      <span>$projectEndDate</span>
                    </div>
                  </a>
                ";
              }
            }
          }
          ?>
        </div>

        <?php 
        if ($haveProjects === 'true') {
          echo "
            <div class='w-100 text-end mt-5'>
              <a href='#'><h3 class='more-projects'>More projects</h3></a>
            </div>
          ";
        } elseif ($haveProjects === "false") {
          echo "";
        }
        ?>

        <?php 
        if ($haveProjects === "true") {
          $getKeys = array_keys($_GET);

          if(!in_array('projectId', $getKeys)) {
            $completionPercentageKeys = array_keys($completionPercentage);
            $projectName = $completionPercentageKeys[0];
            $projectId = $projectIdsArr[$projectName];
            $completion = $completionPercentage[$projectName];
            $projectStart = $projectStartDatesArr[$projectName];
            $projectEnd = $projectEndDatesArr[$projectName];
          } else {
            $projectId = $_GET['projectId'];
            $projectName = array_search($projectId, $projectIdsArr);
            $completion = $completionPercentage[$projectName];
            $projectStart = $projectStartDatesArr[$projectName];
            $projectEnd = $projectEndDatesArr[$projectName];
          }

          $getPhasesQuery = "SELECT description, due_date FROM phases ";
          $getPhasesQuery .= "WHERE project_ID = '$projectId';";

          $phasesResult = mysqli_query($connection, $getPhasesQuery);
          if (!$phasesResult) {
            die("Query Failed");
          }

          $phaseDescriptionsArr = [];
          $phaseDuesArr = [];

          while ($row = mysqli_fetch_assoc($phasesResult)) {
            array_push($phaseDescriptionsArr, $row['description']);
            array_push($phaseDuesArr, $row['due_date']);
          }

          /************/
          // Now you are done with the phases retrieval, it's time to deal with progress bar degree again

          $leftDegree = 360 * ($completion/100) - 180;
          $rightDegree = 360 * ($completion/100);
          $finalLeftDegree = '';
          $finalRightDegree = '';

          if ($leftDegree <= 0) {
            $finalLeftDegree = '0';
          } else {
            $finalLeftDegree = $leftDegree;
          }

          if ($rightDegree >= 180) {
            $finalRightDegree = '180';
          } else {
            $finalRightDegree = $rightDegree;
          }

          /************/
          // for the phases portion this is how I should write it
          $phaseCol = "";
          if (count($phaseDescriptionsArr) === 0) {
            $phaseCol = "<h2 class='text-center'>You don't have any phases yet!</h2>";
          } else {
            foreach ($phaseDescriptionsArr as $value) {
              $dueIdx = array_search($value, $phaseDescriptionsArr);

              $dueDate = $phaseDuesArr[$dueIdx];

              $currentDate = date("Y-m-d");

              $dateDiff = strtotime($dueDate) - strtotime($currentDate);
              $dateDiff = round($dateDiff / (60 * 60 *24));

              if ($dateDiff <= 0) {
                $phaseCol = $phaseCol . "
                <div class='w-100 d-flex justify-content-between due-date-container px-5'>
                  <h3 class='mb-1 phase-due-date opacity-50'>$dueDate</h3>
                  <h4 class='phase-description opacity-50 w-50'>$value</h4>
                </div>
                ";
              } else {
                $phaseCol = $phaseCol . "
                <div class='w-100 d-flex justify-content-between due-date-container px-5'>
                  <h3 class='mb-1 phase-due-date'>$dueDate</h3>
                  <h4 class='phase-description w-50'>$value</h4>
                </div>
                ";
              }
            }
          }

          /************/
          // Enter the tasks query to retrieve tasks data
          $tasksQuery = "SELECT description FROM tasks ";
          $tasksQuery .= "WHERE project_Id = '$projectId';";

          $tasksResults = mysqli_query($connection, $tasksQuery);

          $tasksCol = "";

          while ($row = mysqli_fetch_assoc($tasksResults)) {
            array_push($taskDescriptionsArr, $row['description']);
          }

          if(count($taskDescriptionsArr) === 0) {
            $tasksCol = "<div class='w-100 text-center task-none px-3 mt-5'>You don't have any tasks presented yet</div>";
          }

          echo "
            <div class='project-outer-container mt-5'>
              <h2 class='project-detail-header pt-3 ms-3'>Project Name: $projectName</h2>

              <div class='mt-5 h-75 d-flex justify-content-center align-items-center'>
                <div class='col-3 h-100 border-end project-completion'>
                  <div class='h-100 d-flex justify-content-center align-items-center'>
                    <div class='progress blueone'>
                      <span class='progress-left'>
                        <span class='progress-bar' style='transform: rotate($finalLeftDegree"."deg)'></span>
                      </span>
                      <span class='progress-right'>
                        <span class='progress-bar' style='transform: rotate($finalRightDegree"."deg)'></span>
                      </span>
                      <div class='progress-value'>$completion%</div>
                    </div>
                  </div>
                  <h3 class='text-center col-status'>Completion: $completion%</h3>
                </div>
                <div class='col-6 h-100 phase-outer-container'>
                  <div class='phase-container mx-auto overflow-scroll d-flex flex-column'>
                    $phaseCol
                  </div>
                  <h3 class='text-center col-status-timeline'>Timeline: $projectStart to $projectEnd</h3>
                </div>
                <div class='col-3 h-100 border-start task-outer-container'>
                  $tasksCol
                  <h3 class='text-center col-status-task'>Tasks</h3>
                </div>
              </div>
            </div>
          ";
        } else {
          echo "";
        }
        ?>
      </div>
    </div>
  </div>
<?php include "includes/footer.php"; ?>