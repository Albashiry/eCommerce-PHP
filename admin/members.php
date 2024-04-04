<?php

/* manage memberspage
 * you can {add | edit | delete} members from here
 * */
session_start();
$pageTitle = 'Members';

if (isset($_SESSION['username'])) {
  include 'init.php';

  // split page with Get request
  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

  if ($do == 'manage') {// manage members page 

    // select users except admins
    $stmt = $con->prepare("SELECT * FROM users WHERE groupID != 1");
    $stmt->execute();

    $rows = $stmt->fetchAll();

    ?>

    <h1 class="text-center">Manage Members</h1>
    <div class="container">
      <div class="table-responsive text-center">
        <table class="main-table table table-bordered">
          <thead></thead>
          <tbody>
            <tr>
              <th>#ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Fullname</th>
              <th>Registered Date</th>
              <th>Control</th>
            </tr>
            <?php
            foreach ($rows as $row) {
              echo '<tr>';
              echo "<td>$row[userID]</td>";
              echo "<td>$row[username]</td>";
              echo "<td>$row[email]</td>";
              echo "<td>$row[fullname]</td>";
              echo "<td>$row[date]</td>";
              echo "<td>
                        <a href='members.php?do=edit&userID=$row[userID]' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='members.php?do=delete&userID=$row[userID]' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>
                      </td>";
              echo '</tr>';
            }
            ?>
          </tbody>
          <tfoot></tfoot>
        </table>
      </div>

      <a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New member</a>
    </div>


  <?php }
  elseif ($do == 'add') { // add members page
    ?>

    <h1 class="text-center">Add New Member</h1>
    <div class="container">
      <form class="form-horizontal" action="members.php?do=insert" method="post">
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

    echo '<h1 class="text-center">Update Member</h1>';
    echo '<div class="container">';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
      foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
      }

      // check if there is no error, proceed the update operation
      if (empty($formErrors)) {

        // check if user exists in database
        $check = checkItem('username', 'users', $user);
        if ($check) {
          $theMsg = '<div class="alert alert-danger">Sorry, this user is exist</div>';
          redirectHome($theMsg, 'back');

        }
        else {
          // insert user info into the database
          $stmt = $con->prepare("INSERT INTO users (username, password, email, fullname, date)
                                 VALUES (:zuser, :zpass, :zmail, :zname, now())");
          $stmt->execute(
            array(
              'zuser' => $user,
              'zpass' => $hashPass,
              'zmail' => $email,
              'zname' => $name
            )
          ); //bind parameters and execute query

          // echo success message
          $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted</div>';
          redirectHome($theMsg, 'back');
        }
      }
    }
    else {

      $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
      redirectHome($theMsg, 'back');

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
        <form class="form-horizontal" action="members.php?do=update" method="post">
          <input type="hidden" name="userID" value="<?= $userID ?>">
          <!-- send userID to seelct it in database when update -->

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

      // get the variables from the form
      $userID = $_POST['userID'];
      $user   = $_POST['username'];
      $email  = $_POST['email'];
      $name   = $_POST['fullname'];

      // password trick
      $pass = empty($_POST['newPassword']) ? $pass = $_POST['oldPassword'] : $pass = sha1($_POST['newPassword']);

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
      foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
      }

      // check if there is no error, proceed the update operation
      if (empty($formErrors)) {

        // update the database with this info
        $stmt = $con->prepare("UPDATE users SET username=?, email=?, fullname=?, password=? WHERE userID=?");
        $stmt->execute(array($user, $email, $name, $pass, $userID));

        // echo success message
        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated</div>';
        redirectHome($theMsg, 'back');

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
    $check = checkItem('userID', 'users', $userID);    

    // if there is such ID show the form
    if ($check > 0) {
      $stmt = $con->prepare("DELETE FROM users WHERE userID = :zuser");
      $stmt->bindParam(':zuser', $userID);
      $stmt->execute();

      // echo success message
      $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record deleted</div>';
      redirectHome($theMsg);

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