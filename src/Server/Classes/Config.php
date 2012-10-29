<?php
	Class Config {
		private $_mysql_hostname = "localhost";
		private $_mysql_user = "root";
		private $_mysql_password = "password";
		private $_mysql_database = "secureHTML";
		private $_loginPage = "login.php";
		private $_logoutPage = "logout.php";
		private $_welcomePage = "welcome.php";
		private $_hashAlgorithm = "sha512";
		#Must be 56-bit long key
		private $_securityKey = "abcdefgh01234567";
		
		public function __construct(){
		}
		
		public function getLoginPage() {
			return $this->_loginPage;
		}
		
		public function getLogoutPage() {
			return $this->_logoutPage;
		}
				
		public function getWelcomePage() {
			return $this->_welcomePage;
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
		
		public function getHashAlgorithm() {
			return $this->_hashAlgorithm;
		}
		
		public function getSecurityKey() {
			return $this->_securityKey;
		}
	}
?>