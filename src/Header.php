<?php
	function __autoload($class_name) {
 		include ('Server/Classes/'.$class_name . '.php');
	}
	
	$useraccess = new UserAccess();
	$useraccess->sessionValidation();
?>