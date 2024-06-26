<?php
/* =======================================================
 * == Items Page
 * =======================================================
 * */

ob_start(); // output buffering start
session_start();
$pageTitle = 'Items';

if (isset($_SESSION['username'])) {
  include 'init.php';

  // split page with Get request
  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

  // if the page is main page
  if ($do == 'manage') {

    $stmt = $con->prepare("SELECT items.*, categories.name AS catName, users.username 
                           FROM items
                           INNER JOIN categories ON categories.catID = items.catID
                           INNER JOIN users      ON users.userID = items.memberID
                           ORDER BY itemID DESC");
    $stmt->execute();

    $items = $stmt->fetchAll();

    ?>

    <h1 class="text-center">Manage Items</h1>
    <div class="container">
      <?php if (!empty($items)) { ?>
        <div class="table-responsive text-center">
          <table class="main-table table table-bordered">
            <thead></thead>
            <tbody>
              <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Adding Date</th>
                <th>Category</th>
                <th>Username</th>
                <th>Control</th>
              </tr>
              <?php
              foreach ($items as $item) {
                echo '<tr>';
                echo "<td>$item[itemID]</td>";
                echo "<td>$item[name]</td>";
                echo "<td>$item[description]</td>";
                echo "<td>$item[price]</td>";
                echo "<td>$item[add_date]</td>";
                echo "<td>$item[catName]</td>";
                echo "<td>$item[username]</td>";
                echo "<td class='control'>";
                if ($item['approve'] == 0) {
                  echo "<a href='items.php?do=approve&itemID=$item[itemID]' class='btn btn-info activate'> <i class='fa fa-check'></i> Approve</a>";
                }
                echo "<a href='items.php?do=edit&itemID=$item[itemID]' class='btn btn-success'> <i class='fa fa-edit'></i> Edit</a>
                      <a href='items.php?do=delete&itemID=$item[itemID]' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete</a>";
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
        echo '<div class="alert alert-info">There is no item to show</div>';
      } ?>
      <a href="items.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New item</a>
    </div>

  <?php }
  elseif ($do == 'add') {
    ?>

    <h1 class="text-center">Add New Item</h1>
    <div class="container">
      <form class="form-horizontal" action="items.php?do=insert" method="post" enctype="multipart/form-data">
        <!-- start name field -->
        <div class="mb-3 row">
          <label for="name" class="col-sm-3 col-form-label form-control-lg">Name</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="name" id="name" class="form-control form-control-lg" required="required"
              placeholder="Name of the item" />
          </div>
        </div>
        <!-- end name field -->
        <!-- start description field -->
        <div class="mb-3 row">
          <label for="description" class="col-sm-3 col-form-label form-control-lg">Description</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="description" id="description" class="form-control form-control-lg" required="required"
              placeholder="Description of the item" />
          </div>
        </div>
        <!-- end description field -->
        <!-- start price field -->
        <div class="mb-3 row">
          <label for="price" class="col-sm-3 col-form-label form-control-lg">Price</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="price" id="price" class="form-control form-control-lg" required="required"
              placeholder="Price of the item" />
          </div>
        </div>
        <!-- end price field -->
        <!-- start country field -->
        <div class="mb-3 row">
          <label for="country" class="col-sm-3 col-form-label form-control-lg">Country</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="country" id="country" class="form-control form-control-lg" required="required"
              placeholder="Country of made" />
          </div>
        </div>
        <!-- end country field -->
        <!-- start status field -->
        <div class="mb-3 row">
          <label for="status" class="col-sm-3 col-form-label form-control-lg">Status</label>
          <div class="col-sm-9 col-md-6 required">
            <select name="status" id="status">
              <option value="0" disabled selected></option>
              <option value="1">New</option>
              <option value="2">Like New</option>
              <option value="3">Used</option>
              <option value="4">Old</option>
            </select>
          </div>
        </div>
        <!-- end status field -->
        <!-- start members field -->
        <div class="mb-3 row">
          <label for="member" class="col-sm-3 col-form-label form-control-lg">Member</label>
          <div class="col-sm-9 col-md-6 required">
            <select name="member" id="member">
              <option value="0" disabled selected></option>
              <?php
              $allMembers = getAllFrom('*', 'users', '', '', 'userID');
              foreach ($allMembers as $user) {
                echo "<option value='$user[userID]'>$user[username]</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <!-- end members field -->
        <!-- start categories field -->
        <div class="mb-3 row">
          <label for="category" class="col-sm-3 col-form-label form-control-lg">Category</label>
          <div class="col-sm-9 col-md-6 required">
            <select name="category" id="category">
              <option value="0" disabled selected></option>
              <?php
              $allCats = getAllFrom('*', 'categories', 'WHERE parent = 0', '', 'catID');
              foreach ($allCats as $cat) {
                echo "<option value='$cat[catID]'>$cat[name]</option>";
                $childCats = getAllFrom('*', 'categories', "WHERE parent = $cat[catID]", '', 'catID');
                foreach ($childCats as $child) {
                  echo "<option value='$child[catID]'> --- $child[name]</option>";
                }
              }
              ?>
            </select>
          </div>
        </div>
        <!-- end categories field -->
        <!-- start tags field -->
        <div class="mb-3 row">
          <label for="tags" class="col-sm-3 col-form-label form-control-lg">Tags</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="text" name="tags" id="tags" class="form-control form-control-lg"
              placeholder="Seperate tags with comma (,)" />
          </div>
        </div>
        <!-- end tags field -->
        <!-- start image field -->
        <div class="mb-3 row">
          <label for="image" class="col-sm-3 col-form-label form-control-lg">Image</label>
          <div class="col-sm-9 col-md-6 required">
            <input type="file" name="image" id="image" class="form-control form-control-lg" />
          </div>
        </div>
        <!-- end image field -->
        <!-- start submit field -->
        <div class="mb-3 row">
          <div class="offset-sm-3 col-sm-9">
            <input type="submit" value="Add Item" class="btn btn-primary btn-md">
          </div>
        </div>
        <!-- end submit field -->
      </form>
    </div>

    <?php

  }
  elseif ($do == 'insert') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      echo '<h1 class="text-center">Insert Item</h1>';
      echo '<div class="container">';

      // Extract details from the uploaded file
      $imageName         = $_FILES['image']['name'];
      $imageSize         = $_FILES['image']['size'];
      $imageType         = $_FILES['image']['type'];
      $imageTemp         = $_FILES['image']['tmp_name'];
      $allowedExtensions = array('jpeg', 'jpg', 'png', 'gif');
      $imageExtension    = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

      // get the variables from the form
      $name     = $_POST['name'];
      $desc     = $_POST['description'];
      $price    = $_POST['price'];
      $country  = $_POST['country'];
      $status   = $_POST['status'];
      $member   = $_POST['member'];
      $category = $_POST['category'];
      $tags     = $_POST['tags'];

      // validate the form
      $formErrors = array();
      if (empty($name)) {
        $formErrors[] = 'Name can\'t be <strong>empty</strong>';
      }
      if (empty($desc)) {
        $formErrors[] = 'Description can\'t be <strong>empty</strong>';
      }
      if (empty($price)) {
        $formErrors[] = 'Price can\'t be <strong>empty</strong>';
      }
      if (empty($country)) {
        $formErrors[] = 'Country can\'t be <strong>empty</strong>';
      }
      if ($status == 0) {
        $formErrors[] = 'You must choose the <strong>status</strong>';
      }
      if ($member == 0) {
        $formErrors[] = 'You must choose the <strong>member</strong>';
      }
      if ($category == 0) {
        $formErrors[] = 'You must choose the <strong>category</strong>';
      }
      if (!empty($imageName) && !in_array($imageExtension, $allowedExtensions)) {
        $formErrors[] = 'This extension is <strong>not allowed</strong>';
      }
      if ($imageSize > 5242880) {
        $formErrors[] = 'Avatar can\'t larger than <strong>5MB</strong>';
      }
      if (!empty($avatarName)) {
        $image = rand(0, 99999999999) . '_' . $imageName;
        move_uploaded_file($imageTemp, "data\uploads\items\\$image");
      }
      else {
        $image = 'default-item.jpg';
      }
      foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
      }

      // check if there is no error, proceed the update operation
      if (empty($formErrors)) {
        if (!empty($avatarName)) {
          $image = rand(0, 99999999999) . '_' . $imageName;
          move_uploaded_file($imageTemp, "data\uploads\items\\$image");
        }
        else {
          $image = 'default-item.jpg';
        }
        // insert user info into the database
        $stmt = $con->prepare("INSERT INTO items (name, description, price, country_made, status, add_date, catID, memberID, tags, image )
                              VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags, :zimage)");
        $stmt->execute(
          array(
            'zname'    => $name,
            'zdesc'    => $desc,
            'zprice'   => $price,
            'zcountry' => $country,
            'zstatus'  => $status,
            'zcat'     => $category,
            'zmember'  => $member,
            'ztags'    => $tags,
            'zimage'   => $image
          )
        ); //bind parameters and execute query

        // echo success message
        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted</div>';
        redirectHome($theMsg);

      }
    }
    else {
      echo '<div class="container">';
      $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
      redirectHome($theMsg);

    }
    echo '</div>';


  }
  elseif ($do == 'edit') {

    // check if Get Request itemID is numeric and get the integer value of it
    $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;

    // select all data depend on this ID
    $stmt = $con->prepare("SELECT * FROM items WHERE itemID = ?");
    $stmt->execute(array($itemID)); // execute query

    // fetch the data 
    $item  = $stmt->fetch();
    $count = $stmt->rowCount();

    // if there is such ID show the form
    if ($count > 0) { ?>

      <h1 class="text-center">Edit item</h1>
      <div class="container">
        <form class="form-horizontal" action="items.php?do=update" method="post" enctype="multipart/form-data">
          <input type="hidden" name="itemID" value="<?= $itemID ?>">
          <!-- send itemID to select it in database when update -->

          <!-- start name field -->
          <div class="mb-3 row">
            <label for="name" class="col-sm-3 col-form-label form-control-lg">Name</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="text" name="name" id="name" class="form-control form-control-lg" required="required"
                placeholder="Name of the item" value="<?= $item['name'] ?>" />
            </div>
          </div>
          <!-- end name field -->
          <!-- start description field -->
          <div class="mb-3 row">
            <label for="description" class="col-sm-3 col-form-label form-control-lg">Description</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="text" name="description" id="description" class="form-control form-control-lg" required="required"
                placeholder="Description of the item" value="<?= $item['description'] ?>" />
            </div>
          </div>
          <!-- end description field -->
          <!-- start price field -->
          <div class="mb-3 row">
            <label for="price" class="col-sm-3 col-form-label form-control-lg">Price</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="text" name="price" id="price" class="form-control form-control-lg" required="required"
                placeholder="Price of the item" value="<?= $item['price'] ?>" />
            </div>
          </div>
          <!-- end price field -->
          <!-- start country field -->
          <div class="mb-3 row">
            <label for="country" class="col-sm-3 col-form-label form-control-lg">Country</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="text" name="country" id="country" class="form-control form-control-lg" required="required"
                placeholder="Country of made" value="<?= $item['country_made'] ?>" />
            </div>
          </div>
          <!-- end country field -->
          <!-- start status field -->
          <div class="mb-3 row">
            <label for="status" class="col-sm-3 col-form-label form-control-lg">Status</label>
            <div class="col-sm-9 col-md-6 required">
              <select name="status" id="status">
                <option value="1" <?php if ($item['status'] == 1) {
                  echo 'selected';
                } ?>>New</option>
                <option value="2" <?php if ($item['status'] == 2) {
                  echo 'selected';
                } ?>>Like New</option>
                <option value="3" <?php if ($item['status'] == 3) {
                  echo 'selected';
                } ?>>Used</option>
                <option value="4" <?php if ($item['status'] == 4) {
                  echo 'selected';
                } ?>>Old</option>
              </select>
            </div>
          </div>
          <!-- end status field -->
          <!-- start members field -->
          <div class="mb-3 row">
            <label for="member" class="col-sm-3 col-form-label form-control-lg">Member</label>
            <div class="col-sm-9 col-md-6 required">
              <select name="member" id="member">
                <?php
                $allUsers = getAllFrom('*', 'users', '', '', 'userID');
                foreach ($allUsers as $user) {
                  echo "<option value='$user[userID]'";
                  if ($item['memberID'] == $user['userID']) {
                    echo 'selected';
                  }
                  echo ">$user[username]</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- end members field -->
          <!-- start categories field -->
          <div class="mb-3 row">
            <label for="category" class="col-sm-3 col-form-label form-control-lg">Category</label>
            <div class="col-sm-9 col-md-6 required">
              <select name="category" id="category">
                <?php
                $allCats = getAllFrom('*', 'categories', 'WHERE parent = 0', '', 'catID');
                foreach ($allCats as $cat) {
                  echo "<option value='$cat[catID]'";
                  if ($item['catID'] == $cat['catID']) {
                    echo 'selected';
                  }
                  echo ">$cat[name]</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- end categories field -->
          <!-- start tags field -->
          <div class="mb-3 row">
            <label for="tags" class="col-sm-3 col-form-label form-control-lg">Tags</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="text" name="tags" id="tags" class="form-control form-control-lg"
                placeholder="Seperate tags with comma (,)" value="<?= $item['tags'] ?>" />
            </div>
          </div>
          <!-- end tags field -->
          <!-- start image field -->
          <div class="mb-3 row">
            <label for="image" class="col-sm-3 col-form-label form-control-lg">Image</label>
            <div class="col-sm-9 col-md-6 required">
              <input type="hidden" name="oldimage" id="oldImage" value="<?= $item['image'] ?>">
              <input type="file" name="image" id="image" class="form-control form-control-lg" />
            </div>
          </div>
          <!-- end image field -->
          <!-- start submit field -->
          <div class="mb-3 row">
            <div class="offset-sm-3 col-sm-9">
              <input type="submit" value="Update Item" class="btn btn-primary btn-md">
            </div>
          </div>
          <!-- end submit field -->
        </form>

        <?php
        // show item comments
        $stmt = $con->prepare("SELECT comments.*, users.username AS member
        FROM comments
        INNER JOIN users ON users.userID = comments.userID
        WHERE itemID = ?");
        $stmt->execute(array($itemID));

        $rows = $stmt->fetchAll();

        if (!empty($rows)) {
          ?>

          <h1 class="text-center">Manage [
            <?= $item['name'] ?>] Comments
          </h1>

          <div class="table-responsive text-center">
            <table class="main-table table table-bordered">
              <thead></thead>
              <tbody>
                <tr>
                  <th>Comment</th>
                  <th>User Name</th>
                  <th>Added Date</th>
                  <th>Control</th>
                </tr>
                <?php
                foreach ($rows as $row) {
                  echo '<tr>';
                  echo "<td>$row[comment]</td>";
                  echo "<td>$row[member]</td>";
                  echo "<td>$row[comment_date]</td>";
                  echo "<td>
                      <a href='comments.php?do=edit&comID=$row[comID]' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                      <a href='comments.php?do=delete&comID=$row[comID]' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                  if ($row['status'] == 0) {
                    echo "<a href='comments.php?do=approve&comID=$row[comID]' class='btn btn-info activate'>
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
        <?php } ?>
      </div>

    <?Php }
    else { // if there is no such ID show error message
      echo '<div class="container">';
      $theMsg = '<div class="alert alert-danger">There is no such id</div>';
      redirectHome($theMsg);
      echo '</div>';

    }

  }
  elseif ($do == 'update') {

    echo '<h1 class="text-center">Update item</h1>';
    echo '<div class="container">';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Extract details from the uploaded file
      $imageName = $_FILES['image']['name'];
      $imageSize = $_FILES['image']['size'];
      $imageType = $_FILES['image']['type'];
      $imageTemp = $_FILES['image']['tmp_name'];

      // Allowed file type list
      $allowedExtensions = array('jpeg', 'jpg', 'png', 'gif');

      // Extract extension
      $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

      // get the variables from the form
      $itemID   = $_POST['itemID'];
      $name     = $_POST['name'];
      $desc     = $_POST['description'];
      $price    = $_POST['price'];
      $country  = $_POST['country'];
      $status   = $_POST['status'];
      $category = $_POST['category'];
      $member   = $_POST['member'];
      $tags     = $_POST['tags'];
      $image    = isset($_POST['oldImage']) ? $_POST['oldImage'] : '';


      // validate the form
      $formErrors = array();
      if (empty($name)) {
        $formErrors[] = 'Name can\'t be <strong>empty</strong>';
      }
      if (empty($desc)) {
        $formErrors[] = 'Description can\'t be <strong>empty</strong>';
      }
      if (empty($price)) {
        $formErrors[] = 'Price can\'t be <strong>empty</strong>';
      }
      if (empty($country)) {
        $formErrors[] = 'Country can\'t be <strong>empty</strong>';
      }
      if ($status == 0) {
        $formErrors[] = 'You must choose the <strong>status</strong>';
        if ($category == 0) {
          $formErrors[] = 'You must choose the <strong>category</strong>';
        }
      }
      if ($member == 0) {
        $formErrors[] = 'You must choose the <strong>member</strong>';
      }
      if (!empty($imageName) && !in_array($imageExtension, $allowedExtensions)) {
        $formErrors[] = 'This extension is <strong>not allowed</strong>';
      }
      if ($imageSize > 5242880) {
        $formErrors[] = 'image can\'t be larger than <strong>5MB</strong>';
      }
      foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
      }

      // check if there is no error, proceed the update operation
      if (empty($formErrors)) {
        if (!empty($imageName)) {
          $image = rand(0, 99999999999) . '_' . $imageName;
          move_uploaded_file($imageTemp, "..\data\uploads\items\\$image");
        }
        // update the database with this info
        $stmt = $con->prepare("UPDATE items 
                               SET name=?, description=?, price=?, country_made=?, status=?, catID=?, memberID=?, tags=?, image=?
                               WHERE itemID=?");
        $stmt->execute(array($name, $desc, $price, $country, $status, $category, $member, $tags, $image, $itemID));

        // echo success message
        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated</div>';
        redirectHome($theMsg, 'back');

      }
    }
    else {
      $theMsg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly!</div>';
      redirectHome($theMsg);

    }
    echo '</div>';

  }
  elseif ($do == 'delete') {

    echo '<h1 class="text-center">Delete Member</h1>';
    echo '<div class="container">';

    // check if Get Request itemID is numeric and get the integer value of it
    $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;

    // check data depend on this ID
    $check = checkCount('itemID', 'items', $itemID);

    // if there is such ID show the form
    if ($check > 0) {
      $stmt = $con->prepare("DELETE FROM items WHERE itemID = :zitem");
      $stmt->bindParam(':zitem', $itemID);
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
  elseif ($do == 'approve') {

    echo '<h1 class="text-center">Approve item</h1>';
    echo '<div class="container">';

    // check if Get Request itemID is numeric and get the integer value of it
    $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;

    // check data depend on this ID
    $check = checkCount('itemID', 'items', $itemID);

    // if there is such ID show the form
    if ($check > 0) {
      // $stmt = $con->prepare("UPDATE users SET regStatus =1 WHERE itemID = :zuser");
      // $stmt->bindParam(':zuser', $itemID);
      // $stmt->execute();
      $stmt = $con->prepare("UPDATE items SET approve =1 WHERE itemID = ?");
      $stmt->execute(array($itemID));

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
  header('Location: index.php');
  exit();
}
ob_end_flush();