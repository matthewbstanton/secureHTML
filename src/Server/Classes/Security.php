<?php

include("3rdParty/AES/AES.class.php");
	
class Security {
	public function __construct(){
		$this->_config = new Config();
		$this->_aes = new AES($this->_config->getSecurityKey());
	}
	
	public function hash($value) {
		return hash('sha512', $value);
	}
	
	public function encrypt($plainText) {
		return $this->_aes->encrypt($plainText);
	}
	
	public function decrypt($cipherText) {
		return $this->_aes->decrypt($cipherText);
	}
}
?>