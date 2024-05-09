<?php
ob_start();
session_start();
$pageTitle = "Categories";
include 'init.php';
?>

<div class="container">
  <div class="row">
    <?php
    // $category = isset($_GET['pageID']) && is_numeric($_GET['pageID']) ? intval($_GET['pageID']) : 0;
    if (isset($_GET['name'])) {
      $tag = $_GET['name'];

      echo "<h1 class='text-center'>$tag</h1>";
      // $allItems = getAllFrom('*', 'items', "WHERE catID = {$category}", 'AND approve = 1', 'itemID');
      // foreach ($allItems as $item) {
      //   echo "
      //   <div class='col-sm-6 col-md-3'>
      //     <div class='card item-box'>
      //       <span class='price-tag'>$item[price]$</span>
      //       <img class='card-img-top img-thumbnail' src='avatar.png' alt='User Avatar'>
      //       <div class='card-body caption'>
      //         <h3><a href='items.php?itemID=$item[itemID]'>$item[name]</a></h3>
      //         <p>$item[description]</p>
      //         <div class='date'>$item[add_date]</div>
      //       </div>
      //     </div>
      //   </div>";
      // }
    }
    else {
      echo "<div class='alert alert-danger'>You Must enter tag name!</div>";
    }

    ?>
  </div>
</div>

<?php
include "$tpl/footer.php";
ob_end_flush();
?>