<?php
function __autoload($class_name) {
	include ('Classes/' . $class_name . '.php');
}

function sqlDataToArray($data, $column) {
	$myarray = array();
	while ($row = mysql_fetch_assoc($data)) {
		array_push($myarray, $row[$column]);
	}
	return $myarray;
}

function userPermissionList() {
	$useraccess = new UserAccess();
	$arrResults = $useraccess -> getPermissions();
	return sqlDataToArray($arrResults, 'PERMNAME');
}

function autoComplete($term, $column) {
	$db = new Database();
	$mySqlSearchSring = mysql_real_escape_string($term);
	if ($column == 'PERMNAME')
		$arrResults = $db->searchPerms($mySqlSearchSring);
	else if ($column == 'GROUPNAME')
		$arrResults = $db->searchGroups($mySqlSearchSring);
	else if ($column == 'USERNAME')
		$arrResults = $db->searchUsers($mySqlSearchSring);
	return sqlDataToArray($arrResults, $column);
}

$useraccess = new UserAccess();
$useraccess -> sessionValidation();

if ($_GET['function'] == 'userPermissionList') {
	echo json_encode(userPermissionList());
}
else if ($_GET['function'] == 'autoComplete') {
	echo json_encode(autoComplete($_REQUEST['term'], $_GET['param1'])); 
}
?>