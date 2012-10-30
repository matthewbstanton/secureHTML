<?php
include_once ("header.php");

$useraccess = new UserAccess();
$config = new Config();
$useraccess -> logout();
header("location: " . $config -> getLoginPage());
?>