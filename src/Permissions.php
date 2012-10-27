<?php
	include_once "Header.php";
	$_db = new Database();
	$_db->searchUsers('A');
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
		else if($_POST['addgrouptouser']) {
			$groupname = $_POST['groupnameforuser'];
			$username = $_POST['userforgroup'];
			$_db->addGroupToUser($groupname, $username);
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<!--<script src="jquery.js"></script>-->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/black-tie/jquery-ui.css" type="text/css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>

<script>
jQuery(document).ready(function($){
	$('#users').autocomplete({source:'autocomplete_users.php', minLength:2});
});
jQuery(document).ready(function($){
	$('#groups').autocomplete({source:'autocomplete_groups.php', minLength:2});
});
jQuery(document).ready(function($){
	$('#groups2').autocomplete({source:'autocomplete_groups.php', minLength:2});
});
jQuery(document).ready(function($){
	$('#perms').autocomplete({source:'autocomplete_perms.php', minLength:2});
});

    
</script>
</head>
<body>	
	<form action="" method="post">
	<label>Permission Name :</label>
	<input id="perms" type="text" name="permname"/><br />
	<label>Group Name :</label>
	<input id="groups" type="text" name="groupname"/><br/>
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
	
	<form action="" method="post">
	<label>Group name :</label>
	<input id="groups2" type="text" name="groupnameforuser"/><br />
	<label>User name :</label>
	<input id="users" type="text" name="userforgroup"/><br />
	<input type="submit" name = "addgrouptouser" value=" Add Group to User "/><br />
	</form>
</body>