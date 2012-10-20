<?php
Class Document{
	private $_db;
	
	public function __construct(){
		$this->_db = new Database();
	}
	
	public function getDocument($id) {
	
	}
	
	public function saveDocument($id) {
	
	}
	
	public function getDocumentList($username) {
		return $this->_db->retrieveDocumentList($username);
	}
}
?>