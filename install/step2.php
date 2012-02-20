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
			if (document.getElementById("username").value != "" && document.getElementById("password").value != "") {
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
              			<li class="active"><a href="#">Step 2</a></li>
              			<li><a href="#">Step 3</a></li>
              			<li><a href="#">Step 4</a></li>
            		</ul>
          		</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div id="alertbox"></div>
		<p><h1>Give us a login !</h1></p>
		<p>When you will want to create articles or pages, you'll need to log on the back-office. But to protect the back-office, you'll need to set a login.</p>
		<br>
		<form class="form-horizontal" action="step3.php" method="POST" onSubmit="return checkFilled();">
  			<fieldset>
    				<div class="control-group">
    					<label class="control-label">Username : </label>
    						<div class="controls">
    							<input type="text" class="input-xlarge" name="username" onKeypress="return valid_text(event);" id="username">
        						<p class="help-block">Only alphanumeric characters.</p>
      						</div>
    				</div>
    				<div class="control-group">
    					<label class="control-label">Password : </label>
    						<div class="controls">
    							<input type="password" class="input-xlarge" name="password" onKeypress="return valid_text(event);" id="password">
        						<p class="help-block">Only alphanumeric characters too.</p>
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