<?php	
	include_once "Header.php";
	function listDocuments(){
		//echo "Called listDocuments()";
		$doc = new Document();
		$useraccess = new UserAccess();
		$list = $doc->getDocumentList($useraccess->getUsername());
		if(!$list) {
			echo 'There were no documents that you have access to';
			return;
		}
		while ($row = mysql_fetch_assoc($list)) {
			echo '<tr>';
			echo '<td><input type="radio" name="document" value="'.$row["DOCUMENTID"].'"></td>';
			echo '<td>'.$row["DOCUMENTNAME"].'</td>';
			echo '<td>'.$row["DESCRIPTION"].'</td>';
			echo '</tr>';
		}
	}
?>
<html>
	<head>
		<?php include_once "menu.php";?>
		<title>Document Repository</title>
	</head>
	<body>
		<h1>Document Repository</h1>
		<fieldset>
		<legend><b>Select the document you want to view<b></legend>
		<form>
			<table id="documentList" border="1" width="95%">
				<tr bgcolor="#aaaaaa">
					<th width="5%"></th>
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