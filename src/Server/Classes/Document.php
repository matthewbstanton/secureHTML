<?php
Class Document {
	private $_db;

	public function __construct() {
		$this -> _db = new Database();
		$this -> _useraccess = new UserAccess();
		$this -> _security = new Security();
	}

	public function decryptData($data) {
		$count = count($data[0]);
		for ($i = 0; $i < $count; $i++) {
			$data[0][$i] = $this -> _security -> decrypt($data[0][$i]);
		}
		return $data;
	}

	public function saveDocumentSection($docid, $sectionid, $permname, $data) {
		$this -> _db -> connect();
		$permid = $this -> _db -> getPermID($permname);

		if ($docid == null || $docid < 0)
			return -1;
		if ($sectionid == null || $sectionid < 0)
			return -1;
		if ($permname == null || $permname == '')
			return -1;
		if ($data == null)
			return -1;
		if ($permid == null || $permid < 0)
			return -1;

		if ($this -> _db -> documentSectionExists($docid, $sectionid) > 0) {
			/*can user write to this section?*/
			$username = $this -> _useraccess -> getUsername();
			$userid = $this -> _db -> getUserID($username);
			if ($this -> _db -> documentSectionAccess($docid, $sectionid, $userid) <= 0)
				return -1;
			else
				$returncode = $this -> _db -> UpdateDocumentSection($docid, $sectionid, $permid, $data);
		} else {
			$returncode = $this -> _db -> InsertDocumentSection($docid, $sectionid, $permid, $data);
		}
		$this -> _db -> disconnect();

		return $returncode;
	}

	public function getDocumentSections($docname) {
		$username = $this -> _useraccess -> getUsername();

		$this -> _db -> connect();
		if ($this -> _db -> documentExists($docname) > 0) {
			$userid = $this -> _db -> getUserID($username);
			$docid = $this -> _db -> getDocumentID($docname);
			$data = $this -> _db -> getDocumentSections($docid, $userid);
			$this -> _db -> disconnect();
			return $data;
		}
	}

	public function getDocumentSectionCount($docname) {
		$username = $this -> _useraccess -> getUsername();

		$this -> _db -> connect();
		if ($this -> _db -> documentExists($docname) > 0) {
			$userid = $this -> _db -> getUserID($username);
			$docid = $this -> _db -> getDocumentID($docname);
			$count = $this -> _db -> getDocumentSectionCount($docid, $userid);
			$this -> _db -> disconnect();

			return $count;
		}
	}

	public function createDocumentDefinition($docname, $description, $permid) {
		$this -> _db -> connect();
		$docexists = $this -> _db -> documentExists($docname);

		if ($docexists > 0)
			$returncode = $this -> _db -> getDocumentID($docname);
		else
			$returncode = $this -> _db -> createDocumentDefinition($docname, $description, $permid);
		$this -> _db -> disconnect();

		return $returncode;
	}

	public function getDocumentList($username) {
		$this -> _db -> connect();
		$list = $this -> _db -> retrieveDocumentList($username);
		$this -> _db -> disconnect();

		return $list;
	}

	public function __destruct() {
		unset($this -> _db);
		unset($this -> _useraccess);
		unset($this -> _security);
	}

}
?>