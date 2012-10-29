<?php
include_once "Header.php";
?>

<!DOCTYPE html>
<html>
	<head>
		<?php
		include_once "menu.php";
		?>
		<script src="js/jquery.js"></script>
		<script language="JavaScript">
			$(document).ready(function() {
				$("#addDocumentSection").click(function() {
					//$(".documentSections_div").clear();
					$(".documentSections_div").append("<br/>");
					$(".documentSections_div").append("<select class='PermissionList'></select>");
					$(".PermissionList").append("<option value=Min_DurationVar>Min_DurationVar</option>")
					$(".documentSections_div").append("<br/>");
					$(".documentSections_div").append("<textarea class='SectionData'></textarea>");
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