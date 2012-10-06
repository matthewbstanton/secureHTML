<?php
	include("Classes/UserAccess.php");
	$useraccess = new UserAccess();
	print("Welcome " . $useraccess->getUsername());
?>
<body>
	<h1></h1>
</body>