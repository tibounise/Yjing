<?php 

	session_start();

	$xml = new simpleXMLElement(file_get_contents("../data.xml"));

	$xml->config->title = htmlentities($_POST['title']);

	$buffer = $xml->asXML();
	unlink("../data.xml");
	$file = fopen("../data.xml","w");
	fputs($file,$buffer);
	fclose($file);

	$file = fopen("../params.php","r");
	$output = "";
	while (!feof ($file)) {
		$buffer1 = fgets($file, 4096);
		$buffer2 = $output . $buffer1;
		$output = $buffer2;
  	}
  	fclose($file);

  	str_replace(array("0 => \"<username>\"","1 => \"<password>\""), array("0 => \"" . $_SESSION['username'] . "\"","1 => \"" . md5($_SESSION['password'])) . "\"", $output);
  	unlink("../params.php");
	$file = fopen("../params.php","w");
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
<?php
	unlink("index.php");
	unlink("step2.php");
	unlink("step3.php");
	unlink("step4.php");
	rmdir("../install");
?>