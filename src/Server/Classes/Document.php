<?php
Class Document{
	private $_db;
	
	public function __construct(){
		$this->_db = new Database();
		$this->_useraccess = new UserAccess();
	}
	
	public function getDocument($id) {
	
	}
	
	public function saveDocumentSection($docid, $sectionid, $permname, $data) {
		$permid = $this->_db->getPermID($permname);
		return $this->_db->InsertDocumentSection($docid, $sectionid, $permid, $data);
	}
	
	public function getDocumentSections($docname) {
		$username = $this->_useraccess->getUsername();
		$userid = $this->_db->getUserID($username);
		$docid = $this->_db->getDocumentID($docname);
		return $this->_db->getDocumentSections($docid, $userid);		
	}
	
	public function getDocumentSectionCount($docname) {
		$username = $this->_useraccess->getUsername();
		$userid = $this->_db->getUserID($username);
		$docid = $this->_db->getDocumentID($docname);
		return $this->_db->getDocumentSectionCount($docid, $userid);			
	}
	
	public function createDocumentDefinition($docname, $description, $permid) {
		return $this->_db->createDocumentDefinition($docname, $description, $permid);
	}
	
	public function getDocumentList($username) {
		return $this->_db->retrieveDocumentList($username);
	}
}
?>