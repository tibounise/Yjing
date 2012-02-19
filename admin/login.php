<?php
	session_start();

	include("../params.php");

	if ($_SESSION['connected'] AND $_GET['action'] == "logout") {
		session_destroy();
		header("Location: index.php");
	}

	elseif ($_SESSION['connected']) {
		header("Location: index.php");
	}

	elseif ($_POST['username'] != "" AND $_POST['password'] !="") {
		if ($_POST['username'] == $login[0] AND md5($_POST['password']) == $login[1]) {
			$_SESSION['connected'] = true;
			$_SESSION['try'] = 0;
			header("Location: index.php");
		}
		else {
			$_SESSION['try'] = $_SESSION['try'] + 1;
			header("Location: login.php?error=badlogin");
		}

	}

	elseif ($_POST['submit'] AND $_POST['username'] == "" OR $_POST['password'] == "") {
		header("Location: login.php?error=notenough");
	}

	else {
?>
<!doctype html>
	<html>
		<head>
			<title>Yjing - Login</title>
			<style type="text/css" media="screen">
				body {
					margin: 100px;
					background-color: #CCC;
					font-family: Arial,Helvetica,sans-serif;
					font-size: 10pt;
				}
				#form {
					-webkit-border-bottom-left-radius: 25px;
					-moz-border-radius-bottomleft: 25px;
					border-bottom-left-radius: 25px;
					background-color: #507e8c;
					padding: 50px;
					width: 320px;
					text-align: center;
					margin: auto;
				}
				#form p {
					color: white;
				}
				#caption {
					-webkit-border-top-right-radius: 25px;
					-moz-border-radius-topright: 25px;
					border-top-right-radius: 25px;
					width: 418px;
					text-align: center;
					margin: auto;
					padding: 1px;
					background-color: #EEE;
				}
				.error {
					color: red;
				}
			</style>
		</head>
		<body>
			<?php
				if ($_SESSION['try'] < $numberoftry) {
			?>
			<div id="caption">
			<p>
				<?php
					if ($_GET["error"] == "notenough") {
						echo "You didn't have filled enough fields.";
					}
					elseif ($_GET["error"] == "badlogin") {
						echo "Your login is wrong.";
					}
					elseif ($_GET["error"] == "bear") {
						echo "Your face is bear.";
					}
					else {
						echo "Login";
					}
				?>
			</p>
			</div>
			<form action="login.php" method="POST">
				<div id="form">
					<p>Username : <input type="text" name="username" /></p>
					<p>Password : <input type="password" name="password" /></p>
					<input type="submit" value="Log in !" />
				</div>
			</form>
			<?php
				}
				else {
			?>
			<div id="caption" class="error"><p>ERROR</p></div>
			<div id="form"><p class="alert">You've tried too much logins.</p></form>
			<?php
				}
			?>
		</body>
	</html>
<?php
	}
?>