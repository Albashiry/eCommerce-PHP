<?php

include 'admin/connect.php';

// Routes
$tpl = 'includes/templates/'; // templates directory
$lang = 'includes/languages/'; //language directory
$func = 'includes/functions/'; //functions directory
$css = 'layout/css/'; // css directory
$js = 'layout/js/'; // js directory

//include the important files
include "$lang/English.php"; // language phrases
include "$func/functions.php"; // language phrases

include "$tpl/header.php"; //header of the page
