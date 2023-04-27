<div class="col-3 pace-left-container">
  <div class="text-center my-5">
    <img
      src="/INFS634-A4/imgs/main-logo.svg"
      alt="main-logo"
      class="main-logo-small" />
  </div>
  <div class="d-flex flex-column align-items-center nav-options">
    <div class="w-100 nav-option">
      <div class="nav-at"></div>
      <a
        href="projects.php?userId=<?php echo $userId; ?>&username=<?php echo $username; ?>&groupId=<?php echo $groupId; ?>"
        class="d-flex w-50 mx-auto justify-content-between align-items-center">
        <i class="bi bi-house-door-fill nav-at-active"></i>
        <h2 class="nav-text nav-at-active text-decoration-none">Projects</h2>
      </a>
    </div>
    <div class="w-100 nav-option">
      <a
        href="analyze.php?userId=<?php echo $userId; ?>&username=<?php echo $username; ?>&groupId=<?php echo $groupId; ?>"
        class="d-flex w-50 mx-auto justify-content-between align-items-center">
        <i class="bi bi-pie-chart-fill"></i>
        <h2 class="nav-text">Analyze</h2>
      </a>
    </div>
    <div class="w-100 nav-option">
      <a
        href="today.php?userId=<?php echo $userId; ?>&username=<?php echo $username; ?>&groupId=<?php echo $groupId; ?>"
        class="d-flex w-50 mx-auto justify-content-between align-items-center">
        <i class="bi bi-calendar-check-fill"></i>
        <h2 class="nav-text">Today</h2>
      </a>
    </div>
    <div class="w-100 nav-option">
      <a
        href="account.php?userId=<?php echo $userId; ?>&username=<?php echo $username; ?>&groupId=<?php echo $groupId; ?>"
        class="d-flex w-50 mx-auto justify-content-between align-items-center">
        <i class="bi bi-person-circle"></i>
        <h2 class="nav-text">Account</h2>
      </a>
    </div>
  </div>
</div>
