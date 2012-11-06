<?php
Class Document {
	private $_db;

	public function __construct() {
		$this -> _db = new Database();
		$this -> _useraccess = new UserAccess();
		$this -> _security = new Security();
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

	public function decryptData($data) {
		$count = count($data[0]);
		for ($i = 0; $i < $count; $i++) {
			$data[0][$i] = $this -> _security -> decrypt($data[0][$i]);
		}
		return $data;
	}

	public function saveDocumentSection($docid, $sectionid, $permname, $data) {
		$permid = $this -> _db -> getPermID($permname);
		$encrypted_data = $this -> _security -> encrypt($data);
		if ($this -> _db -> documentSectionExists($docid, $sectionid) > 0)
			return $this -> _db -> UpdateDocumentSection($docid, $sectionid, $permid, $encrypted_data);
		else
			return $this -> _db -> InsertDocumentSection($docid, $sectionid, $permid, $encrypted_data);
	}

	public function getDocumentSections($docname) {
		$username = $this -> _useraccess -> getUsername();
		$userid = $this -> _db -> getUserID($username);
		$docid = $this -> _db -> getDocumentID($docname);

		$arrResults = $this -> _db -> getDocumentSections($docid, $userid);
		$data = sqlDataTo2dArray($arrResults, 'SECTIONTEXT', 'PERMID');
		$decrypted_data = $this -> decryptData($data);
		return $decrypted_data;
	}

	public function getDocumentSectionCount($docname) {
		$username = $this -> _useraccess -> getUsername();
		$userid = $this -> _db -> getUserID($username);
		$docid = $this -> _db -> getDocumentID($docname);
		return $this -> _db -> getDocumentSectionCount($docid, $userid);
	}

	public function createDocumentDefinition($docname, $description, $permid) {
		$docexists = $this -> _db -> documentExists($docname);
		if ($docexists > 0)
			return $this -> _db -> getDocumentID($docname);
		else
			return $this -> _db -> createDocumentDefinition($docname, $description, $permid);
	}

	public function getDocumentList($username) {
		return $this -> _db -> retrieveDocumentList($username);
	}

	public function __destruct() {
		unset($this -> _db);
		unset($this -> _useraccess);
		unset($this -> _security);
	}

}
?>