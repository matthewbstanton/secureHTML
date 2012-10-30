<?php
Class Document{
	private $_db;
	
	public function __construct(){
		$this->_db = new Database();
	}
	
	public function getDocument($id) {
	
	}
	
	public function saveDocumentSection($docid, $sectionid, $permname, $data) {
		$permid = $this->_db->getPermID($permname);
		return $this->_db->InsertDocumentSection($docid, $sectionid, $permid, $data);
	}
	
	public function createDocumentDefinition($docname, $description, $permid) {
		return $this->_db->createDocumentDefinition($docname, $description, $permid);
	}
	
	public function getDocumentList($username) {
		return $this->_db->retrieveDocumentList($username);
	}
}
?>