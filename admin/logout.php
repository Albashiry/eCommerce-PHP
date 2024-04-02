<?php

// start the session
session_start();

session_unset(); //unset the data

session_destroy(); //destroy the session the data

header('Location: index.php');

exit();