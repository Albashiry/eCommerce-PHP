<?php
ob_start();
session_start();
$pageTitle = "Login";
if (isset($_SESSION['user'])) {
  header('Location: index.php');
}

include 'init.php';

// check if user comming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['login'])) {
    $user       = isset($_POST['username']) ? $_POST['username'] : '';
    $pass       = isset($_POST['password']) ? $_POST['password'] : '';
    $hashedPass = sha1($pass);

    // check if the user exists in the database
    $stmt = $con->prepare("SELECT userID, username, password 
                            FROM users 
                            WHERE username = ? AND password = ?"
    );

    $stmt->execute(array($user, $hashedPass));
    $get = $stmt->fetch();
    $count = $stmt->rowCount();

    // if count > 0 this means that the database contain record about this username
    if ($count > 0) {
      $_SESSION['user'] = $user; // register session name
      $_SESSION['uid'] = $get['userID']; // register userID in session
      header("Location: index.php"); // redirect to dashboard.php
      exit();
    }
  }
  else {
    $formErrors = array();

    if (isset($_POST['username'])) {
      // $filteredUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
      // Constant FILTER_SANITIZE_STRING is deprecated => user htmlspecialchars() or strip_tags()
      $filteredUser = strip_tags($_POST['username']);
      if (strlen($filteredUser) < 4) {
        $formErrors[] = "Username must be larger than 4 characters";
      }
    }

    if (isset($_POST['password']) && isset($_POST['password2'])) {
      if (empty(sha1($_POST['password']))) {
        $formErrors[] = "Sorry passwords can't be empty";
      }

      if (sha1($_POST['password']) !== sha1($_POST['password2'])) {
        $formErrors[] = "Sorry passwords are not match";
      }
    }

    if (isset($_POST['email'])) {
      $filteredEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
      if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
        $formErrors[] = "This email is not valid";
      }
    }

    // check if there is no error, proceed the user add operation
    if (empty($formErrors)) {

      // check if user exists in database
      $check = checkCount('username', 'users', $_POST['username']);
      if ($check) {
        $theMsg       = '<div class="alert alert-danger">Sorry, this user is exists</div>';
        $formErrors[] = "Sorry, this usr is exists";
      }
      else {
        // insert user info into the database
        $stmt = $con->prepare("INSERT INTO users (username, password, email, regStatus, date)
                               VALUES (:zuser, :zpass, :zmail, 0, now())");
        $stmt->execute(
          array(
            'zuser' => $_POST['username'],
            'zpass' => sha1($_POST['password']),
            'zmail' => $_POST['email']
          )
        ); //bind parameters and execute query

        // echo success message
        $successMsg = "Congratulation, you are now registered user";

      }
    }
  }
}
?>

<div class="container login-page">
  <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span
      data-class="signup">Signup</span></h1>
  <!-- start login form -->
  <form class="login" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

    <div class="required">
      <input class="form-control" type="text" name="username" id="username" autocomplete="off"
        placeholder="Type your username" required />
    </div>

    <div class="required">
      <input class="form-control" type="password" name="password" id="password" autocomplete="new-password"
        placeholder="Type your password" required />
    </div>

    <input class="btn btn-primary d-block w-100" name="login" type="submit" value="Login" />
  </form>
  <!-- end login form -->

  <!-- start signup form -->
  <form class="signup" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <div class="required">
      <input class="form-control" type="text" name="username" id="username" autocomplete="off"
        placeholder="Type your username" pattern=".{4,}" title="Username must be more than 4 chars" required />
    </div>
    <div class="required">
      <input class="form-control" type="password" name="password" id="password" autocomplete="new-password"
        placeholder="Type a complex password" minlength="4" required />
    </div>
    <div class="required">
      <input class="form-control" type="password" name="password2" id="password2" autocomplete="new-password"
        placeholder="Type the password again" minlength="4" required />
    </div>
    <div class="required">
      <input class="form-control" type="email" name="email" id="email" autocomplete="off"
        placeholder="Type a valid email" required />
    </div>
    <input class="btn btn-success d-block w-100" name="signup" type="submit" value="Signup" />
  </form>
  <!-- end signup form -->
  <div class="errors text-center">
    <?php
    if (!empty($formErrors)) {
      foreach ($formErrors as $error) {
        echo "<div class='msg error'>$error </div>";
      }
    }

    if (isset($successMsg)) {
      echo "<div class='msg success'>$successMsg</div>";
    }
    ?>
  </div>
</div>

<?php
include "$tpl/footer.php";
ob_end_flush();
?>