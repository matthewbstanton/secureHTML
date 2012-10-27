<?php
include_once 'header.php';
if ( !isset($_REQUEST['term']) )
	exit;

$_db = new Database();
$arrResults = $_db->searchUsers(mysql_real_escape_string($_REQUEST['term']));

$myarray = array();
		while ($row = mysql_fetch_assoc($arrResults)) {
			$myarray = array();
			$myarray[] = array(
				'label' => $row['USERNAME'],
				'value' => $row['USERNAME']
			);
		}

echo json_encode($myarray);
?>