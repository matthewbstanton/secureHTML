<?php
	include("Classes/UserAccess.php");
	$useraccess = new UserAccess();
	$config = new Config();
	$useraccess->logout();
	header("location: " . $config->getLoginPage());
?>