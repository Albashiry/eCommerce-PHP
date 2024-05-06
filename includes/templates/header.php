<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= $css; ?>/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= $css; ?>/fontAwesome.min.css" />
  <link rel="stylesheet" href="<?= $css; ?>/jquery-ui.min.css" />
  <link rel="stylesheet" href="<?= $css; ?>/jquery.selectBoxIt.css" />
  <link rel="stylesheet" href="<?= $css; ?>/front.css" />
  <title>
    <?= getTitle() ?>
  </title>
</head>

<body>
  <div class="upper-bar">
    <div class="container">
      <?php if (isset($_SESSION['user'])) {

        echo "Welcome $sessionUser ";
        echo "<a href='profile.php'>Profile</a>";
        echo " - <a href='newad.php'>New Ad</a>";
        echo " - <a href='logout.php'>Logout</a>";

        if (checkUserStatus($sessionUser)) {
          // user is not active
          echo ' Your membership need to be activated by admin';
        }
      }
      else {
        ?>
        <a href="login.php"><span>Login/Signup</span></a>
      <?php } ?>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        Homepage
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <?php
          foreach (getCat() as $cat) {
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='categories.php?pageID=$cat[catID]'>$cat[name]</a></li>";
          }
          ?>

          <!-- <li class="nav-item"><a class="nav-link active" aria-current="page" href="categories.php">
              <?= lang('CATEGORIES') ?>
            </a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="items.php">
              <?= lang('ITEMS') ?>
            </a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="members.php">
              <?= lang('MEMBERS') ?>
            </a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="comments.php">
              <?= lang('COMMENTS') ?>
            </a></li> -->
        </ul>

        <!-- <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Salam
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="members.php?do=edit&userID=<?= $_SESSION['id'] ?>">
                  <?= lang('EditProfile') ?>
                </a></li>
              <li><a class="dropdown-item" href="#">
                  <?= lang('Settings') ?>
                </a></li>
               <li><hr class="dropdown-divider"></li> 
              <li><a class="dropdown-item" href="logout.php">
                  <?= lang('Logout') ?>
                </a></li>
            </ul>
          </li>
        </ul> -->
      </div>
    </div>
  </nav>