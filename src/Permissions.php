<?php
include_once "Header.php";
$_db = new Database();
$_db -> connect();
$_db -> searchUsers('A');
if (session_id() == '') {
	session_start();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($_POST['addpermtogroup']) {
		$permname = $_POST['permname'];
		$groupname = $_POST['groupname'];
		$_db -> addPermissionToGroup($permname, $groupname);
	} else if ($_POST['createperm']) {
		$permname = $_POST['newpermname'];
		$readwrite = $_POST['readwrite'];
		$_db -> createPermission($permname, $readwrite);
	} else if ($_POST['creategroup']) {
		$groupname = $_POST['newgroupname'];
		$_db -> createGroup($groupname);
	} else if ($_POST['addgrouptouser']) {
		$groupname = $_POST['groupnameforuser'];
		$username = $_POST['userforgroup'];
		$_db -> addGroupToUser($groupname, $username);
	}
}

$_db -> disconnect();
unset($_db);
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
		include_once "menu.php";
		?>
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/black-tie/jquery-ui.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/site.css" />
		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script type="text/javascript"></script>

		<script language="JavaScript">
			function displayUserDetails() {
				if ($.callback_json_getUserDetails.complete) {
					data = $.callback_json_getUserDetails.data;
					$(".userDetails_div").empty();
					$(".userDetails_div").append("<table border='1'>");
					$(".userDetails_div").append("<tr><td>User</td><td>Group</td><td>Permission</td><td>Access</td><tr>");
					for (var i = 0; i < data[0].length; i++) {
						$(".userDetails_div").append("<tr>");
						for (var j = 0; j < data.length; j++) {
							$(".userDetails_div").append("<td>" + data[j][i] + "</td>");
						}
						$(".userDetails_div").append("</tr>");
					}
					$(".userDetails_div").append("</table>");
				}
			}

			function getUserDetails(user) {
				$.callback_json_getUserDetails.complete = false;
				$.getJSON("Server/server.php?function=displayUserDetails&username=" + user, function(jdata) {
					$.callback_json_getUserDetails.data = jdata;
					$.callback_json_getUserDetails.complete = true;
					displayUserDetails();
				});
			}


			jQuery(document).ready(function($) {
				$('#users').autocomplete({
					source : 'Server/server.php?function=autoComplete&param1=USERNAME',
					minLength : 1
				});

				$('#groups').autocomplete({
					source : 'Server/server.php?function=autoComplete&param1=GROUPNAME',
					minLength : 1
				});

				$('#groups2').autocomplete({
					source : 'Server/server.php?function=autoComplete&param1=GROUPNAME',
					minLength : 1
				});

				$('#perms').autocomplete({
					source : 'Server/server.php?function=autoComplete&param1=PERMNAME',
					minLength : 1
				});

				$('#username_lookup').autocomplete({
					source : 'Server/server.php?function=autoComplete&param1=USERNAME',
					minLength : 1
				});

				$("#lookupuser").click(function() {
					$.callback_json_getUserDetails = {}
					getUserDetails($("#username_lookup").val());
				});
			});

		</script>
	</head>
	<body>
		<h1>Permissions</h1>
		<fieldset>

			<legend>
				<b>Users Permissions</b>
			</legend>
			<div class="userDetailsHeader_div">
				<input type="text" id="username_lookup" name="username_lookup">
				<button id="lookupuser">
					Lookup User
				</button>
			</div>
			<div class="userDetails_div"></div>
		</fieldset>
		<fieldset>
			<legend>
				<b>Create Permissions and Groups<b>
			</legend>

			<form action="" method="post">
				<label>Permission Name :</label>
				<input type="text" name="newpermname"/>
				<br />
				<input type="radio" name="readwrite" value="R" checked>
				Read
				<br>
				<input type="radio" name="readwrite" value="W">
				Write
				<br>
				<input type="submit" name = "createperm" value=" Create Permission "/>
				<br />
			</form>

			<form action="" method="post">
				<label>Group Name :</label>
				<input type="text" name="newgroupname"/>
				<br />
				<input type="submit" name = "creategroup" value=" Create Group "/>
				<br />
			</form>
		</fieldset>

		<fieldset>
			<legend>
				<b>Attach Permissions to Groups<b>
			</legend>
			<form action="" method="post">
				<label>Permission Name :</label>
				<input id="perms" type="text" name="permname"/>
				<br />
				<label>Group Name :</label>
				<input id="groups" type="text" name="groupname"/>
				<br/>
				<input type="submit" name="addpermtogroup" value=" Add Permission to Group "/>
				<br />
			</form>
		</fieldset>

		<fieldset>
			<legend>
				<b>Attach Groups to User<b>
			</legend>
			<form action="" method="post">
				<label>Group name :</label>
				<input id="groups2" type="text" name="groupnameforuser"/>
				<br />
				<label>User name :</label>
				<input id="users" type="text" name="userforgroup"/>
				<br />
				<input type="submit" name = "addgrouptouser" value=" Attach Group to User "/>
				<br />
			</form>
	</body>
