<?php
class Log {
	function logSql($sql) {
		$fd = fopen("log.txt", "a");
		fwrite($fd, "\n-----------------\n");
		fwrite($fd, $sql);
		fclose($fd);
	}
	
	function logError($error) {
		$fd = fopen("log.txt", "a");
		fwrite($fd, "\n-----------------\n");
		fwrite($fd, $error);
		fclose($fd);
	}

}
?>