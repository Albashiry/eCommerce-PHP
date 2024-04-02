<?php
session_start();
if (isset($_SESSION['username'])) {

  $pageTitle = 'Dashboard';

  include 'init.php';


  echo '<pre>';
  print_r($_SESSION);
  echo '</pre>';


  include "$tpl/footer.php";
} else {
  header("Location: index.php");
  exit();
}