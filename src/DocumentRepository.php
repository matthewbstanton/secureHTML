<?php
	
	/*function __autoload($class_name) {
 		include ('Classes/'.$class_name . '.php');
	}
	
	$useraccess = new UserAccess();
	$useraccess->sessionValidation();*/
	
	function listDocuments(){
		//echo "Called listDocuments()";
		include_once "Header.php";
		$doc = new Document();
		$list = $doc->getDocumentList($useraccess->getUsername());
		if(!$list) {
			echo 'There were no documents that you have access to';
			return;
		}
		while ($row = mysql_fetch_assoc($list)) {
			echo '<tr><td>';
			echo '<input type="radio" name="document" value="test">';
			echo '</td></tr>';
		}
	}
?>
<html>
	<head>
		<title>Document Repository</title>
	</head>
	<body>
		<h1>Document Repository</h1>
		<fieldset>
		<legend><b>Select the document you want to view<b></legend>
		<form>
			<table id="documentList" border="1" width="95%">
				<tr bgcolor="#aaaaaa">
					<th width="5%"><th>
					<th width="35%"> Document Name </th>
					<th width="60%"> Document Description </th>
				<tr/>
				<?php listDocuments() ?>
			</table>
			<br />
			<input type="submit" value=" Submit "/>
			<input type="reset" value=" Reset "/><br />
		</form>
		</fieldset>
	</body>
</html>