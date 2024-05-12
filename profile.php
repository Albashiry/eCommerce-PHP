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
          <?php
          $do = isset($_GET['do']) ? $_GET['do'] : 'show';
          if ($do == 'show') {
            ?>
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
              <!-- <li>
              <i class="fa fa-tags fa-fw"></i>
              <span>Favorite Category</span>:
            </li> -->
            </ul>
            <a href="profile.php?do=edit&userID=<?= $info['userID'] ?>" class="btn btn-outline-secondary">Edit
              information</a>
            <?php
          }
          elseif ($do == "edit") {
            // check if Get Request userID is numeric and get the integer value of it
            $userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
            $stmt   = $con->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");
            $stmt->execute(array($userID)); // execute query
        
            // fetch the data 
            $currentUser = $stmt->fetch();
            $count       = $stmt->rowCount();

            // if there is such ID show the form
            if ($count > 0) { ?>
              <form class="form-horizontal profile-form" action="profile.php?do=update" method="post"
                enctype="multipart/form-data">
                <input type="hidden" name="userID" value="<?= $userID ?>">
                <!-- send userID to select it in database when update -->

                <!-- start username field -->
                <div class="mb-3 row">
                  <label for="username" class="col-sm-3 col-form-label form-control-lg">Username</label>
                  <div class="col-sm-9 col-md-6 required">
                    <input type="text" name="username" id="username" class="form-control form-control-lg"
                      value="<?= $currentUser['username'] ?>" autocomplete="off" required="required">
                  </div>
                </div>
                <!-- end username field -->
                <!-- start password field -->
                <div class="mb-3 row">
                  <label for="newPassword" class="col-sm-3 col-form-label form-control-lg">Password</label>
                  <div class="col-sm-9 col-md-6 required">
                    <input type="hidden" name="oldPassword" id="oldPassword" value="<?= $currentUser['password'] ?>">
                    <input type="password" name="newPassword" id="newPassword" class="form-control form-control-lg"
                      autocomplete="new-password" placeholder="Leave blank if you don't to change">
                  </div>
                </div>
                <!-- end password field -->
                <!-- start email field -->
                <div class="mb-3 row">
                  <label for="email" class="col-sm-3 col-form-label form-control-lg">Email</label>
                  <div class="col-sm-9 col-md-6 required">
                    <input type="email" name="email" id="email" class="form-control form-control-lg"
                      value="<?= $currentUser['email'] ?>" required="required">
                  </div>
                </div>
                <!-- end email field -->
                <!-- start fullname field -->
                <div class="mb-3 row">
                  <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Fullname</label>
                  <div class="col-sm-9 col-md-6 required">
                    <input type="text" name="fullname" id="fullname" class="form-control form-control-lg"
                      value="<?= $currentUser['fullname'] ?>" required="required">
                  </div>
                </div>
                <!-- end fullname field -->
                <!-- start avatar field -->
                <div class="mb-3 row">
                  <label for="avatar" class="col-sm-3 col-form-label form-control-lg">User avatar</label>
                  <div class="col-sm-9 col-md-6 required">
                    <input type="hidden" name="oldAvatar" id="oldAvatar" value="<?= $currentUser['avatar'] ?>">
                    <input type="file" name="avatar" id="avatar" class="form-control form-control-lg">
                  </div>
                </div>
                <!-- end avatar field -->
                <!-- start submit field -->
                <div class="mb-3 row">
                  <div class="offset-sm-3 col-sm-9">
                    <input type="submit" value="Save" class="btn btn-primary btn-lg">
                  </div>
                </div>
                <!-- end submit field -->
              </form>
            <?php }
          }
          elseif ($do == 'update') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

              // Extract details from the uploaded file
              $avatarName = $_FILES['avatar']['name'];
              $avatarSize = $_FILES['avatar']['size'];
              $avatarType = $_FILES['avatar']['type'];
              $avatarTemp = $_FILES['avatar']['tmp_name'];

              // Allowed file type list
              $allowedExtensions = array('jpeg', 'jpg', 'png', 'gif');

              // Extract extension
              $avatarExtension = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));

              // get the variables from the form
              $userID = $_POST['userID'];
              $user   = $_POST['username'];
              $email  = $_POST['email'];
              $name   = $_POST['fullname'];
              $avatar = $_POST['oldAvatar'];

              // password trick
              $pass = empty($_POST['newPassword']) ? $_POST['oldPassword'] : sha1($_POST['newPassword']);

              // validate the form
              $formErrors = array();
              if (strlen($user) < 4) {
                $formErrors[] = 'username can\'t be less than <strong>4 characters</strong>';
              }
              if (strlen($user) > 20) {
                $formErrors[] = 'username can\'t be more than <strong>20 characters</strong>';
              }
              if (empty($user)) {
                $formErrors[] = 'username can\'t be <strong>empty</strong>';
              }
              if (empty($name)) {
                $formErrors[] = 'fullname can\'t be <strong>empty</strong>';
              }
              if (empty($email)) {
                $formErrors[] = 'email can\'t be <strong>empty</strong>';
              }
              if (!empty($avatarName) && !in_array($avatarExtension, $allowedExtensions)) {
                $formErrors[] = 'This extension is <strong>not allowed</strong>';
              }
              if ($avatarSize > 5242880) {
                $formErrors[] = 'Avatar can\'t larger than <strong>5MB</strong>';
              }
              foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
              }

              // check if there is no error, proceed the update operation
              if (empty($formErrors)) {

                $stmt2 = $con->prepare("SELECT * FROM users WHERE username = ? AND userID != ?");
                $stmt2->execute(array($user, $userID));
                $count = $stmt2->rowCount();

                if ($count) {
                  $theMsg = '<div class="alert alert-danger">Sorry, this user is exists</div>';
                  redirectHome($theMsg, 'back', 2);
                }
                else {
                  if (!empty($avatarName)) {
                    $avatar = rand(0, 99999999999) . '_' . $avatarName;
                    move_uploaded_file($avatarTemp, "data\uploads\avatars\\$avatar");
                  }

                  // update the database with this info
                  $stmt = $con->prepare("UPDATE users SET username=?, password=?, email=?, fullname=?, avatar=? WHERE userID=?");
                  $stmt->execute(array($user, $pass, $email, $name, $avatar, $userID));

                  // echo success message
                  echo '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated</div>';
                  header("Refresh: 1; url=profile.php");
                }
              }
            }
            else {
              $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
              redirectHome($theMsg);
            }
          }
          else {
            header("Location: profile.php");
            exit();
          } ?>
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
                echo "
                <a href='newAd.php?do=edit&itemID=$item[itemID]'>
                  <span class='approve-status'>Waiting Approval</span>
                </a>";
              }
              $imgSource = empty($item['image']) ? "default-item.jpg" : $item['image'];
              echo "<span class='price-tag'>$item[price]$</span>
                
                  <img class='card-img-top img-thumbnail' src='data\uploads\items\\$imgSource' alt='User Avatar'>
              
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