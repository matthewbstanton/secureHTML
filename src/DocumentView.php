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
		<script src="js/jquery.validate.js"></script>
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
			//alert("In the ready() function");
			//document ready function
			$(document).ready(function() {
				
				var permissions = "";
				var documentSectionCount = 0;
				var docname = getUrlVars()["docname"];
				$("#documentHeader_div").append("<label id='documentTitle'><h2> " + docname + " </h2></label>");
				$.getJSON("Server/server.php?function=getDocumentSections&docname=" + docname, function(jdata) {
					for (var i = 0; i < jdata.length; i++) {
						$("#documentView_div").append(jdata[0][i]);
						$("#documentView_div").append("<br/>");
					}
				});
			});
		</script>
	</head>
	<body>
		<fieldset>
			<legend>
				<b>View Document</b>
			</legend>
			<div id="documentHeader_div">
			
			</div>
			<br/>
			<fieldset>
				<div id="documentView_div">
				
				</div>
			</fieldset>
		</fieldset>
	</body>
</html>