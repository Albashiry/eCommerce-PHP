<?php
session_start();
$noNavbar = '';
$pageTitle = 'Login';

if (isset($_SESSION['username'])) {
  header("Location: dashboard.php"); // redirect to dashboard.php
}
include 'init.php';


// check if user comming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = isset($_POST['user']) ? $_POST['user'] : '';
  $password = isset($_POST['pass']) ? $_POST['pass'] : '';
  $hashedPassword = sha1($password);

  // check if the user exists in the database
  $stmt = $con->prepare(
    "SELECT userID, username, password 
    FROM users 
    WHERE username = ? AND password = ? AND groupID = 1
    LIMIT 1"
  );

  $stmt->execute(array($username, $hashedPassword));
  $row = $stmt->fetch();
  $count = $stmt->rowCount();

  // if count > 0 this means that the database contain record about this username
  if ($count > 0) {
    $_SESSION['username'] = $username; // register session name
    $_SESSION['id'] = $row['userID'];  // register session ID
    header("Location: dashboard.php"); // redirect to dashboard.php
    exit();
  }
}
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
  <h4 class="text-center">Admin Login</h4>
  <input class="form-control" type="text" name="user" id="user" placeholder="Username" autocomplete="off" />
  <input class="form-control" type="password" name="pass" id="pass" placeholder="Password"
    autocomplete="new-password" />
  <input class="btn btn-primary d-block w-100" type="submit" value="Login" />
</form>

<?php include "$tpl/footer.php"; ?>