<?php
session_start();
if (isset($_SESSION['username'])) {

  $pageTitle = 'Dashboard';

  include 'init.php';
  ?>
  <!-- // start dashboard page -->
  <div class="home-stats">
    <div class="container text-center">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-members">
            Total Members
            <span><a href="members.php"><?= countItems('userID', 'users')?></a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending">pending Members
            <span><a href="members.php?do=manage&page=pending"><?= checkCount('regStatus', 'users', 0)?></a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-items">Total Items
            <span>2000</span>
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
          <div class="panel panel-default">
            <div class="panel-header">
              <i class="fa fa-users"></i> Latest registered users
            </div>
            <div class="panel-body">
              test
            </div>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-header">
              <i class="fa fa-tag"></i> Latest items
            </div>
            <div class="panel-body">
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