<?php
class Database {

	private $_config;
	private $_link;

	public function __construct() {
		$this -> _config = new Config();
		$this -> _log = new Log();
	}

	function sqlDataToArray($data, $column) {
		$myarray = array();
		while ($row = mysql_fetch_assoc($data)) {
			array_push($myarray, $row[$column]);
		}
		return $myarray;
	}

	function sqlDataTo2dArray($data, $column, $column2) {
		$myarray = array();
		$myarray2 = array();
		while ($row = mysql_fetch_assoc($data)) {
			array_push($myarray, $row[$column]);
			array_push($myarray2, $row[$column2]);
		}
		return array($myarray, $myarray2);
	}

	function sqlDataTo3dArray($data, $column, $column2, $column3) {
		$myarray = array();
		$myarray2 = array();
		$myarray3 = array();
		while ($row = mysql_fetch_assoc($data)) {
			array_push($myarray, $row[$column]);
			array_push($myarray2, $row[$column2]);
			array_push($myarray3, $row[$column3]);
		}
		return array($myarray, $myarray2, $myarray3);
	}

	public function connect() {
		$config = $this -> _config;

		$this -> _link = mysql_connect($config -> getHostname(), $config -> getUser(), $config -> getPassword());

		if (!$this -> _link) {
			die('Not Connected : ' . mysql_error());
		}

		$db_selected = mysql_select_db($config -> getDBName(), $this -> _link);
		if (!$db_selected) {
			die('Can\'t use db : ' . mysql_error());
		}

		return $db_selected;
	}
	
	public function executeSQL($sql) {
		$this -> _log -> logSQL($sql);
		return mysql_query($sql);	
	}

	public function disconnect() {
		if ($this -> _link)
			mysql_close($this -> _link);
	}

	public function getPermID($permname) {
		$sql = "SELECT PERMID
				FROM PERMISSIONDEFINITION
				WHERE PERMNAME = '$permname'";

		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['PERMID'];
	}

	private function getGroupID($groupname) {
		$sql = "SELECT GROUPID
				FROM GROUPPERMISSIONS
				WHERE GROUPNAME = '$groupname'";

		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['GROUPID'];
	}

	public function getUserID($username) {
		$sql = "SELECT USERID
				FROM USERS
				WHERE USERNAME = '$username'";

		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['USERID'];
	}

	public function getDocumentID($documentName) {
		$sql = "SELECT DOCUMENTID
				FROM DOCUMENTDEFINITION
				WHERE DOCUMENTNAME = '$documentName'";

		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['DOCUMENTID'];
	}

	public function createDocumentDefinition($name, $description, $permid) {
		$sql = "INSERT INTO DOCUMENTDEFINITION (DOCUMENTNAME, DESCRIPTION, PERMID)
				VALUES ('$name', '$description', '$permid');";
		$result = $this -> executeSQL($sql);
		return $this -> getDocumentID($name);
	}

	public function InsertDocumentSection($docid, $sectionid, $permid, $data) {
		$security_key = $this -> _config -> getSecurityKey();
		$sql = "INSERT INTO DOCUMENTDATA (DOCUMENTID, SECTIONID, PERMID, SECTIONTEXT)
				VALUES ('$docid', '$sectionid', '$permid', AES_ENCRYPT('$data', '$security_key'));";				
		$result = $this -> executeSQL($sql);
		return $sectionid;
	}

	public function UpdateDocumentSection($docid, $sectionid, $permid, $data) {
		$security_key = $this -> _config -> getSecurityKey();
		$sql = "UPDATE DOCUMENTDATA
				SET SECTIONTEXT = AES_ENCRYPT('$data', '$security_key'), PERMID = $permid
				WHERE DOCUMENTID = $docid AND SECTIONID = $sectionid;";
		$result = $this -> executeSQL($sql);
		return $sectionid;
	}

	public function getDocumentSections($docid, $userid) {
		$security_key = $this -> _config -> getSecurityKey();
		$sql = "SELECT CAST(AES_DECRYPT(DD.SECTIONTEXT, '$security_key') AS CHAR CHARACTER SET utf8) AS SECTIONTEXT,
				PD.PERMNAME AS PERMID,
				DD.SECTIONID AS SECTIONID
				FROM DOCUMENTDATA DD
				INNER JOIN GROUPS G ON DD.PERMID = G.PERMID
				INNER JOIN USERS U ON U.GROUPID = G.GROUPID
				INNER JOIN PERMISSIONDEFINITION PD ON PD.PERMID = DD.PERMID
				WHERE U.USERID = '$userid' AND DD.DOCUMENTID ='$docid';";
		$arrResults = $this -> executeSQL($sql);
		$data = $this -> sqlDataTo3dArray($arrResults, 'SECTIONTEXT', 'PERMID', 'SECTIONID');
		return $data;
	}

