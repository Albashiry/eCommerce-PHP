<?php include 'init.php'; ?>


<div class="container">
  <h1 class="text-center">
    <?= str_replace('-', ' ', $_GET['pageName']) ?>
  </h1>
  <div class="row">
    <?php
    foreach (getItems($_GET['pageID']) as $item) {
      echo "
      <div class='col-sm-6 col-md-3'>
        <div class='card item-box'>
        <span class='price-tag'>$item[price]</span>
          <img class='card-img-top img-thumbnail' src='avatar.png' alt='User Avatar'>
          <div class='card-body'>
            <h3>$item[name]</h3>
            <p>$item[description]</p>
          </div>
        </div>
      </div>";
    }
    ?>
  </div>
</div>


<?php include "$tpl/footer.php"; ?>