<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'admin/connect.php';

$sessionUser = isset($_SESSION['user']) ? $_SESSION['user'] : '';

// Routes
$tpl  = 'includes/templates/'; // templates directory
$lang = 'includes/languages/'; //language directory
$func = 'includes/functions/'; //functions directory
$css  = 'layout/css/'; // css directory
$js   = 'layout/js/'; // js directory

//include the important files
include "$lang/English.php"; // language phrases
include "$func/functions.php"; // language phrases

include "$tpl/header.php"; //header of the page
