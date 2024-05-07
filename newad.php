<?php
ob_start();
session_start();
$pageTitle = "Create new item";

include 'init.php';

if (isset($_SESSION['user'])) {

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $formErrors = array();

    $name     = strip_tags($_POST['name']);
    $desc     = strip_tags($_POST['description']);
    $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $country  = strip_tags($_POST['country']);
    $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

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

    // check if there is no error, proceed the update operation
    if (empty($formErrors)) {

      // insert user info into the database
      $stmt = $con->prepare("INSERT INTO items (name, description, price, country_made, status, add_date, catID, memberID )
                              VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember)");
      $stmt->execute(
        array(
          'zname'    => $name,
          'zdesc'    => $desc,
          'zprice'   => $price,
          'zcountry' => $country,
          'zstatus'  => $status,
          'zcat'     => $category,
          'zmember'  => $_SESSION['uid']
        )
      ); //bind parameters and execute query

      // echo success message
      if ($stmt) {
        $successMsg = "Item has been added";
      }
    }
  }
  ?>

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

              <form class="form-horizontal main-form" action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
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
                      // $stmt2 = $con->prepare('SELECT * FROM categories');
                      // $stmt2->execute();
                      // $cats = $stmt2->fetchAll();
                      $cats = getAllFrom('*', 'categories');
                      foreach ($cats as $cat) {
                        echo "<option value='$cat[catID]'>$cat[name]</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <!-- end categories field -->

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
                <img class='card-img-top img-thumbnail' src='avatar.png' alt='User Avatar'>
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
else {
  header("Location: login.php");
  exit();
}
include "$tpl/footer.php";
ob_end_flush();
?>