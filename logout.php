<?php
session_start();

// Kosongkan & musnahkan session
$_SESSION = [];
session_destroy();

// Terus ke login page
header("Location: login.php");
exit();
?>