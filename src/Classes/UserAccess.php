<?php

	include("Database.php");
class UserAccess {
	//singleton because we will only have one instance of this class per login.
	//Programmer will be able to access this without passing of an object
	private $_username;
	private $_passhash;
	
	public function __construct(){
	}
	
	public function setUsername($username) {
		$this->_username = $username;
	}
	
	public function setPassword($password) {
		$this->_passhash = hash('sha512', $password);
	}
	
	public function login($username, $password) {
		$this->setUsername($username);
		$this->setPassword($password);

		$db = new Database();
		$dbpasshash = $db->getUserPassHash($this->_username);

		if($this->_passhash == $dbpasshash) {
			return True;
		}
		else {
			return False;
		}
	}
}
?>
