<?php 

	session_start();

	$xml = new simpleXMLElement(file_get_contents("../data.xml"));

	foreach ($xml->config as $output) {
		$output->title = htmlentities($_POST['title']);
	}

	$buffer = $xml->asXML();
	unlink("../" . $datafile_url);
	$file = fopen("../" . $datafile_url,"w");
	fputs($file,$buffer);
	fclose($file);

?>
<!doctype html>
<html>
<head>
	<title>Yjing - Installation</title>
	<style type="text/css" media="screen">
		
		body {
			padding-top: 60px;
		}
	</style>
	<link rel="stylesheet" href="../admin/bootstrap.min.css">
</head>
<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="#">Yjing - Installation</a>
				<div class="nav-collapse">
            		<ul class="nav">
              			<li><a href="#">Step 1</a></li>
              			<li><a href="#">Step 2</a></li>
              			<li><a href="#">Step 3</a></li>
              			<li class="active"><a href="#">Step 4</a></li>
            		</ul>
          		</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div id="alertbox"></div>
		<p><h1>Your website is ready to be used !</h1></p>
		<p>Yeah, that was fast and easy ...</p>
		<p><a href="../" class="btn btn-info">Go to your website !</a>&nbsp;<a href="../admin" class="btn btn-warning">Go to the administration panel !</a></p>

		<br>
		<hr>
		<footer><p>Yjing is under GNU GPL v3 licence. The Twitter Bootstrap is under Apache Licence v2.0</p></footer>
	</div>

</body>
</html>