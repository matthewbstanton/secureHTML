<?php
include_once "Header.php";
$useraccess = new UserAccess();
$permissions = $useraccess -> getPermissions();
print($permissions);
?>

<!DOCTYPE html>
<html>
	<head>
		<?php
		include_once "menu.php";
		?>
		<script src="js/jquery.js"></script>
		<script src="js/jquery.validate.js"</script>
		<script language="JavaScript">
			var permissions = "";
			var documentSectionCount = 0;
		</script>
		<script language="JavaScript">
			var permissions = "";
			var documentSectionCount = 0;
			var formContent = "action=getlink&link=abc";
			$(document).ready(function() {
				$("#addDocumentSection").click(function() {

					$(".documentSections_div").append("<br/>");
					$(".documentSections_div").append("<select id = 'PermissionList_" + documentSectionCount + "' name = 'PermissionList_" + documentSectionCount + "' class='PermissionList'></select>");
					$(".documentSections_div").append("<br/>");
					$(".documentSections_div").append("<textarea id = 'TextArea_" + documentSectionCount + "' name = 'TextArea_" + documentSectionCount + "' class='SectionData'></textarea>");

					//json start
					$.getJSON("Server/server.php?function=userPermissionList", function(jdata) {
						for (var i = 0; i < jdata.length; i++)
							$("#PermissionList_" + documentSectionCount).append("<option value=" + jdata[i] + ">" + jdata[i] + "</option>");

						documentSectionCount++;
					});
					//End json
				});

				$("#docSections").validate({
					submitHandler : function(form) {
						// do other stuff for a valid form
						$.post('Server/server.php?function=saveDocument&count=' + documentSectionCount, $("#docSections").serialize(), function(data) {
							alert(data);
							//$('#results').html(data);
						});
					}
				});
			});
		</script>
	</head>
	<body>
		<fieldset>
			<legend>
				<b>Document: </b>
			</legend>
			<div id="documentHeader_div">
				<button id="addDocumentSection">
					Add Document Section
				</button>
			</div>
			<form name="docSections" id="docSections" method="post">
				<div class="documentSections_div">

				</div>
				<input type="submit" name="save" value="Save">
			</form>
		</fieldset>

	</body>
</html>