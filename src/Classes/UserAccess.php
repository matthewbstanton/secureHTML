<?php
include("Database.php");
class UserAccess {
	private $_username;
	private $_passhash;
	private $_db;
	
	public function __construct(){
		$this->_db = new Database();
	}
	
	public function setUsername($username) {
		$this->_username = $username;
	}
	
	public function setPassword($password) {
		$this->_passhash = hash('sha512', $password);
	}
	
	private function registerSession() {
		session_regenerate_id();
		$_SESSION['SESS_MEMBER_ID'] = $this->_username;
		session_write_close();
	}
	
	public function login($username, $password) {
		$this->setUsername($username);
		$this->setPassword($password);

		$dbpasshash = $this->_db->getUserPassHash($this->_username);

		if($this->_passhash == $dbpasshash) {
			$this->registerSession();
			return True;
		}
		else {
			return False;
		}
	}
}
?>
