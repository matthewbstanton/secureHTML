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

function sqlDataTo2dArray($data, $column, $column2) {
	$myarray = array();
	$myarray2 = array();
	while ($row = mysql_fetch_assoc($data)) {
		array_push($myarray, $row[$column]);
		array_push($myarray2, $row[$column2]);
	}
	return array($myarray, $myarray2);
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

function getDocumentSections() {
	$document = new Document();
	$arrResults = $document->getDocumentSections($_GET['docname']);
	return sqlDataToArray($arrResults, 'SECTIONTEXT');
}

function getDocumentSectionsEdit() {
	$document = new Document();
	$arrResults = $document->getDocumentSections($_GET['docname']);
	return sqlDataTo2dArray($arrResults, 'SECTIONTEXT', 'PERMID');
}

function getDocumentSectionCount() {
	$document = new Document();
	return $document->getDocumentSectionCount($$_GET['docname']);
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
else if ($_GET['function'] == 'getDocumentSections') {
	echo json_encode(getDocumentSections());
}
else if ($_GET['function'] == 'getDocumentSectionCount') {
	echo json_encode(getDocumentSectionCount());
}
else if ($_GET['function'] == 'getDocumentSectionsEdit') {
	echo json_encode(getDocumentSectionsEdit());
}
?>