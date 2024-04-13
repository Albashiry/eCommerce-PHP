<?php
ob_start();//output buffering => no output is sent from the script (other than headers), instead the output is stored in an internal buffer
session_start();
if (isset($_SESSION['username'])) {

  $pageTitle = 'Dashboard';

  include 'init.php';

  $usersNumber = 5; // specify number of lateset users to show in the card
  $latestUsers = getLatest("*", 'users', 'userID', $usersNumber); // get latest users' array

  $itemsNumber = 5; // specify number of lateset items to show in the card
  $latestItems = getLatest("*", 'items', 'itemID', $itemsNumber); // get latest users' array
  ?>
  <!-- // start dashboard page -->
  <div class="home-stats">
    <div class="container text-center">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-members d-flex justify-content-around align-items-center">
            <i class="fa fa-users"></i>
            <div class="info">
              Total Members
              <span>
                <a href="members.php">
                  <?= checkCount('userID', 'users') ?>
                </a>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending d-flex justify-content-around align-items-center">
            <i class="fa fa-user-plus"></i>
            <div class="info">
              pending Members
              <span>
                <a href="members.php?do=manage&page=pending">
                  <?= checkCount('regStatus', 'users', 0) ?>
                </a>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-items d-flex justify-content-around align-items-center">
            <i class="fa fa-tag"></i>
            <div class="info">
              Total Items
              <span>
                <a href="items.php">
                  <?= checkCount('itemID', 'items') ?>
                </a>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-comments d-flex justify-content-around align-items-center">
            <i class="fa fa-comments"></i>
            <div class="info">
              Total Comments
              <span>
                <a href="comments.php">
                  0
                </a>
              </span>
            </div>
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
            <div class="card-header d-flex justify-content-between align-items-center">
              <div>
                <i class="fa fa-users"></i> Latest
                <?= $usersNumber; ?> registered users
              </div>
              <span class="toggle-info">
                <i class="fa fa-minus fa-lg"></i>
              </span>
            </div>
            <div class="card-body">
              <ul class="list-unstyled latest-users">
                <?php foreach ($latestUsers as $user) {
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
                    echo "<a href='members.php?do=activate&userID=$user[userID]' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                  }
                  echo "</div></li>";
                } ?>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="card card-default">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div>
                <i class="fa fa-tag"></i> Latest items

              </div>
              <span class="toggle-info">
                <i class="fa fa-minus fa-lg"></i>
              </span>
            </div>
            <div class="card-body">
              <ul class="list-unstyled latest-users">
                <?php foreach ($latestItems as $item) {
                  echo
                    "<li class='d-flex justify-content-between'>
                      $item[name]
                      <div>
                      <a href='items.php?do=edit&itemID=$item[itemID]'>
                        <span class='btn btn-success'>
                          <i class='fa fa-edit'></i> Edit
                        </span>
                      </a>";
                  if ($item['approve'] == 0) {
                    echo "<a href='items.php?do=approve&itemID=$item[itemID]' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                  }
                  echo "</div></li>";
                } ?>
              </ul>
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