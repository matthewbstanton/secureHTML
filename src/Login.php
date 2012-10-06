<?php
	include("Classes/UserAccess.php");
	session_start();
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$useraccess = new UserAccess();
		$login = $useraccess->login($username, $password);
		
		if ($login == False) {
			print("login failed");
		}
		else {
			session_regenerate_id();
			
			$_SESSION['SESS_MEMBER_ID'] = $username;
			
			header("location: Welcome.php");
		}
	}
?>

<form action="" method="post">
<label>UserName :</label>
<input type="text" name="username"/><br />
<label>Password :</label>
<input type="password" name="password"/><br/>
<input type="submit" value=" Submit "/><br />
</form>