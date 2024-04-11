<?php
ob_start();//output buffering => no output is sent from the script (other than headers), instead the output is stored in an internal buffer
session_start();
if (isset($_SESSION['username'])) {

  $pageTitle = 'Dashboard';

  include 'init.php';

  $latestUsers = 5; // specify number of lateset users to show in the card
  $latest      = getLatest("*", 'users', 'userID', $latestUsers); // get latest users' array
  ?>
  <!-- // start dashboard page -->
  <div class="home-stats">
    <div class="container text-center">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-members">
            Total Members
            <span><a href="members.php">
                <?= checkCount('userID', 'users') ?>
              </a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending">pending Members
            <span><a href="members.php?do=manage&page=pending">
                <?= checkCount('regStatus', 'users', 0) ?>
              </a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-items">Total Items
            <span><a href="items.php">
                <?= checkCount('itemID', 'items') ?>
              </a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-comments">Total Comments
            <span>2500</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="latest">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <div class="card card-default">
            <div class="card-header">
              <i class="fa fa-users"></i> Latest
              <?= $latestUsers; ?> registered users
            </div>
            <div class="card-body">
              <ul class="list-unstyled latest-users">
                <?php foreach ($latest as $user) {
                  echo
                    "<li class='d-flex justify-content-between'>
                      $user[username]
                      <div>
                      <a href='members.php?do=edit&userID=$user[userID]'>
                        <span class='btn btn-success'>
                          <i class='fa fa-edit'></i> Edit
                        </span>
                      </a>";
                  if ($user['regStatus'] == 0) {
                    echo "<a href='members.php?do=activate&userID=$user[userID]' class='btn btn-info activate'><i class='fa fa-close'></i> Activate</a>";
                  }
                  echo "</div></li>";
                } ?>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="card card-default">
            <div class="card-header">
              <i class="fa fa-tag"></i> Latest items
            </div>
            <div class="card-body">
              test
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- // end dashboard page -->
  <?php
  include "$tpl/footer.php";
}
else {
  header("Location: index.php");
  exit();
}
ob_end_flush(); // output everything in buffer