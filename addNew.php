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
createProject();
?>

<?php include "includes/header.php"; ?>
  <title>Projects | ADD NEW</title>
</head>
<body>
  <div class="d-flex align-items-center">
    <?php include "includes/addNewSideBar.php"; ?>
    <div class="col-9 align-self-start main-right">
      <?php include "includes/topBar.php"; ?>

      <div class="add-container p-5 overflow-scroll">
        <h1 class="addNew-header text-uppercase">Add New Project</h1>
        <div class="add-new-form-container mx-auto mt-5 pt-5">
          <form action="addNew.php?userId=<?php echo $userId; ?>&username=<?php echo $username; ?>&groupId=<?php echo $groupId; ?>" method="post" class="w-100 add-form">
            <div class="form-group d-flex align-items-center justify-content-between project-name-container">
              <label for="projectName" class="project-text">Project Name: </label>
              <input type="text" class="form-control add-input" id="projectName" name="projectName" />
            </div>
            <div class="form-group d-flex align-items-center justify-content-between project-date-container">
              <label for="projectStart" class="project-text">Project Start Date: </label>
              <div class="input-group date add-input" id="datepicker">
                <input type="text" class="form-control" id="projectStart" name="projectStart" />
                <span class="input-group-append">
                  <span class="input-group-text bg-white">
                    <i class="bi bi-calendar-week-fill"></i>
                  </span>
                </span>
              </div>
            </div>

            <div class="form-group d-flex align-items-center justify-content-between project-date-container">
              <label for="projectEnd" class="project-text">Project End Date: </label>
              <div class="input-group date add-input" id="datepicker2">
                <input type="text" class="form-control" id="projectEnd" name="projectEnd" />
                <span class="input-group-append">
                  <span class="input-group-text bg-white">
                    <i class="bi bi-calendar-week-fill"></i>
                  </span>
                </span>
              </div>
            </div>

            <h2 class="project-phases">Project Phases:</h2>

            <div class="all-phases-container">
              <div class="form-group d-flex align-items-center justify-content-between project-phases-container">
                <label for="phase1" class="project-text">Phase 1: </label>
                <input type="text" class="form-control add-phase-input" id="phase1" name="phase1" placeholder="description"/>
                <div class="input-group date add-phase-input" id="datepicker3">
                  <input type="text" class="form-control" id="due_date1" name="due_date1" placeholder="done by"/>
                  <span class="input-group-append">
                    <span class="input-group-text bg-white">
                      <i class="bi bi-calendar-week-fill"></i>
                    </span>
                  </span>
                </div>
              </div>
            </div>

            <div class="w-100 mt-5">
              <button class="btn btn-primary text-center mx-auto add-new-phase d-flex align-items-center justify-content-center" href="#" name="add-a-new-phase">
                <i class="bi bi-plus-square-fill btn-add me-5"></i>
                ADD A NEW PHASE
              </button>
            </div>

            <input type="submit" class="btn btn-primary add-submit" name="add-submit" value="SAVE" />
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(function(){
      $('#datepicker').datepicker();
      $('#datepicker2').datepicker();
      $('#datepicker3').datepicker();
    })
  </script>

  <script>
    const allPhasesContainer = document.querySelector('.all-phases-container');
    const addNewPhase = document.querySelector('.add-new-phase');
    let datepicker = 3;
    let phaseNumber = 1;

    addNewPhase.addEventListener('click', (event) => {
      phaseNumber ++;
      datepicker ++;

      allPhasesContainer.insertAdjacentHTML('beforeend', `
      <div class="form-group d-flex align-items-center justify-content-between project-phases-container">
                <label for="phase${phaseNumber}" class="project-text">Phase ${phaseNumber}: </label>
                <input type="text" class="form-control add-phase-input" id="phase${phaseNumber}" name="phase${phaseNumber}" placeholder="description"/>
                <div class="input-group date add-phase-input" id="datepicker${datepicker}">
                  <input type="text" class="form-control" id="due_date${phaseNumber}" name="due_date${phaseNumber}" placeholder="done by"/>
                  <span class="input-group-append">
                    <span class="input-group-text bg-white">
                      <i class="bi bi-calendar-week-fill"></i>
                    </span>
                  </span>
                </div>
              </div>
      `);

      $(function(){
        $(`#datepicker${datepicker}`).datepicker();
      })
      event.preventDefault();
    })
  </script>
<?php include "includes/footer.php"; ?>