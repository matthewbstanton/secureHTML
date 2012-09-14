<?php
	include("Config.php");
	session_start();
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$link = mysql_connect($mysql_hostname, $mysql_user, $mysql_password);
		if (!$link) {
			die('Not Connected : ' . mysql_error());
		}
		
		$db_selected = mysql_select_db($mysql_database, $link);
		if (!$db_selected) {
		    die ('Can\'t use db : ' . mysql_error());
		}
				
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$sql = "SELECT PASSCODE
				FROM USERS
				WHERE USERNAME = '$username'";
				
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$passhash = $row['PASSCODE'];

		if($passhash == (hash('sha512', $password))) {
			print("Good Password");
		}
		else {
			print("Bad Password");
		}
		
		//header("location: Welcome.php");
	}
?>

<form action="" method="post">
<label>UserName :</label>
<input type="text" name="username"/><br />
<label>Password :</label>
<input type="password" name="password"/><br/>
<input type="submit" value=" Submit "/><br />
</form>