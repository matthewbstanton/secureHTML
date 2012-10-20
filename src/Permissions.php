<?php
	include_once "Header.php";
	$_db = new Database();
	$permname = $_POST['permname'];
	$groupname = $_POST['groupname'];
	$_db->addPermissionToGroup($permname, $groupname);
?>

<form action="" method="post">
<label>Permission Name :</label>
<input type="text" name="permname"/><br />
<label>Group Name :</label>
<input type="text" name="groupname"/><br/>
<input type="submit" value=" Add Permission to Group "/><br />
</form>