<?php
	include_once "Header.php";
	$_db = new Database();
		if(session_id() == '') {
		session_start();
	}
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($_POST['addpermtogroup']) {
			$permname = $_POST['permname'];
			$groupname = $_POST['groupname'];
			$_db->addPermissionToGroup($permname, $groupname);
		}
		else if($_POST['createperm']) {
			$permname = $_POST['newpermname'];
			$readwrite = $_POST['readwrite'];
			$_db->createPermission($permname, $readwrite);
		}
		else if($_POST['creategroup']) {
			$groupname = $_POST['newgroupname'];
			$_db->createGroup($groupname);
		}
	}
?>

<form action="" method="post">
<label>Permission Name :</label>
<input type="text" name="permname"/><br />
<label>Group Name :</label>
<input type="text" name="groupname"/><br/>
<input type="submit" name="addpermtogroup" value=" Add Permission to Group "/><br />
</form>

<form action="" method="post">
<label>Permission Name :</label>
<input type="text" name="newpermname"/><br />
<input type="radio" name="readwrite" value="R" checked> Read<br>
<input type="radio" name="readwrite" value="W"> Write<br> 
<input type="submit" name = "createperm" value=" Create Permission "/><br />
</form>

<form action="" method="post">
<label>Group Name :</label>
<input type="text" name="newgroupname"/><br />
<input type="submit" name = "creategroup" value=" Create Group "/><br />
</form>