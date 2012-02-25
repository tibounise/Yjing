<?php
	if (!isset($_GET['action'])) {
		rename("index.php","install.php");
		header("Location: install.php?action=install");
	}
	if (isset($_GET['action']) AND $_GET['action'] == "install") {
?>
<!doctype html>
<html>
<head>
	<title>OneClick Installer</title>
	<style type="text/css" media="screen">
		body {
			margin: 100px;
			background-color: #CCC;
			font-family: Arial,Helvetica,sans-serif;
			font-size: 10pt;
		}
		#content {
			-webkit-border-bottom-left-radius: 25px;
			-moz-border-radius-bottomleft: 25px;
			border-bottom-left-radius: 25px;
			background-color: #507e8c;
			padding: 50px;
			width: 320px;
			text-align: center;
			margin: auto;
		}
		#content p {
			color: white;
		}
	</style>
	<script>
		var checkDownloaded = true;
		var checkInstall = true;

		function download() {
			document.getElementById("content").innerHTML = "The setup is downloading files.";
			var xhr = new XMLHttpRequest();
			xhr.open("GET","install.php?action=download");
			xhr.send(null);
			checkIfDownloaded();
		}

		function checkIfDownloaded() {
			var xhr2 = new XMLHttpRequest();
			xhr2.open("GET","install.php?action=isdownloaded");
			xhr2.onreadystatechange = function() {
				if (xhr2.readyState == 4) {
					if (xhr2.status == 200) {
						if (xhr2.responseText == "1") {
							checkDownloaded = false;
							document.getElementById("content").innerHTML = "<p>Yjing is unzipping.</p>";
							var xhr3 = new XMLHttpRequest();
							xhr3.open("GET","install.php?action=extract");
							xhr3.send(null);
							checkIfExtracted();
						}
					}
				}
			}
			xhr2.send(null);
			if (checkDownloaded) {
				setTimeout("checkIfDownloaded()",1000);
			}
		}
		function checkIfExtracted() {
			var xhr4 = new XMLHttpRequest();
			xhr4.open("GET","install.php?action=isextracted");
			xhr4.onreadystatechange = function() {
				if (xhr4.readyState == 4) {
					if (xhr4.status == 200) {
						if (xhr4.responseText == "2") {
							checkInstall = false;
							document.getElementById("content").innerHTML = "<p>Yjign was installed. Please wait some seconds to lauch the installer.</p>";
							setTimeout("document.location.href = \"index.php\"",3000);
						}
					}
				}
			}
			xhr4.send(null);
			if (checkInstall) {
				setTimeout("checkIfExtracted()",1000);
			}
		}

	</script>
</head>
<body onLoad="download()">
<div id="content">
	<input type="submit" value="Lancer l'installation" onClick="download()" />
</div>
</body>
</html>
<?php
	}
	elseif (isset($_GET['action']) AND $_GET['action'] == "download" AND !file_exists("downloaded.txt")) {
		copy("http://tibounise.github.com/Yjing/downloads/lastversion.zip", "buffer.zip");
		$file = fopen("downloaded.txt","a");
		fputs($file,"1");
		fclose($file);
	}
	elseif (isset($_GET['action']) AND $_GET['action'] == "isdownloaded" AND file_exists("downloaded.txt")) {
		$file = fopen("downloaded.txt","r+");
		echo fgets($file);
		fclose($file);
	}
	elseif (isset($_GET['action']) AND $_GET['action'] == "extract" AND !file_exists("extracted.txt")) {
		if (file_exists("downloaded.txt")) {
			unlink("downloaded.txt");
		}
		$buffer = new ZipArchive;
		$res = $buffer->open("buffer.zip");
		if ($res) {
			$buffer->extractTo("./");
			$buffer->close();
			$file = fopen("extracted.txt","a");
			fputs($file,"2");
			fclose($file);
		}
		else {
			$file = fopen("extracted.txt","a");
			fputs($file,"3");
			fclose($file);
		}

	}
	elseif (isset($_GET['action']) AND $_GET['action'] == "isextracted" AND file_exists("extracted.txt")) {
		$file = fopen("extracted.txt","r+");
		echo fgets($file);
		fclose($file);
		unlink("install.php");
	}

?>