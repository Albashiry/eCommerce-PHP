<?php
// Categories => [Manage | Edit | Update | Add | Insert | Delete | Statistics]

// split page with Get request
$do = isset($_GET['do']) ? $_GET['do'] : 'manage';

// if the page is main page
if ($do == 'manage') {

  echo 'you are in manage category page <br/>';
  echo '<a href="page.php?do=add">Add New Category +</a>';

} elseif ($do == 'add') {

  echo 'you are in add category page<br/>';

} elseif ($do == 'insert') {

  echo 'you are in insert category page<br/>';

} else {
  echo 'Error! there is no page with this name';
}