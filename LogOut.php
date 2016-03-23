<?php
session_start();
echo "You have been logged out\n";
unset($_SESSION['usersession']);
session_destroy();
header("Location:Homepage.php");
?>
