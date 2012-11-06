<?php
include_once "Header.php";
$useraccess = new UserAccess();
$permissions = $useraccess -> getPermissions();
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
			var documentSectionCount = 0;
		</script>
		<script language="JavaScript">
			function getUrlVars() {
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
				for (var i = 0; i < hashes.length; i++) {
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
				}
				return vars;
			}

			function sleep(ms) {
				var dt = new Date();
				dt.setTime(dt.getTime() + ms);
				while (new Date().getTime() < dt.getTime());
			}

			var documentSectionCount = 0;
			var docname = getUrlVars()["docname"];
			var docPermission = new Array();

			if (getUrlVars()["action"] == "edit") {
				$.getJSON("Server/server.php?function=getDocumentSections&docname=" + docname, function(jdata) {
					for (var i = 0; i < jdata.length; i++) {
						$(".documentSections_div").append("<br/>");
						$(".documentSections_div").append("<select id = 'PermissionList_" + documentSectionCount + "' name = 'PermissionList_" + documentSectionCount + "' class='PermissionList'></select>");
						$(".documentSections_div").append("<br/>");
						$(".documentSections_div").append("<textarea id = 'TextArea_" + documentSectionCount + "' name = 'TextArea_" + documentSectionCount + "' class='SectionData'>" + jdata[0][i] + "</textarea>");
						docPermission[i] = jdata[1][i];
						documentSectionCount++;
					}
				});
				//json start
				$.getJSON("Server/server.php?function=userPermissionList", function(permData) {
					//alert(permData);
					for (var i = 0; i < documentSectionCount; i++) {
						for (var j = 0; j < permData.length; j++) {
							$("#PermissionList_" + i).append("<option value=" + permData[j] + ">" + permData[j] + "</option>");
						}
						$("#PermissionList_" + i).val(docPermission[i]);
					}
				});
				//End json
			}

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

				sleep(1000);

				$("#docSections").validate({
					submitHandler : function(form) {
						// do other stuff for a valid form
						$.post('Server/server.php?function=saveDocument&count=' + documentSectionCount, $("#docSections").serialize(), function(data) {
							alert(data);
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
				<input type="text" name="documentName">
				<div class="documentSections_div">

				</div>
				<input type="submit" name="save" value="Save">
			</form>
		</fieldset>

	</body>
</html>