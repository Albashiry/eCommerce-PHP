<?php
session_start();
$pageTitle = "Homepage";

include 'init.php';
?>

<div class="container">
  <div class="row">
    <?php
    $allItems = getAllFrom('*', 'items', 'WHERE approve = 1', '', 'itemID');
    foreach ($allItems as $item) {
      $imgSource = empty($item['image']) ? "default-item.jpg" : $item['image'];
      echo "
      <div class='col-sm-6 col-md-3'>
        <div class='card item-box'>
          <span class='price-tag'>$item[price]$</span>
          <img class='card-img-top img-thumbnail' src='data\uploads\items\\$imgSource' alt='User Avatar'>
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