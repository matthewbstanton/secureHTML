<?php
function __autoload($class_name) {
	include_once ('Classes/' . $class_name . '.php');
}

function login() {
	$config = new Config();
	$loginpage = $config -> getLoginPage();
	$welcomepage = $config -> getWelcomePage();
	unset($config);
	if (session_id() == '') {
		session_start();
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$useraccess = new UserAccess();
		$login = $useraccess -> login($username, $password);

		if ($login == False) {
			header("location: " . $loginpage);
		} else {
			header("location: " . $welcomepage);
		}
	}
}

function userPermissionList() {
	$useraccess = new UserAccess();
	$results = $useraccess -> getPermissions();
	unset($useraccess);
	return $results;
}

function autoComplete($term, $column) {
	$db = new Database();
	$db -> connect();
	$mySqlSearchSring = mysql_real_escape_string($term);
	if ($column == 'PERMNAME')
		$results = $db -> searchPerms($mySqlSearchSring);
	else if ($column == 'GROUPNAME')
		$results = $db -> searchGroups($mySqlSearchSring);
	else if ($column == 'USERNAME')
		$results = $db -> searchUsers($mySqlSearchSring);
	$db -> disconnect();
	unset($db);
	return $results;
}

function saveDocument() {
	$sectionCount = $_GET['count'];
	$document = new Document();
	$docid = $document -> createDocumentDefinition($_POST['documentName'], 'DESCRIPTION', 0);

	for ($i = 0; $i < $sectionCount; $i++) {
		$permname = $_POST['PermissionList_' . $i];
		$data = $_POST['TextArea_' . $i];
		$document -> saveDocumentSection($docid, $i, $permname, $data);
	}
	unset($document);
	return print_r($_POST);
}

function getDocumentSections() {
	$document = new Document();
	$data = $document -> getDocumentSections($_GET['docname']);
	unset($document);
	return $data;
}

function getDocumentSectionCount() {
	$document = new Document();
	$data = $document -> getDocumentSectionCount($_GET['docname']);
	unset($document);
	return $data;
}

if ($_GET['function'] == 'login') {
	login();
}

$useraccess = new UserAccess();
$useraccess -> sessionValidation();
unset($useraccess);

if ($_GET['function'] == 'userPermissionList') {
	echo json_encode(userPermissionList());
} else if ($_GET['function'] == 'autoComplete') {
	echo json_encode(autoComplete($_REQUEST['term'], $_GET['param1']));
} else if ($_GET['function'] == 'saveDocument') {
	echo json_encode(saveDocument());
} else if ($_GET['function'] == 'getDocumentSections') {
	echo json_encode(getDocumentSections());
} else if ($_GET['function'] == 'getDocumentSectionCount') {
	echo json_encode(getDocumentSectionCount());
}
?>