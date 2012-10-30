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
		<script>
			var permissions = "";
			var documentSectionCount = 0;
		</script>
		<script language="JavaScript">
			var formContent ="action=getlink&link=abc";
			$(document).ready(function() {
				$("#addDocumentSection").click(function() {

					$(".documentSections_div").append("<br/>");
					$(".documentSections_div").append("<select id = 'PermissionList_" + documentSectionCount + "' class='PermissionList'></select>");
					$(".documentSections_div").append("<br/>");
					$(".documentSections_div").append("<textarea class='SectionData'></textarea>");
					
					//json start
					$.getJSON("Server/server.php?function=userPermissionList", function(jdata) {
						for(var i = 0; i < jdata.length; i++)
							$("#PermissionList_" + documentSectionCount).append("<option value=Min_DurationVar>" + jdata[i] + "</option>");
						
						documentSectionCount++;
					});
					//End json
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
			<div class="documentSections_div">

			</div>
		</fieldset>

	</body>
</html>