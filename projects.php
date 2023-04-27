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
global $groupId, $connection;

$getProjectInfoQuery = "SELECT id, name, DATEDIFF(end_date, start_date) as days, end_date, start_date FROM projects ";
$getProjectInfoQuery .= "WHERE group_id = '$groupId'";

$projects = mysqli_query($connection, $getProjectInfoQuery);
$completions = [];
$duesArr = [];
$projectIdArr = [];


while ($row = mysqli_fetch_assoc($projects)) {
  $projectId = $row['id'];
  $projectName = $row['name'];
  $projectDays = $row['days'];
  $projectEndDate = $row['end_date'];

  $currentDate = date("Y-m-d");

  $dateDiff = strtotime($projectEndDate) - strtotime($currentDate);
  $dateDiff = round($dateDiff / (60 * 60 * 24));

  if ($dateDiff <= 0) {
    $completions[$projectName] = '100';
    $duesArr[$projectName] = $projectEndDate;
    $projectIdArr[$projectName] = $projectId;
  } else {
    $completePercent = ($projectDays - $dateDiff) / $projectDays * 100;
    $completions[$projectName] = round($completePercent);
    $duesArr[$projectName] = $projectEndDate;
    $projectIdArr[$projectName] = $projectId;
  }
}

// print_r($completions);
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
          <a class="progress-container <?php 
          $completionKeys = array_keys($completions);

          $projectId = $projectIdArr[$completionKeys[0]];

          $getKeys = array_keys($_GET);

          if (!in_array('projectId', $getKeys) or $_GET['projectId'] === $projectId) {
            echo "progress-active";
          } else {
            echo "";
          }
          ?> d-flex justify-content-center align-items-center" href="<?php 
          $completionKeys = array_keys($completions);

          $projectId = $projectIdArr[$completionKeys[0]];
          echo "projects.php?userId=$userId&username=$username&groupId=$groupId&projectId=$projectId"?>">
            <h2 class="project-name">
              <?php 
              $completionKeys = array_keys($completions);

              echo $completionKeys[0];
              ?>
            </h2>
            <div class="progress blueone">
              <span class="progress-left">
                <span class="progress-bar" style="transform: rotate(<?php
            $completionKeys = array_keys($completions);

            $percentage = $completions[$completionKeys[0]];
            
            $finaldegree = 360 * ($percentage/100) - 180;

            if($finaldegree <= 0) {
              echo "0";
            } else {
              echo $finaldegree;
            }
            ?>deg)"></span>
              </span>
              <span class="progress-right">
                <span class="progress-bar" style="transform: rotate(<?php
            $completionKeys = array_keys($completions);

            $percentage = $completions[$completionKeys[0]];
            
            $finaldegree = 360 * ($percentage/100);

            if($finaldegree >= 180) {
              echo "180";
            } else {
              echo $finaldegree;
            }
            ?>deg)"></span>
              </span>
              <div class="progress-value">
                <?php 
                $completionKeys = array_keys($completions);

                $percentage = $completions[$completionKeys[0]];

                echo $percentage . "%";
                ?>
              </div>
            </div>

            <div class="project-due">
              Deadline: 
              <span>
              <?php 
              $completionKeys = array_keys($completions);

              echo $duesArr[$completionKeys[0]];
              ?>
              </span>
            </div>
          </a>

          <a class="progress-container <?php 
          $completionKeys = array_keys($completions);

          $projectId = $projectIdArr[$completionKeys[1]];

          $getKeys = array_keys($_GET);

          if (in_array('projectId', $getKeys) and $_GET['projectId'] === $projectId) {
            echo "progress-active";
          } else {
            echo "";
          }
          ?> d-flex justify-content-center align-items-center" href="<?php 
          $completionKeys = array_keys($completions);

          $projectId = $projectIdArr[$completionKeys[1]];
          echo "projects.php?userId=$userId&username=$username&groupId=$groupId&projectId=$projectId"?>">
            <h2 class="project-name">
              <?php 
              $completionKeys = array_keys($completions);

              echo $completionKeys[1];
              ?>
            </h2>
            <div class="progress bluetwo">
              <span class="progress-left">
                <span class="progress-bar" style="transform: rotate(<?php
            $completionKeys = array_keys($completions);

            $percentage = $completions[$completionKeys[1]];
            
            $finaldegree = 360 * ($percentage/100) - 180;

            if($finaldegree <= 0) {
              echo "0";
            } else {
              echo $finaldegree;
            }
            ?>deg)"></span>
              </span>
              <span class="progress-right">
                <span class="progress-bar" style="transform: rotate(<?php
            $completionKeys = array_keys($completions);

            $percentage = $completions[$completionKeys[1]];
            
            $finaldegree = 360 * ($percentage/100);

            if($finaldegree >= 180) {
              echo "180";
            } else {
              echo $finaldegree;
            }
            ?>deg)"></span>
              </span>
              <div class="progress-value">
                <?php 
                $completionKeys = array_keys($completions);

                $percentage = $completions[$completionKeys[1]];

                echo $percentage . "%";
                ?>
              </div>
            </div>

            <div class="project-due">
              Deadline: 
              <span>
              <?php 
              $completionKeys = array_keys($completions);

              echo $duesArr[$completionKeys[1]];
              ?>
              </span>
            </div>
          </a>

          <a class="progress-container <?php 
          $completionKeys = array_keys($completions);

          $projectId = $projectIdArr[$completionKeys[2]];

          $getKeys = array_keys($_GET);

          if (in_array('projectId', $getKeys) and $_GET['projectId'] === $projectId) {
            echo "progress-active";
          } else {
            echo "";
          }
          ?> d-flex justify-content-center align-items-center" href="<?php 
          $completionKeys = array_keys($completions);

          $projectId = $projectIdArr[$completionKeys[2]];
          echo "projects.php?userId=$userId&username=$username&groupId=$groupId&projectId=$projectId"?>">
            <h2 class="project-name">
              <?php 
              $completionKeys = array_keys($completions);

              echo $completionKeys[2];
              ?>
            </h2>
            <div class="progress bluethree">
              <span class="progress-left">
                <span class="progress-bar" style="transform: rotate(<?php
            $completionKeys = array_keys($completions);

            $percentage = $completions[$completionKeys[2]];
            
            $finaldegree = 360 * ($percentage/100) - 180;

            if($finaldegree <= 0) {
              echo "0";
            } else {
              echo $finaldegree;
            }
            ?>deg)"></span>
              </span>
              <span class="progress-right">
                <span class="progress-bar" style="transform: rotate(<?php
            $completionKeys = array_keys($completions);

            $percentage = $completions[$completionKeys[2]];
            
            $finaldegree = 360 * ($percentage/100);

            if($finaldegree >= 180) {
              echo "180";
            } else {
              echo $finaldegree;
            }
            ?>deg)"></span>
              </span>
              <div class="progress-value">
                <?php 
                $completionKeys = array_keys($completions);

                $percentage = $completions[$completionKeys[2]];

                echo $percentage . "%";
                ?>
              </div>
            </div>

            <div class="project-due">
              Deadline: 
              <span>
              <?php 
              $completionKeys = array_keys($completions);

              echo $duesArr[$completionKeys[2]];
              ?>
              </span>
            </div>
          </a>


        </div>

        <div class="w-100 text-end mt-5">
          <a href="#"><h3 class="more-projects">More Projects</h3></a>
        </div>

        <div class="project-outer-container mt-5">
            <?php 
            $getKeys = array_keys($_GET);
            
            $projectId = '';
            $completion = '';
            $projectName = '';
  
            if (!in_array('projectId', $getKeys)) {
              $completionKeys = array_keys($completions);
              $projectId = $projectIdArr[$completionKeys[0]];
              $completion = $completions[$completionKeys[0]];
              $projectName = $completionKeys[0];
            } else {
              $projectId = $_GET['projectId'];
              $projectName = array_search($projectId, $projectIdArr);
              $completion = $completions[$projectName];
            }

            $getPhaseQuery = "SELECT description, due_date FROM phases ";
            $getPhaseQuery .= "WHERE project_ID = '$projectId';";

            $phaseResult = mysqli_query($connection, $getPhaseQuery);

            $phaseDescriptionArr = [];
            $phaseDueArr = [];

            while ($row = mysqli_fetch_assoc($phaseResult)) {
              array_push($phaseDescriptionArr, $row['description']);
              array_push($phaseDueArr, $row['due_date']);
            }
            ?>
          <h2 class="project-detail-header pt-3 ms-3">Project Name: <?php echo $projectName; ?></h2>
          <div class="mt-5 h-75 d-flex justify-content-center align-items-center">
  
            <div class="col-3 h-100 border-end project-completion">
            <div class="h-100 d-flex justify-content-center align-items-center">
            <div class="progress blueone">
                <span class="progress-left">
                  <span class="progress-bar" style="transform: rotate(<?php
  
              
              $finaldegree = 360 * ($completion/100) - 180;
  
              if($finaldegree <= 0) {
                echo "0";
              } else {
                echo $finaldegree;
              }
              ?>deg)"></span>
                </span>
                <span class="progress-right">
                  <span class="progress-bar" style="transform: rotate(<?php
              
              $finaldegree = 360 * ($completion/100);
  
              if($finaldegree >= 180) {
                echo "180";
              } else {
                echo $finaldegree;
              }
              ?>deg)"></span>
                </span>
                <div class="progress-value">
                  <?php echo $completion . "%"; ?>
                </div>
            </div>
          </div>
          <h3 class="text-center col-status">
            Completion: <?php echo $completion."%"; ?>
          </h3>
            </div>
            <div class="col-6 h-100">
              <div class="phase-container mx-auto overflow-scroll d-flex flex-column">
                <?php 
                if (count($phaseDescriptionArr) === 0) {
                  echo "<h2 class='text-center'>You don't have any phases yet!</h2>";
                } else {
                  foreach ($phaseDescriptionArr as $value) {
                    $dueIdx = array_search($value, $phaseDescriptionArr);

                    $dueDate = $phaseDueArr[$dueIdx];

                    echo "
                      <div class='w-100 d-flex justify-content-around due-date-container'>
                        <h3 class='mb-1'>$dueDate</h3>
                        <h4>$value</h4>
                      </div>
                    ";
                  }
                }
                ?>
              </div>
            </div>
            <div class="col-3 h-100 border-start"></div>
  
          </div>
        </div>
      </div>

    </div>
  </div>
<?php include "includes/footer.php"; ?>