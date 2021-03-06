<?php

class UserAccess {
	private $_username;
	private $_passhash;
	private $_db;

	public function __construct() {
		$this -> _db = new Database();
		$this -> _config = new Config();
		$this -> _security = new Security();
	}

	public function setUsername($username) {
		$this -> _username = $username;
	}

	public function getUsername() {
		if (session_id() == '') {
			session_start();
		}
		return $_SESSION['SESS_MEMBER_ID'];
	}

	public function isLoggedIn() {
		if (session_id() == '') {
			session_start();
		}
		if (!isset($_SESSION['SESS_MEMBER_ID']) || $_SESSION['SESS_MEMBER_ID'] == '')
			return False;
		else
			return True;
	}

	public function sessionValidation() {
		if (!$this -> isLoggedIn())
			header("location: " . $this -> _config -> getLoginPage());
	}

	public function setPassword($password) {
		$this -> _passhash = $this -> _security -> hash($password);
	}

	private function registerSession() {
		session_regenerate_id();
		$_SESSION['SESS_MEMBER_ID'] = $this -> _username;
		session_write_close();
	}

	public function logout() {
		session_start();
		session_destroy();
		setcookie(session_name(), "", time() - 3600, "/");
	}

	public function login($username, $password) {
		$this -> setUsername($username);
		$this -> setPassword($password);

		$this -> _db -> connect();
		$dbpasshash = $this -> _db -> getUserPassHash($this -> _username);
		$this -> _db -> disconnect();

		if ($this -> _passhash == $dbpasshash) {
			$this -> registerSession();
			return True;
		} else {
			return False;
		}
	}

	public function getPermissions() {
		$this -> _db -> connect();
		$permissions = $this -> _db -> getUserPermissions($this -> getUsername());
		$this -> _db -> disconnect();
		
		return $permissions;
	}

	public function __destruct() {
		unset($this -> _config);
		unset($this -> _security);
		unset($this -> _db);
	}

}
?>
