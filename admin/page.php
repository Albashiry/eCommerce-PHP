<?php
/* =======================================================
 * == Template Page
 * =======================================================
 * */

ob_start(); // output buffering start
session_start();
$pageTitle = '';

if (isset($_SESSION['username'])) {
  include 'init.php';

  // split page with Get request
  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

  // if the page is main page
  if ($do == 'manage') {
    echo 'manage';

  }
  elseif ($do == 'add') {
    echo 'add';

  }
  elseif ($do == 'insert') {

  }
  elseif ($do == 'edit') {

  }
  elseif ($do == 'update') {

  }
  elseif ($do == 'delete') {

  }
  elseif ($do == 'activate') {

  }

  include "$tpl/footer.php";

}
else {
  header('Location: index.php');
  exit();
}
ob_end_flush();