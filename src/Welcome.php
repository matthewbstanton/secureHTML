<?php

	/*function __autoload($class_name) {
 		include ('Classes/'.$class_name . '.php');
	}
	
	$useraccess = new UserAccess();
	$useraccess->sessionValidation();*/
	include_once "Header.php";
	print("Welcome " . $useraccess->getUsername());
?>
<body>
	<h1></h1>
	<A HREF = logout.php>Log out</A>
</body>