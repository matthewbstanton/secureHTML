<?php
include("Config.php");
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
	
	public function getUserPassHash($username) {
		
		$this->connect();

		$sql = "SELECT PASSCODE
				FROM USERS
				WHERE USERNAME = '$username'";
				
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['PASSCODE'];
	}
}
php?>