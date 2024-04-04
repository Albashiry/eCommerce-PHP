<?php

/* title function v1.0
 * title function that echo the page title in case the page has the variable $pageTitle
 * and echo default title for other pages
 */
function getTitle() {
  global $pageTitle;

  if (isset($pageTitle)) {
    return $pageTitle;
  }
  else {
    return 'Default';
  }
}


/* Home Redirect function v1.0
 * This function accept parameters:
 *   $errorMsg => echo the error message
 *   $seconds => seconds before redirecting
 */
// function redirectHome($errorMsg, $seconds = 3) {
//   echo "<div class='alert alert-danger'>$errorMsg</div>";
//   echo "<div class='alert alert-info'>You will be redirected to home page after $seconds seconds.</div>";
//   header("refresh:$seconds;url=index.php"); // asynchronous code
//   exit();
// }

/* Home Redirect function v2.0
 * This function accept parameters:
 *   $theMsg => echo the message [error | success | warning]
 *   $url => the link we redirect to it
 *   $seconds => seconds before redirecting
 */
function redirectHome($theMsg, $url = null, $seconds = 3) {
  $link = 'Homepage';

  // go back to the previous page
  if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
    $url  = $_SERVER['HTTP_REFERER'];
    $link = 'Previous Page';
  }
  else {
    $url = 'index.php';
  }

  echo $theMsg;
  echo "<div class='alert alert-info'>You will be redirected to $link after $seconds seconds.</div>";

  header("Refresh: $seconds; url=$url");

  exit();
}


/* check items function v1.0
 * function to check item in database [function accepts parameters]
 *   $select => the item to select [example: user, item, category]
 *   $from => the table to select from [example: users, items, categories]
 *   $value => the value of select [example: Betho, box, electronics]
 */
function checkItem($column, $table, $value) {
  global $con;

  $statement = $con->prepare("SELECT $column From $table WHERE $column = ?");
  $statement->execute(array($value));
  $count = $statement->rowCount();
  return $count;
}