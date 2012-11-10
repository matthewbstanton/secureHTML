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
			echo '<td><input type="radio" name="docname" value="'.$row["DOCUMENTNAME"].'"></td>';
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
		<script language="JavaScript">
			function changeView() {
				//alert("Got Here");
				document.getElementById("view_form").style.visibility = "visible";
				if(document.getElementById("action_view").checked) {
					document.getElementById("view_form").style.visibility = "visible";
					document.getElementById("edit_form").style.visibility = "hidden";
				}
				else {
					document.getElementById("view_form").style.visibility = "hidden";
					document.getElementById("edit_form").style.visibility = "visible";
				}
			}
		</script>
	</head>
	<body>
		<h1>Document Repository</h1>
		<fieldset>
		<legend><b>Select the document you want to edit or view<b></legend>
		<div id="userAction">
			<input type="radio" name="action" id="action_view" value="view" onclick="changeView()"/> View Document <br/>
			<input type="radio" name="action" id="action_edit" value="edit" onclick="changeView()"/> Edit Document <br/>
		</div>
		<div id="view_form" style="visibility:hidden">
			<form action="DocumentView.php" method="GET" visibility="hidden">
				<input type="hidden" name="action" value ="edit"/>
				<table id="documentList" border="1" width="95%">
					<tr bgcolor="#aaaaaa">
						<th width="5%"></th>
						<th width="35%"> Document Name </th>
						<th width="60%"> Document Description </th>
					<tr/>
					<?php listDocuments() ?>
				</table>
				<br />
				<input type="submit" value=" View "/>
				<input type="reset" value=" Reset "/><br />
			</form>
		</div>
		<div id="edit_form" style="visibility:hidden">
			<form action="DocumentEdit.php" method="GET" visibility="hidden">
				<input type="hidden" name="action" value ="edit"/>
				<table id="documentList" border="1" width="95%">
					<tr bgcolor="#aaaaaa">
						<th width="5%"></th>
						<th width="35%"> Document Name </th>
						<th width="60%"> Document Description </th>
					<tr/>
					<?php listDocuments() ?>
				</table>
				<br />
				<input type="submit" value=" Edit "/>
				<input type="reset" value=" Reset "/><br />
			</form>
		</div>
		<form action="DocumentEdit.php?action=new" method="POST">
		<div>
		<input type="submit" value=" Create New Document ">
		</div>
		</form>
		</fieldset>
	</body>
</html>