<?php
	include("Classes/UserAccess.php");
	$useraccess = new UserAccess();
	$useraccess->sessionValidation();
	print("Welcome " . $useraccess->getUsername());
?>
<body>
	<h1></h1>
	<A HREF = logout.php>Log out</A>
</body>