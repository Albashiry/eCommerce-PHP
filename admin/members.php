<?php

/* manage memberspage
 * you can {add | edit | delete} members from here
 * */

session_start();
if (isset($_SESSION['username'])) {
  $pageTitle = 'Members';
  include 'init.php';


  // split page with Get request
  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

  if ($do == 'manage') {
    // manage page

  } elseif ($do == 'edit') {
    echo "you are in edit page, your ID is $_GET[userID]<br/>";

  }


  include "$tpl/footer.php";
} else {
  header("Location: index.php");
  exit();
}