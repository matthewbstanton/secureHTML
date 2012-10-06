<?php
	Class Config {
	    private $_mysql_hostname = "localhost";
	    private $_mysql_user = "root";
		private $_mysql_password = "password";
		private $_mysql_database = "secureHTML";
		
		public function __construct(){
		}
		
		public function getHostname() {
			return $this->_mysql_hostname;
		}
		
		public function getUser() {
			return $this->_mysql_user;
		}
		
		public function getPassword() {
			return $this->_mysql_password;
		}
		
		public function getDBName() {
			return $this->_mysql_database;
		}
	}
?>