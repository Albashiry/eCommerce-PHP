<?php
// Categories page

ob_start(); // output buffering start
session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['username'])) {
  include 'init.php';

  // split page with Get request
  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

  // if the page is main page
  if ($do == 'manage') {

    $sort       = 'ASC';
    $sort_array = array('ASC', 'DESC', 'asc', 'desc');
    if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
      $sort = $_GET['sort'];
    }

    $stmt = $con->prepare("SELECT * FROM categories ORDER BY ordering $sort");
    $stmt->execute();
    $cats = $stmt->fetchAll(); ?>

    <h1 class="text-center">Manage Categories</h1>
    <div class="container categories">
      <?php if (!empty($cats)) { ?>
        <div class="card card-default mb-2">
          <div class="card-heading p-2 d-flex justify-content-between">
            <div><i class="fa fa-edit"></i> Manage Categories</div>
            <div class="option">
              <i class="fa fa-sort"></i> Ordering: [
              <a class="<?php if ($sort == 'ASC')
                echo 'active' ?>" href="categories.php?sort=ASC">ASC</a> |
                <a class="<?php if ($sort == 'DESC')
                echo 'active' ?>" href="categories.php?sort=DESC">DESC</a> ]
              </div>
              <div class="option"><i class="fa fa-eye"></i> View: [
                <span class="active" data-view="full">Full</span> |
                <span data-view="classic">Classic</span> ]
              </div>
            </div>
            <div class="card-body">
              <?php
              foreach ($cats as $cat) {
                echo '<div class="cat">';
                echo '<div class="hidden-buttons">';
                echo '<a href="categories.php?do=edit&catID=' . $cat['catID'] . '" class="btn btn-xs btn-primary"><i class="fa fa-edit"> Edit</i></a>';
                echo '<a href="categories.php?do=delete&catID=' . $cat['catID'] . '" class="confirm btn btn-xs btn-danger"><i class="fa fa-close"> Delete</i></a>';
                echo '</div>';
                echo "<h3>$cat[name]</h3>";
                echo '<div class="full-view">';
                echo "<p>";
                if ($cat['description'] == '') {
                  echo 'This category has no description';
                }
                else {
                  echo $cat['description'];
                }
                echo "</p>";
                if ($cat['visibility'] == 1) {
                  echo "<span class='visibility'><i class='fa fa-eye-slash'></i> Hidden</span>";
                }
                if ($cat['allow_comment'] == 1) {
                  echo "<span class='commenting'><i class='fa fa-comment-slash'></i> Comment Disabled</span>";
                }
                if ($cat['allow_ads'] == 1) {
                  echo "<span class='advertises'><i class='fa fa-rectangle-ad'></i> Ads Disabled</span>";
                }
                echo '</div>';
                echo '</div>';
                echo '<hr/>';
              }
              ?>
          </div>
        </div>
      <?php }
      else {
        echo '<div class="alert alert-info">There is no category to show</div>';
      } ?>
      <a class="add-category btn btn-primary mb-3" href="categories.php?do=add"><i class="fa fa-plus"></i> New Category
      </a>
    </div>

    <?php
  }
  elseif ($do == 'add') {
    ?>

    <h1 class="text-center">Add New Category</h1>
    <div class="container">
      <form class="form-horizontal" action="categories.php?do=insert" method="post">
        <!-- start name field -->
        <div class="mb-3 row">
          <label for="name" class="col-sm-3 col-form-label form-control-lg">Name</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="name" id="name" class="form-control form-control-lg" autocomplete="off"
              required="required" placeholder="Name of the category">
          </div>
        </div>
        <!-- end name field -->
        <!-- start description field -->
        <div class="mb-3 row">
          <label for="description" class="col-sm-3 col-form-label form-control-lg">Description</label>
          <div class="col-sm-9 col-md-6">
            <input type="text" name="description" id="description" class="form-control form-control-lg"
              placeholder="Describe the category">
          </div>
        </div>
        <!-- end description field -->
        <!-- start oredering field -->
        <div class="mb-3 row">
          <label for="ordering" class="col-sm-3 col-form-label form-control-lg">Ordering</label>
          <div class="col-sm-9 col-md-6">
            <input type="text" name="ordering" id="ordering" class="form-control form-control-lg"
              placeholder="Number to arrange the categories">
          </div>
        </div>
        <!-- end oredering field -->
        <!-- start visibility field -->
        <div class="mb-3 row">
          <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Visible</label>
          <div class="col-sm-9 col-md-6">
            <div>
              <input type="radio" name="visibility" id="vis-yes" value="0" checked />
              <label for="vis-yes">Yes</label>
            </div>
            <div>
              <input type="radio" name="visibility" id="vis-no" value="1" />
              <label for="vis-no">No</label>
            </div>
          </div>
        </div>
        <!-- end visibility field -->
        <!-- start commenting field -->
        <div class="mb-3 row">
          <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Allow Commenting</label>
          <div class="col-sm-9 col-md-6">
            <div>
              <input type="radio" name="commenting" id="com-yes" value="0" checked />
              <label for="com-yes">Yes</label>
            </div>
            <div>
              <input type="radio" name="commenting" id="com-no" value="1" />
              <label for="com-no">No</label>
            </div>
          </div>
        </div>
        <!-- end commenting field -->
        <!-- start ads field -->
        <div class="mb-3 row">
          <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Allow Advertise</label>
          <div class="col-sm-9 col-md-6">
            <div>
              <input type="radio" name="ads" id="ads-yes" value="0" checked />
              <label for="ads-yes">Yes</label>
            </div>
            <div>
              <input type="radio" name="ads" id="ads-no" value="1" />
              <label for="ads-no">No</label>
            </div>
          </div>
        </div>
        <!-- end ads field -->
        <!-- start submit field -->
        <div class="mb-3 row">
          <div class="offset-sm-3 col-sm-9">
            <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
          </div>
        </div>
        <!-- end submit field -->
      </form>
    </div>

    <?php
  }
  elseif ($do == 'insert') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      echo '<h1 class="text-center">Update Category</h1>';
      echo '<div class="container">';

      // get the variables from the form
      $name    = $_POST['name'];
      $desc    = $_POST['description'];
      $order   = $_POST['ordering'];
      $visible = $_POST['visibility'];
      $comment = $_POST['commenting'];
      $ads     = $_POST['ads'];

      // check if category exists in database
      $check = checkCount('name', 'categories', $name);

      if ($check) {
        $theMsg = '<div class="alert alert-danger">Sorry, this category is exists</div>';
        redirectHome($theMsg, 'back');

      }
      else {
        // insert category info into the database
        $stmt = $con->prepare("INSERT INTO categories (name, description, ordering, visibility, allow_comment, allow_ads)
                                VALUES (:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)");
        $stmt->execute(
          array(
            'zname'    => $name,
            'zdesc'    => $desc,
            'zorder'   => $order,
            'zvisible' => $visible,
            'zcomment' => $comment,
            'zads'     => $ads,
          )
        ); //bind parameters and execute query

        // echo success message
        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted</div>';
        redirectHome($theMsg, 'back');
      }
    }
    else {
      echo '<div class="container">';
      $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
      redirectHome($theMsg, 'back');
    }
    echo '</div>';

  }
  elseif ($do == 'edit') {

    echo '<h1 class="text-center">Edit Category</h1>';
    echo '<div class="container">';

    // check if Get Request catID is numeric and get the integer value of it
    $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? intval($_GET['catID']) : 0;

    // select all data depend on this ID
    $stmt = $con->prepare("SELECT * FROM categories WHERE catID = ?");
    $stmt->execute(array($catID)); // execute query

    // fetch the data 
    $cat   = $stmt->fetch();
    $count = $stmt->rowCount();

    // if there is such ID show the form
    if ($count > 0) { ?>

      <form class="form-horizontal" action="categories.php?do=update" method="post">
        <input type="hidden" name="catID" value="<?= $catID ?>">
        <!-- send catID to select it in database when update -->

        <!-- start name field -->
        <div class="mb-3 row">
          <label for="name" class="col-sm-3 col-form-label form-control-lg">Name</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="name" id="name" class="form-control form-control-lg" required="required"
              placeholder="Name of the category" value="<?php echo $cat['name']; ?>">
          </div>
        </div>
        <!-- end name field -->
        <!-- start description field -->
        <div class="mb-3 row">
          <label for="description" class="col-sm-3 col-form-label form-control-lg">Description</label>
          <div class="col-sm-9 col-md-6">
            <input type="text" name="description" id="description" class="form-control form-control-lg"
              placeholder="Describe the category" value="<?php echo $cat['description']; ?>">
          </div>
        </div>
        <!-- end description field -->
        <!-- start oredering field -->
        <div class="mb-3 row">
          <label for="ordering" class="col-sm-3 col-form-label form-control-lg">Ordering</label>
          <div class="col-sm-9 col-md-6">
            <input type="text" name="ordering" id="ordering" class="form-control form-control-lg"
              placeholder="Number to arrange the categories" value="<?php echo $cat['ordering']; ?>">
          </div>
        </div>
        <!-- end oredering field -->
        <!-- start visibility field -->
        <div class="mb-3 row">
          <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Visible</label>
          <div class="col-sm-9 col-md-6">
            <div>
              <input type="radio" name="visibility" id="vis-yes" value="0" <?php if ($cat['visibility'] == 0) {
                echo 'checked';
              } ?> />
              <label for="vis-yes">Yes</label>
            </div>
            <div>
              <input type="radio" name="visibility" id="vis-no" value="1" <?php if ($cat['visibility'] == 1) {
                echo 'checked';
              } ?> />
              <label for="vis-no">No</label>
            </div>
          </div>
        </div>
        <!-- end visibility field -->
        <!-- start commenting field -->
        <div class="mb-3 row">
          <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Allow Commenting</label>
          <div class="col-sm-9 col-md-6">
            <div>
              <input type="radio" name="commenting" id="com-yes" value="0" <?php if ($cat['allow_comment'] == 0) {
                echo 'checked';
              } ?> />
              <label for="com-yes">Yes</label>
            </div>
            <div>
              <input type="radio" name="commenting" id="com-no" value="1" <?php if ($cat['allow_comment'] == 1) {
                echo 'checked';
              } ?> />
              <label for="com-no">No</label>
            </div>
          </div>
        </div>
        <!-- end commenting field -->
        <!-- start ads field -->
        <div class="mb-3 row">
          <label for="fullname" class="col-sm-3 col-form-label form-control-lg">Allow Advertise</label>
          <div class="col-sm-9 col-md-6">
            <div>
              <input type="radio" name="ads" id="ads-yes" value="0" <?php if ($cat['allow_ads'] == 0) {
                echo 'checked';
              } ?> />
              <label for="ads-yes">Yes</label>
            </div>
            <div>
              <input type="radio" name="ads" id="ads-no" value="1" <?php if ($cat['allow_ads'] == 1) {
                echo 'checked';
              } ?> />
              <label for="ads-no">No</label>
            </div>
          </div>
        </div>
        <!-- end ads field -->
        <!-- start submit field -->
        <div class="mb-3 row">
          <div class="offset-sm-3 col-sm-9">
            <input type="submit" value="Save" class="btn btn-primary btn-lg">
          </div>
        </div>
        <!-- end submit field -->
      </form>

    <?Php }
    else { // if there is no such ID show error message
      $theMsg = '<div class="alert alert-danger">There is no such id</div>';
      redirectHome($theMsg);

    }
    echo '</div>';
  }
  elseif ($do == 'update') {

    echo '<h1 class="text-center">Update Category</h1>';
    echo '<div class="container">';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // get the variables from the form
      $catID   = $_POST['catID'];
      $name    = $_POST['name'];
      $desc    = $_POST['description'];
      $order   = $_POST['ordering'];
      $visible = $_POST['visibility'];
      $comment = $_POST['commenting'];
      $ads     = $_POST['ads'];

      // update the database with this info
      $stmt = $con->prepare("UPDATE categories 
                             SET name=?, 
                                 description=?, 
                                 ordering=?, 
                                 visibility=?, 
                                 allow_comment=?, 
                                 allow_ads=? 
                             WHERE catID=?");
      $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $catID));

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
  elseif ($do == 'delete') {

    echo '<h1 class="text-center">Delete Category</h1>';
    echo '<div class="container">';

    // check if Get Request userID is numeric and get the integer value of it
    $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? intval($_GET['catID']) : 0;

    // check data depend on this ID
    $check = checkCount('catID', 'categories', $catID);

    // if there is such ID show the form
    if ($check > 0) {
      $stmt = $con->prepare("DELETE FROM categories WHERE catID = :zid");
      $stmt->bindParam(':zid', $catID);
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

  include "$tpl/footer.php";

}
else {
  header('Location: index.php');
  exit();
}
ob_end_flush();