<?php
session_start();
$pageTitle = "Login";
if (isset($_SESSION['user'])) {
  header('Location: index.php');
}

include 'init.php';

// check if user comming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user       = isset($_POST['username']) ? $_POST['username'] : '';
  $pass       = isset($_POST['password']) ? $_POST['password'] : '';
  $hashedPass = sha1($pass);

  // check if the user exists in the database
  $stmt = $con->prepare(
    "SELECT username, password 
     FROM users 
     WHERE username = ? AND password = ?"
  );

  $stmt->execute(array($user, $hashedPass));
  $count = $stmt->rowCount();

  // if count > 0 this means that the database contain record about this username
  if ($count > 0) {
    $_SESSION['user'] = $user; // register session name
    header("Location: index.php"); // redirect to dashboard.php
    exit();
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

    <input class="btn btn-primary d-block w-100" type="submit" value="Login" />
  </form>
  <!-- end login form -->

  <!-- start signup form -->
  <form class="signup" action="">
    <input class="form-control" type="text" name="username" id="username" autocomplete="off"
      placeholder="Type your username" required="required" />

    <input class="form-control" type="password" name="password" id="password" autocomplete="new-password"
      placeholder="Type a complex password" />

    <input class="form-control" type="password" name="password2" id="password2" autocomplete="new-password"
      placeholder="Type the password again" />

    <input class="form-control" type="email" name="email" id="email" autocomplete="off"
      placeholder="Type a valid email" />

    <input class="btn btn-success d-block w-100" type="submit" value="Signup" />
  </form>
  <!-- end signup form -->
</div>

<?php include "$tpl/footer.php"; ?>