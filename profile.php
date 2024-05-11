<?php
ob_start();
session_start();
$pageTitle = "Profile";

include 'init.php';

if (isset($_SESSION['user'])) {
  $info = getAllFrom('*', 'users', "WHERE username = '$sessionUser'")[0];
  // echo '<pre>';
  // print_r($currentUser);
  // $getUser = $con->prepare("SELECT * FROM users WHERE username = ?");
  // $getUser->execute(array($sessionUser));
  // $info = $getUser->fetch();
  ?>

  <h1 class="text-center">My profile</h1>
  <div class="information block">
    <div class="container">
      <div class="card">
        <div class="card-header bg-primary text-white">
          MY information
        </div>
        <div class="card-body">
          <ul class="list-unstyled">
            <li>
              <i class="fa fa-unlock-alt fa-fw"></i>
              <span>Login name</span>:
              <?= $info['username'] ?>
            </li>
            <li>
              <i class="fa fa-envelope fa-fw"></i>
              <span>email</span>:
              <?= $info['email'] ?>
            </li>
            <li>
              <i class="fa fa-user fa-fw"></i>
              <span>Fullname</span>:
              <?= $info['fullname'] ?>
            </li>
            <li>
              <i class="fa fa-calendar fa-fw"></i>
              <span>Register Date</span>:
              <?= $info['date'] ?>
            </li>
            <li>
              <i class="fa fa-tags fa-fw"></i>
              <span>Favorite Category</span>:
            </li>
          </ul>
          <a href="#" class="btn btn-outline-secondary">Edit information</a>
        </div>
      </div>
    </div>
  </div>

  <div class="ads block" id="my-ads">
    <div class="container">
      <div class="card">
        <div class="card-header bg-primary text-white">
          My items
        </div>
        <div class="card-body">

          <?php
          $items = getAllFrom('*', 'items', "WHERE memberID = {$info['userID']}", "", "itemID");
          if (!empty($items)) {
            echo "<div class='row'>";
            foreach ($items as $item) {
              echo "
            <div class='col-sm-6 col-md-3'>
              <div class='card item-box'>";
              if ($item['approve'] == 0) {
                echo "<span class='approve-status'>Waiting Approval</span>";
              }
              echo "<span class='price-tag'>$item[price]$</span>
                <img class='card-img-top img-thumbnail' src='avatar.png' alt='User Avatar'>
                <div class='card-body caption'>
                  <h3><a href='items.php?itemID=$item[itemID]'>$item[name]</a></h3>
                  <p>$item[description]</p>
                  <div class='date'>$item[add_date]</div>
                </div>
              </div>
            </div>";
            }
            echo "</div>";
          }
          else {
            echo "Sorry, There is no Ads to show, Create <a href='newad.php'>new Ad</a>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  </div>

  <div class="comments block">
    <div class="container">
      <div class="card">
        <div class="card-header bg-primary text-white">
          latest comments
        </div>
        <div class="card-body">
          <?php
          $comments = getAllFrom('comment', 'comments', "WHERE userID = {$info['userID']}", "", "comID");

          if (!empty($comments)) {
            foreach ($comments as $comment) {
              echo "<p>$comment[comment]</p>";
            }
          }
          else {
            echo "there is no comments to show";
          }
          ?>
        </div>
      </div>
    </div>
  </div>

<?php }
else {
  header("Location: login.php");
  exit();
}
include "$tpl/footer.php";
ob_end_flush();
?>