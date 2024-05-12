<?php

/* manage members page
 * you can {add | edit | delete} members from here
 * */
ob_start(); // output buffreing start
session_start();
$pageTitle = 'Members';

if (isset($_SESSION['username'])) {
  include 'init.php';

  // split page with Get request
  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

  if ($do == 'manage') {// manage members page 

    $query = '';
    if (isset($_GET['page']) && $_GET['page'] == 'pending') {
      $query = 'AND regStatus = 0';
    }

    // select users except admins
    $stmt = $con->prepare("SELECT * FROM users WHERE groupID != 1 $query ORDER BY userID DESC");
    $stmt->execute();

    $users = $stmt->fetchAll();

    ?>

    <h1 class="text-center">Manage Members</h1>
    <div class="container">
      <?php if (!empty($users)) { ?>
        <div class="table-responsive text-center">
          <table class="main-table table table-bordered">
            <thead></thead>
            <tbody>
              <tr>
                <th>#ID</th>
                <th>Avatar</th>
                <th>Username</th>
                <th>Email</th>
                <th>Fullname</th>
                <th>Registered Date</th>
                <th>Control</th>
              </tr>
              <?php
              foreach ($users as $user) {
                echo '<tr>';
                echo "<td>$user[userID]</td>";
                echo "<td class='avatar'>";
                echo empty($user['avatar'])
                  ? "<img src='..\data\uploads\avatars\default-avatar.jpg' alt='default-avatar'></td>"
                  : "<img src='..\data\uploads\avatars\\$user[avatar]' alt='$user[avatar]'></td>";
                echo "<td>$user[username]</td>";
                echo "<td>$user[email]</td>";
                echo "<td>$user[fullname]</td>";
                echo "<td>$user[date]</td>";
                echo "<td class='text-end control'>";
                if ($user['regStatus'] == 0) {
                  echo "<a href='members.php?do=activate&userID=$user[userID]' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                }
                echo "<a href='members.php?do=edit&userID=$user[userID]' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='members.php?do=delete&userID=$user[userID]' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                echo "</td>";
                echo '</tr>';
              }
              ?>
            </tbody>
            <tfoot></tfoot>
          </table>
        </div>
      <?php }
      else {
        echo '<div class="alert alert-info">There is no member to show</div>';
      } ?>
      <a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New member</a>
    </div>


  <?php }
  elseif ($do == 'add') { // add members page
    ?>

    <h1 class="text-center">Add New Member</h1>
    <div class="container">
      <form class="form-horizontal" action="members.php?do=insert" method="post" enctype="multipart/form-data">
        <!-- start username field -->
        <div class="mb-3 row">
          <label for="username" class="col-sm-3 col-form-label form-control-lg">Username</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="username" id="username" class="form-control form-control-lg" autocomplete="off"
              required="required" placeholder="Username to login into shop">
          </div>
        </div>
        <!-- end username field -->
        <!-- start password field -->
        <div class="mb-3 row">
          <label for="password" class="col-sm-3 col-form-label form-control-lg">Password</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="password" name="password" id="password" class="password form-control form-control-lg"
              autocomplete="new-password" required="required" placeholder="Password must be hard and complex">
            <i class="show-pass fa fa-eye fa-2x"></i>
          </div>
        </div>
        <!-- end password field -->
        <!-- start email field -->
        <div class="mb-3 row">
          <label for="email" class="col-sm-3 col-form-label form-control-lg">Email</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="email" name="email" id="email" class="form-control form-control-lg" required="required"
              placeholder="Email must be valid">
          </div>
        </div>
        <!-- end email field -->
        <!-- start fullname field -->
        <div class="mb-3 row">
          <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Fullname</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="fullname" id="fullname" class="form-control form-control-lg" required="required"
              placeholder="Fullname appear in your profile page">
          </div>
        </div>
        <!-- end fullname field -->
        <!-- start avatar field -->
        <div class="mb-3 row">
          <label for="avatar" class="col-sm-3 col-form-label form-control-lg">User avatar</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="file" name="avatar" id="avatar" class="form-control form-control-lg" required="required">
          </div>
        </div>
        <!-- end avatar field -->
        <!-- start submit field -->
        <div class="mb-3 row">
          <div class="offset-sm-3 col-sm-9">
            <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
          </div>
        </div>
        <!-- end submit field -->
      </form>
    </div>

    <?php

  }
  elseif ($do == 'insert') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      echo '<h1 class="text-center">Insert Member</h1>';
      echo '<div class="container">';

      // Extract details from the uploaded file
      $avatarName = $_FILES['avatar']['name'];
      $avatarSize = $_FILES['avatar']['size'];
      $avatarType = $_FILES['avatar']['type'];
      $avatarTemp = $_FILES['avatar']['tmp_name'];

      // Allowed file type list
      $allowedExtensions = array('jpeg', 'jpg', 'png', 'gif');

      // Extract extension
      // $explodedName    = explode('.', $avatarName);  // Use a variable to store the result
      // $avatarExtension = strtolower(end($explodedName));       // Use the variable with end()
      $avatarExtension = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));

      // get the variables from the form
      $user  = $_POST['username'];
      $pass  = $_POST['password'];
      $email = $_POST['email'];
      $name  = $_POST['fullname'];

      $hashPass = sha1($pass);

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
      if (empty($pass)) {
        $formErrors[] = 'password can\'t be <strong>empty</strong>';
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
      if (empty($avatarName)) {
        $formErrors[] = 'Avatar is <strong>required</strong>';
      }
      if ($avatarSize > 5242880) {
        $formErrors[] = 'Avatar can\'t larger than <strong>5MB</strong>';
      }
      foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
      }

      // check if there is no error, proceed the update operation
      if (empty($formErrors)) {

        // check if user exists in database
        $check = checkCount('username', 'users', $user);
        if ($check) {
          $theMsg = '<div class="alert alert-danger">Sorry, this user is exists</div>';
          redirectHome($theMsg, 'back');

        }
        else {
          $avatar = rand(0, 99999999999) . '_' . $avatarName;
          move_uploaded_file($avatarTemp, "..\data\uploads\avatars\\$avatar");
          // insert user info into the database
          $stmt = $con->prepare("INSERT INTO users (username, password, email, fullname, regStatus, date, avatar)
                                 VALUES (:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");
          $stmt->execute(
            array(
              'zuser'   => $user,
              'zpass'   => $hashPass,
              'zmail'   => $email,
              'zname'   => $name,
              'zavatar' => $avatar
            )
          ); //bind parameters and execute query

          // echo success message
          $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted</div>';
          redirectHome($theMsg, 'back');
        }
      }
    }
    else {
      echo '<div class="container">';
      $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
      redirectHome($theMsg);
    }
    echo '</div>';


  }
  elseif ($do == 'edit') { // edit members page

    // check if Get Request userID is numeric and get the integer value of it
    $userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;

    // select all data depend on this ID
    $stmt = $con->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");
    $stmt->execute(array($userID)); // execute query

    // fetch the data 
    $row   = $stmt->fetch();
    $count = $stmt->rowCount();

    // if there is such ID show the form
    if ($count > 0) { ?>

      <h1 class="text-center">Edit Member</h1>
      <div class="container">
        <form class="form-horizontal" action="members.php?do=update" method="post" enctype="multipart/form-data">
          <input type="hidden" name="userID" value="<?= $userID ?>">
          <!-- send userID to select it in database when update -->

          <!-- start username field -->
          <div class="mb-3 row">
            <label for="username" class="col-sm-3 col-form-label form-control-lg">Username</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="text" name="username" id="username" class="form-control form-control-lg"
                value="<?= $row['username'] ?>" autocomplete="off" required="required">
            </div>
          </div>
          <!-- end username field -->
          <!-- start password field -->
          <div class="mb-3 row">
            <label for="newPassword" class="col-sm-3 col-form-label form-control-lg">Password</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="hidden" name="oldPassword" id="oldPassword" value="<?= $row['password'] ?>">
              <input type="password" name="newPassword" id="newPassword" class="form-control form-control-lg"
                autocomplete="new-password" placeholder="Leave blank if you don't to change">
            </div>
          </div>
          <!-- end password field -->
          <!-- start email field -->
          <div class="mb-3 row">
            <label for="email" class="col-sm-3 col-form-label form-control-lg">Email</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="email" name="email" id="email" class="form-control form-control-lg" value="<?= $row['email'] ?>"
                required="required">
            </div>
          </div>
          <!-- end email field -->
          <!-- start fullname field -->
          <div class="mb-3 row">
            <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Fullname</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="text" name="fullname" id="fullname" class="form-control form-control-lg"
                value="<?= $row['fullname'] ?>" required="required">
            </div>
          </div>
          <!-- end fullname field -->
          <!-- start avatar field -->
          <div class="mb-3 row">
            <label for="avatar" class="col-sm-3 col-form-label form-control-lg">User avatar</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="hidden" name="oldAvatar" id="oldAvatar" value="<?= $row['avatar'] ?>">
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
      </div>

    <?Php }
    else { // if there is no such ID show error message
      echo '<div class="container">';
      $theMsg = '<div class="alert alert-danger">There is no such id</div>';
      redirectHome($theMsg);
      echo '</div>';

    }
  }
  elseif ($do == 'update') { // update page

    echo '<h1 class="text-center">Update Member</h1>';
    echo '<div class="container">';

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
            move_uploaded_file($avatarTemp, "..\data\uploads\avatars\\$avatar");
          }

          // update the database with this info
          $stmt = $con->prepare("UPDATE users SET username=?, password=?, email=?, fullname=?, avatar=? WHERE userID=?");
          $stmt->execute(array($user, $pass, $email, $name, $avatar, $userID));

          // echo success message
          $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated</div>';
          redirectHome($theMsg, 'back');
        }
      }

    }
    else {
      $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
      redirectHome($theMsg);

    }
    echo '</div>';


  }
  elseif ($do == 'delete') { // delete members page

    echo '<h1 class="text-center">Delete Member</h1>';
    echo '<div class="container">';

    // check if Get Request userID is numeric and get the integer value of it
    $userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;

    // check data depend on this ID
    $check = checkCount('userID', 'users', $userID);

    // if there is such ID show the form
    if ($check > 0) {
      $stmt = $con->prepare("DELETE FROM users WHERE userID = :zuser");
      $stmt->bindParam(':zuser', $userID);
      $stmt->execute();

      // echo success message
      $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record deleted</div>';
      redirectHome($theMsg, 'back', 1);

    }
    else {
      $theMsg = '<div class="alert alert-danger">This ID is not exist</div>';
      redirectHome($theMsg);

    }
    echo '</div>';


  }
  elseif ($do == 'activate') { // activate members

    echo '<h1 class="text-center">Activate Member</h1>';
    echo '<div class="container">';

    // check if Get Request userID is numeric and get the integer value of it
    $userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;

    // check data depend on this ID
    $check = checkCount('userID', 'users', $userID);

    // if there is such ID show the form
    if ($check > 0) {
      // $stmt = $con->prepare("UPDATE users SET regStatus =1 WHERE userID = :zuser");
      // $stmt->bindParam(':zuser', $userID);
      // $stmt->execute();
      $stmt = $con->prepare("UPDATE users SET regStatus =1 WHERE userID = ?");
      $stmt->execute(array($userID));

      // echo success message
      $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record activated</div>';
      redirectHome($theMsg, 'back', 1);

    }
    else {
      $theMsg = '<div class="alert alert-danger">This ID is not exist</div>';
      redirectHome($theMsg);

    }
    echo '</div>';

  }



  include "$tpl/footer.php";
}
else {
  header("Location: index.php");
  exit();
}
ob_end_flush();