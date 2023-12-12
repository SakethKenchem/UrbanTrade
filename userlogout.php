<?php
//handles logout for users
session_start();
session_destroy();
header("Location: homepage.php");
exit();

?>