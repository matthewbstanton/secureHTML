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
	
	public function getPermID($permname) {
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
		return $row['GROUPID'];
	}
	
	public function getUserID($username) {
		$this->connect();
		$sql = "SELECT USERID
				FROM USERS
				WHERE USERNAME = '$username'";

		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['USERID'];
	}
	
	public function getDocumentID($documentName) {
		$this->connect();
		$sql = "SELECT DOCUMENTID
				FROM DOCUMENTDEFINITION
				WHERE DOCUMENTNAME = '$documentName'";

		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['DOCUMENTID'];
	}
	
	public function createDocumentDefinition($name, $description, $permid) {
		$this->connect();
		$sql = "INSERT INTO DOCUMENTDEFINITION (DOCUMENTNAME, DESCRIPTION, PERMID)
				VALUES ('$name', '$description', '$permid');";
		$result = mysql_query($sql) or die(mysql_error());
		return $this->getDocumentID($name);
	}

	public function InsertDocumentSection($docid, $sectionid, $permid, $data) {
		$this->connect();
		$sql = "INSERT INTO DOCUMENTDATA (DOCUMENTID, SECTIONID, PERMID, SECTIONTEXT)
				VALUES ('$docid', '$sectionid', '$permid', '$data');";
		$result = mysql_query($sql) or die(mysql_error());
		return $sectionid;
	}
	
	public function getDocumentSections($docid, $userid) {
		$this->connect();
		$sql = "SELECT SECTIONTEXT
				FROM DOCUMENTDATA DD
				INNER JOIN GROUPS G ON DD.PERMID = G.PERMID
				INNER JOIN USERS U ON U.GROUPID = G.GROUPID
				WHERE U.USERID = '$userid' AND DD.DOCUMENTID ='$docid';";
		return mysql_query($sql);
	}
	
	public function getDocumentSectionCount($docid, $userid) {
		$this->connect();
		$sql = "SELECT COUNT(SECTIONTEXT) AS COUNT
				FROM DOCUMENTDATA DD
				INNER JOIN GROUPS G ON DD.PERMID = G.PERMID
				INNER JOIN USERS U ON U.GROUPID = G.GROUPID
				WHERE U.USERID = '$userid';";
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['COUNT'];
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
	
	public function addPermissionToGroup($permname, $groupname) {
		$groupid = $this->getGroupID($groupname);
		$permid = $this->getPermID($permname);
		if ($groupid != 0 and $permid != 0) {
			$this->connect();
			$sql = "INSERT INTO GROUPS
					VALUES('$groupid', '$permid');";
					
			mysql_query($sql) or die(mysql_error());
		}
	}
	
	public function addGroupToUser($groupname, $username) {
		$groupid = $this->getGroupID($groupname);
		$userid = $this->getUserID($username);
		if ($groupid != 0 and $userid != 0) {
			$this->connect();
			$sql = "UPDATE USERS
					SET GROUPID = '$groupid'
					WHERE USERID = '$userid';";
					
			mysql_query($sql) or die(mysql_error());
		}
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
	
	public function retrieveDocumentList($username) {
		$this->connect();
		$sql = "SELECT DD.DOCUMENTID, DD.DESCRIPTION, DD.DOCUMENTNAME
				FROM DOCUMENTDEFINITION AS DD
				INNER JOIN PERMISSIONDEFINITION AS PD ON PD.PERMID = DD.PERMID OR DD.PERMID = 0
				INNER JOIN GROUPS AS G ON G.PERMID = PD.PERMID
				INNER JOIN GROUPPERMISSIONS AS GP ON GP.GROUPID = G.GROUPID
				INNER JOIN USERS AS U ON U.GROUPID = GP.GROUPID
				WHERE U.USERNAME = '$username';";
				
		return mysql_query($sql);
	}
	
	public function searchUsers($search) {
		$this->connect();
		$sql = "SELECT USERNAME
				FROM USERS
				WHERE USERNAME LIKE '$search%';";

		$result = mysql_query($sql);
		
		return $result;
	}
	
	public function searchGroups($search) {
		$this->connect();
		$sql = "SELECT GROUPNAME
				FROM GROUPPERMISSIONS
				WHERE GROUPNAME LIKE '$search%';";

		$result = mysql_query($sql);
		
		return $result;
	}
	
	public function searchPerms($search) {
		$this->connect();
		$sql = "SELECT PERMNAME
				FROM PERMISSIONDEFINITION
				WHERE PERMNAME LIKE '$search%';";

		return mysql_query($sql);
	}
	
	public function getUserPermissions($username) {
		$this->connect();
		$sql = "SELECT PERMNAME
				FROM PERMISSIONDEFINITION PD
				INNER JOIN GROUPS G ON G.PERMID = PD.PERMID
				INNER JOIN USERS U ON U.GROUPID = G.GROUPID
				WHERE U.USERNAME = '$username';";
		return mysql_query($sql);
	}
}
?>