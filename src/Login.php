<?php	
	function __autoload($class_name) {
 		include ('Classes/'.$class_name . '.php');
	}
	
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
			print("login failed");
			/*Test aes*/
			//$sec = new Security();
			//print $sec->encrypt($sec->decrypt("ABC"));
		}
		else {
			header("location: " . $config->getWelcomePage());
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