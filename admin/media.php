<?php

	session_start();

	include("../params.php");
	include("../functions.php");

	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true) {
		if ($_GET['action'] == "upload_file") {
			$page = "<h1>Upload a media</h1><br /><form class=\"form-horizontal\" action=\"media.php\" method=\"POST\" enctype=\"multipart/form-data\"><fieldset>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">File : </label><div class=\"controls\"><input type=\"file\" class=\"input-file span6\" id=\"img\" name=\"img\"><p class=\"help-block\">For your security, Yjing accepts only by default Jpeg, Gif, Png, Mp3, Mp4, Avi, WebM and Ogg files.</p></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-info\">Upload</button></div></div>";
			$page .= "</fieldset></form>";
		}

		include("design.html");
	}

	else {
		header("Location: login.php");
	}

?>