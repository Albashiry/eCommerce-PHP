<?php

/* manage comments page
 * you can {edit | delete | approve} comments from here
 * */
ob_start(); // output buffreing start
session_start();
$pageTitle = 'Comments';

if (isset($_SESSION['username'])) {
  include 'init.php';

  // split page with Get request
  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

  if ($do == 'manage') {// manage members page 

    $stmt = $con->prepare("SELECT comments.*, items.name AS item_name, users.username AS member
                           FROM comments
                           INNER JOIN items ON items.itemID = comments.itemID
                           INNER JOIN users ON users.userID = comments.userID
                           ORDER BY comID DESC");
    $stmt->execute();

    $comments = $stmt->fetchAll();

    ?>

    <h1 class="text-center">Manage Comments</h1>
    <div class="container">
      <?php if (!empty($comments)) { ?>
        <div class="table-responsive text-center">
          <table class="main-table table table-bordered">
            <thead></thead>
            <tbody>
              <tr>
                <th>#ID</th>
                <th>Comment</th>
                <th>Item Name</th>
                <th>User Name</th>
                <th>Added Date</th>
                <th>Control</th>
              </tr>
              <?php
              foreach ($comments as $comment) {
                echo '<tr>';
                echo "<td>$comment[comID]</td>";
                echo "<td>$comment[comment]</td>";
                echo "<td>$comment[item_name]</td>";
                echo "<td>$comment[member]</td>";
                echo "<td>$comment[comment_date]</td>";
                echo "<td>
                      <a href='comments.php?do=edit&comID=$comment[comID]' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                      <a href='comments.php?do=delete&comID=$comment[comID]' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                if ($comment['status'] == 0) {
                  echo "<a href='comments.php?do=approve&comID=$comment[comID]' class='btn btn-info activate'>
                        <i class='fa fa-check'></i> Approve
                      </a>";
                }
                echo "</td>";
                echo '</tr>';
              }
              ?>
            </tbody>
            <tfoot></tfoot>
          </table>
        </div>
      <?php }
      else {
        echo '<div class="alert alert-info">There is no comment to show</div>';
      } ?>
    </div>


  <?php }
  elseif ($do == 'edit') { // edit comments page

    // check if Get Request ItemID is numeric and get the integer value of it
    $comID = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;

    // select all data depend on this ID
    $stmt = $con->prepare("SELECT * FROM comments WHERE comID = ?");
    $stmt->execute(array($comID)); // execute query

    // fetch the data 
    $row   = $stmt->fetch();
    $count = $stmt->rowCount();

    // if there is such ID show the form
    if ($count > 0) { ?>

      <h1 class="text-center">Edit Comment</h1>
      <div class="container">
        <form class="form-horizontal" action="comments.php?do=update" method="post">
          <input type="hidden" name="comID" value="<?= $comID ?>">
          <!-- send comID to select it in database when update -->

          <!-- start comment field -->
          <div class="mb-3 row">
            <label for="comment" class="col-sm-3 col-form-label form-control-lg">comment</label>
            <div class="col-sm-9 col-md-6 required">
              <textarea class="form-control" name="comment" id="comment" rows="15" cols="">
                                              <?= $row['comment'] ?>
                                            </textarea>
            </div>
          </div>
          <!-- end comment field -->

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

    echo '<h1 class="text-center">Update Comment</h1>';
    echo '<div class="container">';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // get the variables from the form
      $comID   = $_POST['comID'];
      $comment = $_POST['comment'];

      // update the database with this info
      $stmt = $con->prepare("UPDATE comments SET comment=? WHERE comID=?");
      $stmt->execute(array($comment, $comID));

      // echo success message
      $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated</div>';
      redirectHome($theMsg, 'back');


    }
    else {
      $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
      redirectHome($theMsg);

    }
    echo '</div>';


  }
  elseif ($do == 'delete') { // delete page

    echo '<h1 class="text-center">Delete Comment</h1>';
    echo '<div class="container">';

    // check if Get Request comID is numeric and get the integer value of it
    $comID = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;

    // check data depend on this ID
    $check = checkCount('comID', 'comments', $comID);

    // if there is such ID show the form
    if ($check > 0) {
      $stmt = $con->prepare("DELETE FROM comments WHERE comID = :zid");
      $stmt->bindParam(':zid', $comID);
      $stmt->execute();

      // echo success message
      $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record deleted</div>';
      redirectHome($theMsg, 'back');

    }
    else {
      $theMsg = '<div class="alert alert-danger">This ID is not exist</div>';
      redirectHome($theMsg);

    }
    echo '</div>';


  }
  elseif ($do == 'approve') { // activate members

    echo '<h1 class="text-center">Approve Member</h1>';
    echo '<div class="container">';

    // check if Get Request comID is numeric and get the integer value of it
    $comID = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;

    // check data depend on this ID
    $check = checkCount('comID', 'comments', $comID);

    // if there is such ID show the form
    if ($check > 0) {
      // $stmt = $con->prepare("UPDATE users SET regStatus =1 WHERE comID = :zuser");
      // $stmt->bindParam(':zuser', $comID);
      // $stmt->execute();
      $stmt = $con->prepare("UPDATE comments SET status =1 WHERE comID = ?");
      $stmt->execute(array($comID));

      // echo success message
      $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record approved</div>';
      redirectHome($theMsg, 'back', 1);

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
ob_end_flush();