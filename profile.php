<?php
session_start();
$pageTitle = "Profile";

include 'init.php';

if (isset($_SESSION['user'])) {
  $getUser = $con->prepare("SELECT * FROM users WHERE username = ?");
  $getUser->execute(array($sessionUser));
  $info = $getUser->fetch();
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
        </div>
      </div>
    </div>
  </div>

  <div class="ads block">
    <div class="container">
      <div class="card">
        <div class="card-header bg-primary text-white">
          MY ads
        </div>
        <div class="card-body">

          <?php
          if (!empty(getItems('memberID', $info['userID']))) {
            echo "<div class='row'>";
            foreach (getItems('memberID', $info['userID']) as $item) {
              echo "
            <div class='col-sm-6 col-md-3'>
              <div class='card item-box'>
              <span class='price-tag'>$item[price]</span>
                <img class='card-img-top img-thumbnail' src='avatar.png' alt='User Avatar'>
                <div class='card-body'>
                  <h3>$item[name]</h3>
                  <p>$item[description]</p>
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
          // show item comments
          $stmt = $con->prepare("SELECT comment FROM comments WHERE userID = ?");
          $stmt->execute(array($info['userID']));

          $comments = $stmt->fetchAll();
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
?>