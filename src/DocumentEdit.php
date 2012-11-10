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
		<script type="teext/javascript"></script>
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

			function generatePermissionBox(id, perm, permlist) {
				$(".documentSections_div").append("<select id = 'PermissionList_" + id + "' name = 'PermissionList_" + id + "' class='PermissionList'></select>");
				for (var i = 0; i < permlist.length; i++)
					$("#PermissionList_" + id).append("<option value=" + permlist[i] + ">" + permlist[i] + "</option>");
				$("#PermissionList_" + id).val(perm);
			}
			
			function generateSectionIDBox(id, sectionid) {
				$(".documentSections_div").append("<input type='text' id='Section_" + id + "' name='Section_" + id + "'></input>");
				$('#Section_'+id).val(sectionid);
				$(".documentSections_div").append("<br/>");
			}

			function generateDocumentSection(id, perm, permlist, data, sectionid) {

				$(".documentSections_div").append("<br/>");
				generateSectionIDBox(id, sectionid);
				generatePermissionBox(id, perm, permlist);
				$(".documentSections_div").append("<br/>");
				$(".documentSections_div").append("<textarea id = 'TextArea_" + id + "' name = 'TextArea_" + id + "' class='SectionData'>" + data + "</textarea>");
				$.sectionCount++;
			}

			//callback function
			function generateDocument() {
				//if both json threads are complete, we can move forward.  Else we are waiting for 1 or more threads to complete.
				if ($.callback_json_getDocumentSections.complete && $.callback_json_getPermissionSections.complete) {
					documentSections = $.callback_json_getDocumentSections.data;
					if (documentSections == null)
						return;
					permlist = $.callback_json_getPermissionSections.data;
					for (var i = 0; i < documentSections[0].length; i++) {
						generateDocumentSection(i, documentSections[1][i], permlist, documentSections[0][i], documentSections[2][i]);
					}
				}
			}

			function getUserPermissions() {
				$.callback_json_getPermissionSections.complete = false;
				$.getJSON("Server/server.php?function=userPermissionList", function(permData) {
					$.callback_json_getPermissionSections.data = permData;
					$.callback_json_getPermissionSections.complete = true;
					generateDocument();
				});
			}

			function getDocumentSections(_docname) {
				$.getJSON("Server/server.php?function=getDocumentSections&docname=" + _docname, function(jdata) {
					$.callback_json_getDocumentSections.complete = false;
					$.callback_json_getDocumentSections.data = jdata;
					$.callback_json_getDocumentSections.complete = true;
					generateDocument();
				});
			}


			$(document).ready(function() {
				//Global variables, to be set when callback from json is complete
				$.callback_json_getDocumentSections = {}
				$.callback_json_getPermissionSections = {}
				$.sectionCount = 0;

				var documentSectionCount = 0;
				var docname = getUrlVars()["docname"];
				var documentSections = new Array();
				
				//Load permissions to be used in other places
				$.callback_json_getPermissionSections.complete = false;
				getUserPermissions();

				if (getUrlVars()["action"] == "edit") {
					$('#documentNameID').val(docname);
					$("#documentNameID").attr("disabled", "disabled");
					$.callback_json_getDocumentSections.complete = false;
					getDocumentSections(docname);
				}

				$("#addDocumentSection").click(function() {
					generateDocumentSection($.sectionCount, $.callback_json_getPermissionSections[0], $.callback_json_getPermissionSections.data, "");
				});

				$("#docSections").validate({
					submitHandler : function(form) {
					$("#documentNameID").attr("disabled", false);
						$.post('Server/server.php?function=saveDocument&count=' + $.sectionCount, $("#docSections").serialize(), function(data) {
							alert("Saved to DB");
							$("#documentNameID").attr("disabled", "disabled");
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
				<input type="text" id="documentNameID" name="documentName">
				</input>
				<div class="documentSections_div">

				</div>
				<input type="submit" name="save" value="Save">
				</input>
			</form>
		</fieldset>

	</body>
</html>