	public function getDocumentSectionCount($docid, $userid) {
		$sql = "SELECT COUNT(1) AS COUNT
				FROM DOCUMENTDATA DD
				INNER JOIN GROUPS G ON DD.PERMID = G.PERMID
				INNER JOIN USERS U ON U.GROUPID = G.GROUPID
				WHERE U.USERID = '$userid';";
		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['COUNT'];
	}

	public function documentExists($docname) {
		$sql = "SELECT COUNT(1) AS COUNT
				FROM DOCUMENTDEFINITION DD
				WHERE DD.DOCUMENTNAME = '$docname';";
		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['COUNT'];
	}

	public function documentSectionExists($docid, $sectionid) {
		$sql = "SELECT COUNT(1) AS COUNT
				FROM DOCUMENTDATA DD
				WHERE DD.DOCUMENTID = $docid AND DD.SECTIONID = $sectionid;";
		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['COUNT'];
	}

	public function getUserPassHash($username) {
		$sql = "SELECT PASSCODE
				FROM USERS
				WHERE USERNAME = '$username'";

		$result = $this -> executeSQL($sql);
		$row = mysql_fetch_array($result);
		return $row['PASSCODE'];
	}

	public function addPermissionToGroup($permname, $groupname) {
		$groupid = $this -> getGroupID($groupname);
		$permid = $this -> getPermID($permname);
		if ($groupid != 0 and $permid != 0) {
			$sql = "INSERT INTO GROUPS
					VALUES('$groupid', '$permid');";

			$this -> executeSQL($sql);
		}
	}

	public function addGroupToUser($groupname, $username) {
		$groupid = $this -> getGroupID($groupname);
		$userid = $this -> getUserID($username);
		if ($groupid != 0 and $userid != 0) {
			$sql = "UPDATE USERS
					SET GROUPID = '$groupid'
					WHERE USERID = '$userid';";

			$this -> executeSQL($sql);
		}
	}

	public function createPermission($permname, $readwrite) {
		$sql = "INSERT INTO PERMISSIONDEFINITION (PERMNAME, ACCESS)
				VALUES('$permname', '$readwrite');";

		$this -> executeSQL($sql);
	}

	public function createGroup($groupname) {
		$sql = "INSERT INTO GROUPPERMISSIONS (GROUPNAME)
				VALUES('$groupname');";

		$this -> executeSQL($sql);
	}

	public function retrieveDocumentList($username) {
		$sql = "SELECT DD.DOCUMENTID, DD.DESCRIPTION, DD.DOCUMENTNAME
				FROM DOCUMENTDEFINITION AS DD
				INNER JOIN PERMISSIONDEFINITION AS PD ON PD.PERMID = DD.PERMID OR DD.PERMID = 0
				INNER JOIN GROUPS AS G ON G.PERMID = PD.PERMID
				INNER JOIN GROUPPERMISSIONS AS GP ON GP.GROUPID = G.GROUPID
				INNER JOIN USERS AS U ON U.GROUPID = GP.GROUPID
				WHERE U.USERNAME = '$username';";

		$this -> executeSQL($sql);
	}

	public function searchUsers($search) {
		$sql = "SELECT USERNAME
				FROM USERS
				WHERE USERNAME LIKE '$search%';";
		$arrResults = $this -> executeSQL($sql);
		return $this -> sqlDataToArray($arrResults, 'USERNAME');
	}

	public function searchGroups($search) {
		$sql = "SELECT GROUPNAME
				FROM GROUPPERMISSIONS
				WHERE GROUPNAME LIKE '$search%';";
		$arrResults = $this -> executeSQL($sql);
		return $this -> sqlDataToArray($arrResults, 'GROUPNAME');
	}

	public function searchPerms($search) {
		$sql = "SELECT PERMNAME
				FROM PERMISSIONDEFINITION
				WHERE PERMNAME LIKE '$search%';";
		$arrResults = $this -> executeSQL($sql);
		return $this -> sqlDataToArray($arrResults, 'PERMNAME');
	}

	public function getUserPermissions($username) {
		$sql = "SELECT PERMNAME
				FROM PERMISSIONDEFINITION PD
				INNER JOIN GROUPS G ON G.PERMID = PD.PERMID
				INNER JOIN USERS U ON U.GROUPID = G.GROUPID
				WHERE U.USERNAME = '$username';";
		$results = $this -> executeSQL($sql);
		return $this -> sqlDataToArray($results, 'PERMNAME');
	}

	public function __destruct() {
		unset($this -> _config);
		unset($this -> _log);
	}

}
?>