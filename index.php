<?php
session_start();
include 'init.php';

foreach (getCat() as $cat) {
  echo $cat['name'] . '<br/>';
}

include "$tpl/footer.php";