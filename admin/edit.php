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
				$page .= "<td><a href=\"edit.php?action=edit_article&article=". $output->key ."\">" . html_entity_decode($output->title) . "</a></td>";
				$page .= "<td>" . $output->pubdate . "</td>";
				$page .= "<td>" . $output->author . "</td>";
				$page .= "</tr>";
			}

			$page .= "</tbody></table>";
		}
		elseif ($action == "edit_article" AND !empty($_GET['article']) AND preg_match("#^[0-9]+$#", $_GET["article"])) {
			$article = getArticle($_GET['article'],"../".$datafile_url);
			$page = "<h1>Edit an article</h1><br /><form class=\"form-horizontal\" action=\"edit.php?action=edit_article_processing\" method=\"POST\"><fieldset>";
			$page .= "<input type=\"hidden\" name=\"id\" value=\"" . $_GET['article'] . "\">";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">Name of the article : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" value=\"" . html_entity_decode($article[0]) . "\" name=\"title\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">Author : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" value=\"" . $article[3] . "\" name=\"author\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">Pubdate : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" value=\"" . $article[2] . "\" name=\"pubdate\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">Content : </label><div class=\"controls\"><textarea name=\"content\" class=\"span6\" rows=\"15\">" . html_entity_decode($article[1]) . "</textarea></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-success\">Save changes</button></div></div>";
			$page .= "</fieldset></form>";
		}
		elseif ($action == "edit_article_processing" AND isset($_POST['title']) AND isset($_POST['id']) AND isset($_POST['author']) AND isset($_POST['pubdate']) AND isset($_POST['content'])) {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			foreach ($xml->article as $output) {
				if ($output->key == $_POST['id']) {
					$output->title = htmlentities($_POST['title']);
					$output->author = $_POST['author'];
					$output->pubdate = $_POST['pubdate'];
					$output->content = htmlentities($_POST['content']);
				}
			}
			
			$buffer = $xml->asXML();
			unlink("../" . $datafile_url);
			$file = fopen("../" . $datafile_url,"w");
			fputs($file,$buffer);
			fclose($file);
			$page = "<p>Your changes has been done !</p><p><a href=\"index.php\" class=\"btn btn-warning\">Return to index</a></p>";
		}
		elseif ($action == "delete_article") {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$page = "<h1>Choose an article to delete it.</h1><table class=\"table\"><thead><tr><th>#</th><th>Title</th><th>Pubdate</th><th>Author</th></tr></thead><tbody>";

			foreach ($xml->article as $output) {
				$page .= "<tr>";
				$page .= "<td>" . $output->key . "</td>";
				$page .= "<td><a href=\"edit.php?action=delete_article_confirmation&article=". $output->key ."\">" . html_entity_decode($output->title) . "</a></td>";
				$page .= "<td>" . $output->pubdate . "</td>";
				$page .= "<td>" . $output->author . "</td>";
				$page .= "</tr>";
			}

			$page .= "</tbody></table>";
		}
		elseif ($action == "delete_article_confirmation" AND !empty($_GET['article'])) {

			$page = "<h1>Are you sure that you want to delete it ?</h1><br />";
			$page .= "<a class=\"btn btn-danger\" href=\"edit.php?action=delete_article_processing&article=" . $_GET['article'] . "\">Continue</a>&nbsp;";
			$page .= "<a class=\"btn btn-success\" href=\"index.php\">Cancel</a>";
		}
		elseif ($action == "delete_article_processing" AND !empty($_GET['article'])) {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$i = 0;

			foreach ($xml->article as $output) {
				if ($output->key == $_GET['article']) {
					unset($xml->article[$i]); break;
				}
				$i++;
			}

			$buffer = $xml->asXML();
			unlink("../" . $datafile_url);
			$file = fopen("../" . $datafile_url,"w");
			fputs($file,$buffer);
			fclose($file);

			$page = "<p>Article deleted.</p><p><a href=\"index.php\" class=\"btn btn-warning\">Return to index</a></p>";
		}

		include("design.html");
	}

	else {
		header("Location: login.php");
	}

?>