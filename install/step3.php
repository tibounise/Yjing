<?php 
	session_start();

	$_SESSION['username'] = $_POST['username'];
	$_SESSION['password'] = $_POST['password'];

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
	<script type="text/javascript">
		function valid_text(evt) {
			var keyCode = evt.which ? evt.which : evt.keyCode;
			var ban = '@àâäãçéèêëìîïòôöõùûüñ &*?!:;,\t#~"^¨%$£?²¤§%*()[]{}<>|\\/`\'';
			if (ban.indexOf(String.fromCharCode(keyCode)) >= 0) {
				return false;
			}
		}

		function checkFilled() {
			if (document.getElementById("title").value != "") {
				document.getElementById("alertbox").innerHTML = "";
				return true;
			}
			else {
				document.getElementById("alertbox").innerHTML = "<div class=\"alert alert-error\">You havn't filled enough fields !</div>"
				return false;
			}
		}
</script>
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
              			<li class="active"><a href="#">Step 3</a></li>
              			<li><a href="#">Step 4</a></li>
            		</ul>
          		</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div id="alertbox"></div>
		<p><h1>Let's customize your site !</h1></p>
		<p>We want Yjing to be your website. So it needs to fit you !</p>
		<br>
		<form class="form-horizontal" action="step4.php" method="POST" onSubmit="return checkFilled();">
  			<fieldset>
    				<div class="control-group">
    					<label class="control-label">Title of the website : </label>
    						<div class="controls">
    							<input type="text" class="input-xlarge" name="title" onKeypress="return valid_text(event);" id="title">
        						<p class="help-block">Only alphanumeric characters.</p>
      						</div>
    				</div>
    				<div class="control-group">
    					<div class="controls">
    						<input type="submit" class="btn btn-info" value="Continue" />
    					</div>
    				</div>
  			</fieldset>
		</form>

		<hr>
		<footer><p>Yjing is under GNU GPL v3 licence. The Twitter Bootstrap is under Apache Licence v2.0</p></footer>
	</div>

</body>
</html>