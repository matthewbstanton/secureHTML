<?php

	function __autoload($class_name) {
 		include ('Classes/'.$class_name . '.php');
	}
	
	$useraccess = new UserAccess();
	$config = new Config();
	$useraccess->logout();
	header("location: " . $config->getLoginPage());
?>