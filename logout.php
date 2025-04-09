<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

session_start();

logoutUser();

header("Location: login.php?logout=success");
exit;
?>