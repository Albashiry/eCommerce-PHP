<?php
/* Get All function v3.0
 * function to get all records from any database table
 * 
 *  returns array of results
 * 
 * override getCat()
 * override getItems()
 * */
function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderField = 'NULL', $ordering = 'DESC') {
  global $con;

  $getAll = $con->prepare("SELECT * FROM $table $where $and ORDER BY $orderField $ordering");
  $getAll->execute(array());
  $all = $getAll->fetchAll();
  return $all;
}

/* Get categories function v1.0
 * function to get categories from database
 * 
 *  returns array of results
 * *
function getCat() {
  global $con;

  $getCat = $con->prepare("SELECT * FROM categories ORDER BY catID");
  $getCat->execute();
  $cats = $getCat->fetchAll();
  return $cats;
}*/


/* Get Ad items function v2.0
 * function to get Ad items from database
 * 
 *  returns array of results
 * *
function getItems($where, $value, $approve = NULL) {
  global $con;

  $sql = $approve == NULL ? "AND approve = 1" : '';
  // if ($approve == NULL) { $sql = "AND approve = 1"; } else { $sql = NULL; }

  $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY itemID DESC");
  $getItems->execute(array($value));
  $items = $getItems->fetchAll();
  return $items;
}*/


/* checkUserStatus function v1.0
 * function to check User Status in database if is not activated
 * check the regStatus of the user
 * 
 *  returns array of results
 * */
function checkUserStatus($user) {
  global $con;

  $stmtx = $con->prepare("SELECT username, regStatus 
                         FROM users 
                         WHERE username = ? AND regStatus = 0"
  );

  $stmtx->execute(array($user));
  $status = $stmtx->rowCount();
  return $status;
}

/* check items function v2.0
 * function to check item in database [function accepts parameters]
 *   $column => 
 *   $table => 
 *   $value => 
 * */
/* checkItem($column, $table, $value) + countItems($item, $table) */
function checkCount($column, $table, $value = "") {
  global $con;

  if ($value == "") {
    $stmt = $con->prepare("SELECT COUNT($column) FROM $table");
    $stmt->execute();
    return $stmt->fetchColumn();
  }
  else {
    $statement = $con->prepare("SELECT $column From $table WHERE $column = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
  }
}


/* title function v1.0
 * title function that echo the page title in case the page has the variable $pageTitle
 * and echo default title for other pages
 * */
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
 * */
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
 * */
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
