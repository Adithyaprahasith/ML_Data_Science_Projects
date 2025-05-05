<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to main_page.html after logout
header("Location: main_page.html");
exit();
?>
