<?php
ob_start();
session_start();
$pageTitle = "Categories";
include 'init.php';
?>

<div class="container">
  <h1 class="text-center">Show Category</h1>
  <div class="row">
    <?php
    foreach (getItems('catID', $_GET['pageID']) as $item) {
      echo "
      <div class='col-sm-6 col-md-3'>
        <div class='card item-box'>
          <span class='price-tag'>$item[price]$</span>
          <img class='card-img-top img-thumbnail' src='avatar.png' alt='User Avatar'>
          <div class='card-body caption'>
            <h3><a href='items.php?itemID=$item[itemID]'>$item[name]</a></h3>
            <p>$item[description]</p>
            <div class='date'>$item[add_date]</div>
          </div>
        </div>
      </div>";
    }
    ?>
  </div>
</div>

<?php
include "$tpl/footer.php";
ob_end_flush();
?>