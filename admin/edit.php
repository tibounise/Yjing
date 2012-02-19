<?php

	session_start();

	include("../params.php");
	include("../functions.php");

	if (isset($_SESSION['connected']) AND !empty($_GET['action']) AND $_SESSION['connected'] == true) {
		$action = $_GET['action'];
		if ($action == "list_article") {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$page = "<h1>Choose an article to edit.</h1><table class=\"table\"><thead><tr><th>#</th><th>Title</th><th>Pubdate</th><th>Author</th></tr></thead><tbody>";

			foreach ($xml->article as $output) {
				$page .= "<tr>";
				$page .= "<td>" . $output->key . "</td>";
				$page .= "<td><a href=\"edit.php?action=edit_article&article=". $output->key ."\">" . $output->title . "</a></td>";
				$page .= "<td>" . $output->pubdate . "</td>";
				$page .= "<td>" . $output->author . "</td>";
				$page .= "</tr>";
			}

			$page .= "</tbody></table>";
		}
		elseif ($action == "edit_article" AND !empty($_GET['article']) AND preg_match("#^[0-9]+$#", $_GET["article"])) {
			$article = getArticle($_GET['article'],"../".$datafile_url);
			$page = "<form class=\"form-horizontal\" action=\"edit.php?edit_article_processing\" method=\"POST\"><fieldset>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\" for=\"title\">Name of the article : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" value=\"" . $article[0] . "\"></div></div>";
			$page .= "</fieldset></form>";
		}
		include("design.html");
	}

	else {
		header("Location: login.php");
	}

?>