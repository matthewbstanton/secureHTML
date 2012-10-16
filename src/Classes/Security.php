<?php
class Security {
	
	public function hash($value) {
		return hash('sha512', $value);
	}
}
?>