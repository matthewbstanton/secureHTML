<?php
class Database {
	
	private $_config;
	
	public function __construct() {
		$this->_config = new Config();
	}
	
	private function connect() {
		$config = $this->_config;
		$link = mysql_connect($config->getHostname(), $config->getUser(), $config->getPassword());
		if (!$link) {
			die('Not Connected : ' . mysql_error());
		}
		
		$db_selected = mysql_select_db($config->getDBName(), $link);
		if (!$db_selected) {
		    die ('Can\'t use db : ' . mysql_error());
		}
		
		return $db_selected;
	}
	
	private function getPermID($permname) {
		$this->connect();
		$sql = "SELECT PERMID
				FROM PERMISSIONDEFINITION
				WHERE PERMNAME = '$permname'";
				
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['PERMID'];
	}
	
	private function getGroupID($groupname) {
		$this->connect();
		$sql = "SELECT GROUPID
				FROM GROUPPERMISSIONS
				WHERE GROUPNAME = '$groupname'";
				
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['PERMID'];
	}
	
	public function getUserPassHash($username) {
		
		$this->connect();

		$sql = "SELECT PASSCODE
				FROM USERS
				WHERE USERNAME = '$username'";
				
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['PASSCODE'];
	}
	
	public function addUserToGroup($username, $groupname) {
		$this->connect();

		//$sql = "INSERT INTO ";
	}
	
	public function addPermissionToGroup($permname, $groupname) {
		$groupid = $this->getGroupID($groupname);
		$permid = $this->getPermID($permname);
		if ($groupid != 0 and $permid != 0) {
			$this->connect();
			$sql = "INSERT INTO GROUPS
					VALUES('$groupid', '$permid');";
		}
				
		mysql_query($sql) or die(mysql_error());
	}
	
	public function createPermission($permname, $readwrite) {
		$this->connect();
		$sql = "INSERT INTO PERMISSIONDEFINITION (PERMNAME, ACCESS)
				VALUES('$permname', '$readwrite');";
				
		mysql_query($sql) or die(mysql_error());
	}
	
	public function createGroup($groupname) {
		$this->connect();
		$sql = "INSERT INTO GROUPPERMISSIONS (GROUPNAME)
				VALUES('$groupname');";
				
		mysql_query($sql) or die(mysql_error());
	}
}
?>