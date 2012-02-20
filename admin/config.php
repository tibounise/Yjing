<?php

	session_start();

	include("../params.php");
	include("../functions.php");

	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true) {
		if (!isset($_GET['action'])) {
			$config_get = getSiteInfos("../".$datafile_url);
			$page = "<h1>Edit config</h1><br /><form class=\"form-horizontal\" action=\"config.php?action=edit_config_processing\" method=\"POST\"><fieldset>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">Name of the site : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"name\" value=\"" . html_entity_decode($config_get[0]) . "\" name=\"name\"></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-success\">Save changes</button></div></div>";
			$page .= "</fieldset></form>";
		}
		elseif ($_GET['action'] == "edit_config_processing" AND isset($_POST['name'])) {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			foreach ($xml->config as $output) {
				$output->title = htmlentities($_POST['name']);
			}
			
			$buffer = $xml->asXML();
			unlink("../" . $datafile_url);
			$file = fopen("../" . $datafile_url,"w");
			fputs($file,$buffer);
			fclose($file);
			$page = "<p>Your changes has been done !</p><p><a href=\"index.php\" class=\"btn btn-warning\">Return to index</a></p>";
		}

		include("design.html");
	}

	else {
		header("Location: login.php");
	}

?>