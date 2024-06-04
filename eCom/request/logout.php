<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect the user to the index page after logout
header("Location: ../index.php");
exit;
?>
