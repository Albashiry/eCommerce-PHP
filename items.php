<?php
ob_start();
session_start();
$pageTitle = "Show item";

include 'init.php';

// check if Get Request itemID is numeric and get the integer value of it
$itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;

// select all data depend on this ID
$stmt = $con->prepare("SELECT items.*, categories.name AS category_name, users.username
                        FROM items
                        INNER JOIN categories ON categories.catID = items.catID
                        INNER JOIN users ON users.userID = items.memberID
                        WHERE itemID = ? AND approve = 1");
$stmt->execute(array($itemID)); // execute query

if ($stmt->rowCount()) {
  // fetch the data 
  $item = $stmt->fetch();
  ?>

  <h1 class="text-center">
    <?= $item['name'] ?>
  </h1>
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <img class='img-thumbnail m-auto' src='avatar.png' alt='User Avatar'>
      </div>
      <div class="col-md-9 item-info">
        <h2>
          <?= $item['name'] ?>
        </h2>
        <p>
          <?= $item['description'] ?>
        </p>

        <ul class="list-unstyled">
          <li>
            <i class="fa fa-calendar fa-fw"></i>
            <span>Added date</span>:
            <?= $item['add_date'] ?>
          </li>
          <li>
            <i class="fa fa-money-bill fa-fw"></i>
            <span>Price</span>:
            <?= $item['price'] ?>$
          </li>
          <li>
            <i class="fa fa-building fa-fw"></i>
            <span>Made in</span>:
            <?= $item['country_made'] ?>
          </li>
          <li>
            <i class="fa fa-tags fa-fw"></i>
            <span>Category</span>:
            <a href="categories.php?pageID=<?= $item['catID'] ?>">
              <?= $item['category_name'] ?>
            </a>
          </li>
          <li>
            <i class="fa fa-user fa-fw"></i>
            <span>Added by</span>:
            <a href="#">
              <?= $item['username'] ?>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <hr class="custom-hr">
    <?php if (isset($_SESSION['user'])) { ?>
      <!-- start add comment -->
      <div class="row">
        <div class="col-md-9 offset-md-3">
          <div class="comment">
            <h3>Add your comment</h3>
            <form action="<?= $_SERVER['PHP_SELF'] . "?itemID=" . $item['itemID'] ?>" method="POST">
              <textarea name="comment"></textarea>
              <input class="btn btn-primary" type="submit" value="Add Comment">
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
              // $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
              $comment = strip_tags($_POST['comment']);
              $itemID  = $item['itemID'];
              $userID  = $_SESSION['uid'];

              if (!empty($comment)) {
                $stmt = $con->prepare("INSERT INTO comments(comment, status, comment_date, itemID, userID) 
                                        VALUES(:zcomment, 0, now(), :zitemID, :zuserID)");
                $stmt->execute(array('zcomment' => $comment, 'zitemID' => $itemID, 'zuserID' => $userID));

                if ($stmt) {
                  echo "<div class='alert alert-success'>Comment Added!</div>";
                }
              }
            }
            ?>
          </div>
        </div>
      </div>
      <!-- end add comment -->
    <?php }
    else {
      echo "<a href='login.php'>Login</a> or <a href='login.php'>Register</a> to add comments";
    } ?>

    <hr class="custom-hr">
    <?php
    $stmt = $con->prepare("SELECT comments.*, users.username AS member
                        FROM comments
                        INNER JOIN users ON users.userID = comments.userID
                        WHERE itemID = ? AND status = 1
                        ORDER BY comID DESC");
    $stmt->execute(array($item['itemID']));
    $comments = $stmt->fetchAll();

    foreach ($comments as $com) {
      echo "
      <div class='comment-box'>
        <div class='row'>
          <div class='col-sm-2 text-center'>
            <img class='img-thumbnail m-auto rounded-circle' src='avatar.png' alt='User Avatar'>
            $com[member]
          </div>
          <div class='col-sm-10'>
            <p class='lead'>$com[comment]</p>
          </div>
        </div>
      </div>";
    }
    ?>
  </div>

  <?php
}
else {
  echo "
  <div class='container'>
    <div class='alert alert-danger'>There is no such ID, or this item is waiting approval!</div>
  </div>";
}
include "$tpl/footer.php";
ob_end_flush();
?>