<?php
function __autoload($class_name) {
	include ('Classes/' . $class_name . '.php');
}

$useraccess = new UserAccess();
$useraccess -> sessionValidation();
//print ($_GET['UsersPermissions']);
//print ($_GET['Username']);
$arrResults = $useraccess -> getPermissions();

$myarray = array();
while ($row = mysql_fetch_assoc($arrResults)) {
	array_push($myarray, $row['PERMNAME']);
}

echo json_encode($myarray);
?>