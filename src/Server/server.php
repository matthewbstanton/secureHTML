<?php
function __autoload($class_name) {
	include_once ('Classes/' . $class_name . '.php');
}

function login() {
	$config = new Config();
	if(session_id() == '') {
		session_start();
	}
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$useraccess = new UserAccess();
		$login = $useraccess->login($username, $password);
		
		if ($login == False) {
			header("location: " . $config->getLoginPage());
		}
		else {
			header("location: " . $config->getWelcomePage());
		}
	}
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

function saveDocument() {
	//return $_POST['PermissionList_0'];
	//return $_GET['count'];
	$sectionCount = $_GET['count'];
	$document = new Document();
	$docid = $document->createDocumentDefinition($_POST['documentName'], 'DESCRIPTION', 0);
	for ($i=0; $i<$sectionCount; $i++) {
		$permname = $_POST['PermissionList_' . $i];
		$data = $_POST['TextArea_' . $i];
		$document->saveDocumentSection($docid, $i, $permname, $data);
	}
	return print_r($_POST);
	//return $_POST['documentName'];
}

if ($_GET['function'] == 'login') {
	login();
}

$useraccess = new UserAccess();
$useraccess -> sessionValidation();

if ($_GET['function'] == 'userPermissionList') {
	echo json_encode(userPermissionList());
}
else if ($_GET['function'] == 'autoComplete') {
	echo json_encode(autoComplete($_REQUEST['term'], $_GET['param1'])); 
}
else if ($_GET['function'] == 'saveDocument') {
	echo json_encode(saveDocument());
}
?>