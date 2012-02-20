<?php

	session_start();

	include("../params.php");
	include("../functions.php");

	if (isset($_SESSION['connected']) AND !empty($_GET['action']) AND $_SESSION['connected'] == true) {
		$action = $_GET['action'];
		if ($action == "add_media") {
			$page = "<h1>Upload an image</h1><br /><form class=\"form-horizontal\" action=\"media.php?action=add_media_processing\" method=\"POST\" enctype=\"multipart/form-data\"><fieldset>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">Image : </label><div class=\"controls\"><input type=\"file\" class=\"span6\" id=\"media\" name=\"media\"></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-success\">Upload</button></div></div>";
			$page .= "</fieldset></form>";
			$page .= substr("trolol.php", -4, 0);
		}
		elseif ($action == "add_media_processing" AND !empty($_FILES)) {
			$media = $_FILES['media'];
			$ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
			if (preg_match("#jpg|jpeg|png|mp3|mp4|mpeg|avi|webm|ogg|swf#",$ext)) {
				move_uploaded_file($media['tmp_name'],"../media/".$media['name']);
				$page = "<p>Your media has been uploaded !</p><p><a href=\"index.php\" class=\"btn btn-warning\">Return to index</a></p>";
			} else {
				$page = "<p>Your media has not been uploaded !</p><p><a href=\"index.php\" class=\"btn btn-warning\">Return to index</a></p>";
			}
		}
	
		include("design.html");
	}

	else {
		header("Location: login.php");
	}

?>