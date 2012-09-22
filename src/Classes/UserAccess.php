<?php
class UserAccess {
	//singleton because we will only have one instance of this class per login.
	//Programmer will be able to access this without passing of an object
	$private static $_username;
	$private static $_passhash;
	
	public static function login($username, $password) {
		$_username = $username;
		$_passhash = hash('sha512', $password);
		
		$sql = "SELECT PASSCODE
				FROM USERS
				WHERE USERNAME = '$_username'";
				
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$dbpasshash = $row['PASSCODE'];
		
		if($_passhash == $dbpasshash) {
			return True;
		}
		else {
			return False;
		}
	}
}
?>
