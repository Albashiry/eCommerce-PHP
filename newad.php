<?php
ob_start();
session_start();
$pageTitle = "Create new item";

include 'init.php';

if (isset($_SESSION['user'])) {

  $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
  if ($do == 'manage') { ?>
    <h1 class="text-center">
      <?= $pageTitle ?>
    </h1>
    <div class="create-ad block">
      <div class="container">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <?= $pageTitle ?>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">

                <form class="form-horizontal main-form" action="newAd.php?do=insert" method="post"
                  enctype="multipart/form-data">
                  <!-- start name field -->
                  <div class="mb-3 row">
                    <label for="name" class="col-sm-3 col-form-label form-control-lg">Name</label>
                    <div class="col-sm-9 col-md-8 required">
                      <input pattern=".{4,}" title="This field requires at least 4 characters" type="text" name="name"
                        id="name" class="form-control form-control-lg live" required placeholder="Name of the item"
                        data-class=".live-title" />
                    </div>
                  </div>
                  <!-- end name field -->
                  <!-- start description field -->
                  <div class="mb-3 row">
                    <label for="description" class="col-sm-3 col-form-label form-control-lg">Description</label>
                    <div class="col-sm-9 col-md-8 required">
                      <input pattern=".{10,}" title="This field requires at least 10 characters" type="text"
                        name="description" id="description" class="form-control form-control-lg live" required="required"
                        placeholder="Description of the item" data-class=".live-desc" />
                    </div>
                  </div>
                  <!-- end description field -->
                  <!-- start price field -->
                  <div class="mb-3 row">
                    <label for="price" class="col-sm-3 col-form-label form-control-lg">Price</label>
                    <div class="col-sm-9 col-md-8 required">
                      <input type="text" name="price" id="price" class="form-control form-control-lg live"
                        required="required" placeholder="Price of the item" data-class=".live-price" />
                    </div>
                  </div>
                  <!-- end price field -->
                  <!-- start country field -->
                  <div class="mb-3 row">
                    <label for="country" class="col-sm-3 col-form-label form-control-lg">Country</label>
                    <div class="col-sm-9 col-md-8 required">
                      <input type="text" name="country" id="country" class="form-control form-control-lg"
                        required="required" placeholder="Country of made" />
                    </div>
                  </div>
                  <!-- end country field -->
                  <!-- start status field -->
                  <div class="mb-3 row">
                    <label for="status" class="col-sm-3 col-form-label form-control-lg">Status</label>
                    <div class="col-sm-9 col-md-8 required">
                      <select name="status" id="status" required>
                        <option value="" disabled selected></option>
                        <option value="1">New</option>
                        <option value="2">Like New</option>
                        <option value="3">Used</option>
                        <option value="4">Old</option>
                      </select>
                    </div>
                  </div>
                  <!-- end status field -->
                  <!-- start categories field -->
                  <div class="mb-3 row">
                    <label for="category" class="col-sm-3 col-form-label form-control-lg">Category</label>
                    <div class="col-sm-9 col-md-8 required">
                      <select name="category" id="category" required>
                        <option value="" disabled selected></option>
                        <?php
                        $allCats = getAllFrom('*', 'categories', "WHERE parent = 0");
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
                    <div class="col-sm-9 col-md-8 required">
                      <input type="text" name="tags" id="tags" class="form-control form-control-lg"
                        placeholder="Seperate tags with comma (,)" />
                    </div>
                  </div>
                  <!-- end tags field -->
                  <!-- start image field -->
                  <div class="mb-3 row">
                    <label for="image" class="col-sm-3 col-form-label form-control-lg">Image</label>
                    <div class="col-sm-9 col-md-8 required">
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
              <div class="col-md-4">

                <div class='card item-box live-preview'>
                  <span class='price-tag'>
                    <span class="live-price">0</span>$
                  </span>
                  <img class='card-img-top img-thumbnail live-img' src='data\uploads\items\default-item.jpg'
                    alt='User Avatar'>
                  <div class='card-body'>
                    <h3 class="live-title">[name]</h3>
                    <p class="live-desc">[description]</p>
                  </div>
                </div>

              </div>
            </div>
            <!-- start looping through errors -->
            <?php
            if (!empty($formErrors)) {
              foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
              }
            }
            if (isset($successMsg)) {
              echo "<div class='alert alert-success'>$successMsg</div>";
            }
            ?>
            <!-- end looping through errors -->
          </div>
        </div>
      </div>
    </div>
  <?php }
  elseif ($do == 'insert') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // Extract details from the uploaded file
      $imageName         = $_FILES['image']['name'];
      $imageSize         = $_FILES['image']['size'];
      $imageType         = $_FILES['image']['type'];
      $imageTemp         = $_FILES['image']['tmp_name'];
      $allowedExtensions = array('jpeg', 'jpg', 'png', 'gif');
      $imageExtension    = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));


      $name     = strip_tags($_POST['name']);
      $desc     = strip_tags($_POST['description']);
      $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
      $country  = strip_tags($_POST['country']);
      $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
      $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
      $tags     = strip_tags($_POST['tags']);

      $formErrors = array();
      if (strlen($name) < 4) {
        $formErrors[] = "Item title must be at least 4 characters";
      }
      if (strlen($desc) < 10) {
        $formErrors[] = "Item description must be at least 10 characters";
      }
      if (strlen($country) < 2) {
        $formErrors[] = "Country name must be at least 4 characters";
      }
      if (empty($price)) {
        $formErrors[] = "Item price must be not empty";
      }
      if (empty($status)) {
        $formErrors[] = "Item status must be not empty";
      }
      if (empty($category)) {
        $formErrors[] = "Item category must be not empty";
      }
      if (!empty($imageName) && !in_array($imageExtension, $allowedExtensions)) {
        $formErrors[] = 'This extension is <strong>not allowed</strong>';
      }
      if ($imageSize > 5242880) {
        $formErrors[] = 'Avatar can\'t larger than <strong>5MB</strong>';
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
        $stmt = $con->prepare("INSERT INTO items (name, description, price, country_made, status, add_date, catID, memberID, tags, image)
                                VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags, :zimage)");
        $stmt->execute(
          array(
            'zname'    => $name,
            'zdesc'    => $desc,
            'zprice'   => $price,
            'zcountry' => $country,
            'zstatus'  => $status,
            'zcat'     => $category,
            'zmember'  => $_SESSION['uid'],
            'ztags'    => $tags,
            'zimage'   => $image
          )
        ); //bind parameters and execute query

        // echo success message
        if ($stmt) {
          $successMsg = "Item has been added";
        }
      }
    }
  }
  elseif ($do == 'edit') {
    // show edit form
  }
  elseif ($do == 'update') {
    // update data
  }
  else {
    echo "<div class='container'><div class='alert alert-danger'>nothing to show here</div></div>";
  }













}
else {
  header("Location: login.php");
  exit();
}
include "$tpl/footer.php";
ob_end_flush();
?>