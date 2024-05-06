<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php"><?= lang('HOME_ADMIN')?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="categories.php"><?= lang('CATEGORIES')?></a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="items.php"><?= lang('ITEMS')?></a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="members.php"><?= lang('MEMBERS')?></a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="comments.php"><?= lang('COMMENTS')?></a></li>
      </ul>
      
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Salam
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="../index.php"><?= lang('VisitShop')?></a></li>
            <li><a class="dropdown-item" href="members.php?do=edit&userID=<?= $_SESSION['id'] ?>"><?= lang('EditProfile')?></a></li>
            <li><a class="dropdown-item" href="#"><?= lang('Settings')?></a></li>
            <!-- <li><hr class="dropdown-divider"></li> -->
            <li><a class="dropdown-item" href="logout.php"><?= lang('Logout')?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>