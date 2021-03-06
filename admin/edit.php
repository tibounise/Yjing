<?php

	session_start();

	include("../params.php");
	include("../functions.php");
	include("../langs.php");

	if (isset($_SESSION['connected']) AND !empty($_GET['action']) AND $_SESSION['connected'] == true) {
		$action = $_GET['action'];
		if ($action == "list_article") {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$page = "<h1>" . $choose_an_article_to_edit[$lang] . ".</h1><table class=\"table\"><thead><tr><th>#</th><th>" . base64_decode($title[$lang]) . "</th><th>" . base64_decode($pubdate[$lang]) . "</th><th>" . base64_decode($author[$lang]) . "</th></tr></thead><tbody>";

			foreach ($xml->article as $output) {
				$page .= "<tr>";
				$page .= "<td>" . $output->key . "</td>";
				$page .= "<td><a href=\"edit.php?action=edit_article&article=". $output->key ."\">" . htmlspecialchars_decode(base64_decode($output->title)) . "</a></td>";
				$page .= "<td>" . base64_decode($output->pubdate) . "</td>";
				$page .= "<td>" . base64_decode($output->author) . "</td>";
				$page .= "</tr>";
			}

			$page .= "</tbody></table>";
		}
		elseif ($action == "edit_article" AND !empty($_GET['article']) AND preg_match("#^[0-9]+$#", $_GET["article"])) {
			$article_get = getArticle($_GET['article'],"../".$datafile_url);
			$page = "<h1>" . $edit_an_article[$lang] . "</h1><br /><form class=\"form-horizontal\" action=\"edit.php?action=edit_article_processing\" method=\"POST\"><fieldset>";
			$page .= "<input type=\"hidden\" name=\"id\" value=\"" . $_GET['article'] . "\">";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $name_of_the_article[$lang] . " : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" value=\"" . htmlspecialchars_decode(base64_decode($article_get[0])) . "\" name=\"title\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $author[$lang] . " : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" value=\"" . $article_get[3] . "\" name=\"author\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $pubdate[$lang] . " : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" value=\"" . $article_get[2] . "\" name=\"pubdate\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $content[$lang] . " : </label><div class=\"controls\"><textarea name=\"content\" class=\"span6\" rows=\"15\">" . stripslashes(htmlspecialchars_decode($article_get[1])) . "</textarea></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-success\">" . $save_changes[$lang] . "</button></div></div>";
			$page .= "</fieldset></form>";
		}
		elseif ($action == "edit_article_processing" AND isset($_POST['title']) AND isset($_POST['id']) AND isset($_POST['author']) AND isset($_POST['pubdate']) AND isset($_POST['content'])) {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			foreach ($xml->article as $output) {
				if ($output->key == $_POST['id']) {
					$output->title = base64_encode($_POST['title']);
					$output->author = base64_encode($_POST['author']);
					$output->pubdate = base64_encode($_POST['pubdate']);
					$output->content = base64_encode($_POST['content']);
				}
			}
			
			$buffer = $xml->asXML();
			unlink("../" . $datafile_url);
			$file = fopen("../" . $datafile_url,"w");
			fputs($file,$buffer);
			fclose($file);
			$page = "<p>" . $your_changes_has_been_done[$lang] . " !</p>";
			$page .= "<p>" . $your_article_is_available_at[$lang] . " : <strong><i>index.php?article=" . $_POST['id'] . "</i></strong>.</p>";
			$page .= "<p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
		}
		elseif ($action == "delete_article") {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$page = "<h1>" . $choose_an_article_to_delete_it[$lang] . ".</h1><table class=\"table\"><thead><tr><th class=\"span1\"></th><th>#</th><th>" . $title[$lang] . "</th><th>" . $pubdate[$lang] . "</th><th>" . $author[$lang] . "</th></tr></thead><tbody>";

			foreach ($xml->article as $output) {
				$page .= "<tr>";
				$page .= "<td><a style=\"margin-top: -3px;\" class=\"close\" href=\"edit.php?action=delete_article_confirmation&article=". $output->key ."&token=". $_SESSION['token'] ."\">&times;</a></td>";
				$page .= "<td>" . $output->key . "</td>";
				$page .= "<td><a href=\"edit.php?action=delete_article_confirmation&article=". $output->key ."&token=". $_SESSION['token'] ."\">" . htmlspecialchars(base64_decode($output->title)) . "</a></td>";
				$page .= "<td>" . base64_decode($output->pubdate) . "</td>";
				$page .= "<td>" . base64_decode($output->author) . "</td>";
				$page .= "</tr>";
			}

			$page .= "</tbody></table>";
		}
		elseif ($action == "delete_article_confirmation" AND !empty($_GET['article'])) {

			$page = "<h1>" . $are_you_sure_that_you_want_to_delete_it[$lang] . " ?</h1><br />";
			$page .= "<a class=\"btn btn-danger\" href=\"edit.php?action=delete_article_processing&article=" . $_GET['article'] . "&token=" . $_GET['token'] . "\">" . $continue[$lang] . "</a>&nbsp;";
			$page .= "<a class=\"btn btn-success\" href=\"index.php\">" . $cancel[$lang] . "</a>";
		}
		elseif ($action == "delete_article_processing" AND !empty($_GET['article']) AND $_GET['token'] == $_SESSION['token']) {
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

			$page = "<p>" . $article_deleted[$lang] . ".</p><p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
		}
		elseif ($action == "add_article") {
			$page = "<h1>" . $add_an_article[$lang] . "</h1><br /><form class=\"form-horizontal\" action=\"edit.php?action=add_article_processing\" method=\"POST\"><fieldset>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $name_of_the_article[$lang] . " : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" placeholder=\"" . $name_of_the_article[$lang] . "\" name=\"title\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $author[$lang] . " : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" placeholder=\"" . $author[$lang] . "\" name=\"author\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $pubdate[$lang] . " : </label><div class=\"controls\"><input type=\"text\" class=\"span6\" id=\"title\" placeholder=\"" . $pubdate[$lang] . "\" name=\"pubdate\"></div></div>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $content[$lang] . " : </label><div class=\"controls\"><textarea name=\"content\" class=\"span6\" rows=\"15\" placeholder=\"" . $type_something_here[$lang] . ".\"></textarea></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-success\">" . $save[$lang] . "</button></div></div>";
			$page .= "</fieldset></form>";
		}

		elseif ($action == "add_article_processing") {
			if (!empty($_POST['title']) AND !empty($_POST['author']) AND !empty($_POST['content']) AND !empty($_POST['pubdate'])) {	
				$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));
				
				$id = 1;

				foreach ($xml->article as $output) {
					if ($output->key >= $id) {
						$id = $output->key + 1;
					}
				}

				$article = $xml->addChild("article","");
				$article->addChild("key",$id);
				$article->addChild("author",base64_encode($_POST['author']));
				$article->addChild("title",base64_encode($_POST['title']));
				$article->addChild("pubdate",base64_encode($_POST['pubdate']));
				$article->addChild("content",base64_encode($_POST['content']));
				$buffer = $xml->asXML();
				unlink("../" . $datafile_url);
				$file = fopen("../" . $datafile_url,"w");
				fputs($file,$buffer);
				fclose($file);
				$page = "<p>" . $the_article_has_been_published[$lang] . ".</p>";
				$page .= "<p>" . $your_article_is_available_at[$lang] . " : <strong><i>index.php?article=" . $id . "</i></strong>.</p>";
				$page .= "<p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
			}
			else {
				$page = "<p>" . $you_havnt_filled_some_fields[$lang] . ".</p><p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
			}
		}
		
		elseif ($action == "list_page") {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$page = "<h1>" . $choose_an_page_to_edit[$lang] . ".</h1><table class=\"table\"><thead><tr><th>#</th><th>" . $content[$lang] . "</th></tr></thead><tbody>";

			foreach ($xml->page as $output) {
				$page .= "<tr>";
				$page .= "<td>" . $output->key . "</td>";
				$page .= "<td><a href=\"edit.php?action=edit_page&page=". $output->key ."\">" . stripslashes(strip_tags(substr(htmlspecialchars_decode(base64_decode($output->content)), 0, 140))) . "[...]</a></td>";
				$page .= "</tr>";
			}

			$page .= "</tbody></table>";
		}
		elseif ($action == "edit_page" AND !empty($_GET['page']) AND preg_match("#^[0-9]+$#", $_GET["page"])) {
			$page_get = getPage($_GET['page'],"../".$datafile_url);
			$page = "<h1>" . $edit_an_page[$lang] . "</h1><br /><form class=\"form-horizontal\" action=\"edit.php?action=edit_page_processing\" method=\"POST\"><fieldset>";
			$page .= "<input type=\"hidden\" name=\"id\" value=\"" . $_GET['page'] . "\">";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $content[$lang] . " : </label><div class=\"controls\"><textarea name=\"content\" class=\"span6\" rows=\"15\">" . stripslashes(htmlspecialchars_decode(base64_decode($page_get[0]))) . "</textarea></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-success\">" . $save_changes[$lang] . "</button></div></div>";
			$page .= "</fieldset></form>";
		}
		elseif ($action == "edit_page_processing" AND isset($_POST['id']) AND isset($_POST['content'])) {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			foreach ($xml->page as $output) {
				if ($output->key == $_POST['id']) {
					$output->content = base64_encode($_POST['content']);
				}
			}
			
			$buffer = $xml->asXML();
			unlink("../" . $datafile_url);
			$file = fopen("../" . $datafile_url,"w");
			fputs($file,$buffer);
			fclose($file);
			$page = "<p>" . $your_changes_has_been_done[$lang] . " !</p>";
			$page .= "<p>" . $your_article_is_available_at[$lang] . " : <strong><i>index.php?page=" . $_POST['id'] . "</i></strong>.</p>";
			$page .= "<p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
		}
		elseif ($action == "delete_page") {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$page = "<h1>" . $choose_an_page_to_delete_it[$lang] . ".</h1><table class=\"table\"><thead><tr><th class=\"span1\"></th><th>#</th><th>" . $content[$lang] . "</th></tr></thead><tbody>";

			foreach ($xml->page as $output) {
				$page .= "<tr>";
				$page .= "<td><a style=\"margin-top: -3px;\" class=\"close\" href=\"edit.php?action=delete_page_confirmation&page=". $output->key ."&token=". $_SESSION['token'] ."\">&times;</a></td>";
				$page .= "<td>" . $output->key . "</td>";
				$page .= "<td><a href=\"edit.php?action=delete_page_confirmation&page=". $output->key ."&token=". $_SESSION['token'] ."\">" . stripslashes(strip_tags(substr(htmlspecialchars_decode(base64_decode($output->content)), 0, 140))) . "[...]</a></td>";
				$page .= "</tr>";
			}

			$page .= "</tbody></table>";
		}
		elseif ($action == "delete_page_confirmation" AND !empty($_GET['page'])) {
			if ($_GET['page'] == 1) {
				$page = "<p>" . $you_cant_delete_the_first_page[$lang] . " !</p>";
				$page .= "<p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
			}
			else {
				$page = "<h1>" . $are_you_sure_that_you_want_to_delete_it[$lang] . " ?</h1><br />";
				$page .= "<a class=\"btn btn-danger\" href=\"edit.php?action=delete_page_processing&page=" . $_GET['page'] . "&token=" . $_GET['token'] . "\">" . $continue[$lang] . "</a>&nbsp;";
				$page .= "<a class=\"btn btn-success\" href=\"index.php\">" . $cancel[$lang] . "</a>";
			}
		}
		elseif ($action == "delete_page_processing" AND !empty($_GET['page']) AND $_GET['token'] == $_SESSION['token']) {
			$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));

			$i = 0;

			foreach ($xml->page as $output) {
				if ($output->key == $_GET['page']) {
					unset($xml->page[$i]); break;
				}
				$i++;
			}

			$buffer = $xml->asXML();
			unlink("../" . $datafile_url);
			$file = fopen("../" . $datafile_url,"w");
			fputs($file,$buffer);
			fclose($file);

			$page = "<p>" . $page_deleted[$lang] . ".</p><p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
		}
		elseif ($action == "add_page") {
			$page = "<h1>" . $add_a_page[$lang] . "</h1><br /><form class=\"form-horizontal\" action=\"edit.php?action=add_page_processing\" method=\"POST\"><fieldset>";
			$page .= "<div class=\"control-group\"><label class=\"control-label\">" . $content[$lang] . " : </label><div class=\"controls\"><textarea name=\"content\" class=\"span6\" rows=\"15\" placeholder=\"" . $type_something_here[$lang] . ".\"></textarea></div></div>";
			$page .= "<div class=\"control-group\"><div class=\"controls\"><button type=\"submit\" class=\"btn btn-success\">" . $save[$lang] . "</button></div></div>";
			$page .= "</fieldset></form>";
		}

		elseif ($action == "add_page_processing") {
			if (!empty($_POST['content'])) {	
				$xml = new simpleXMLElement(file_get_contents("../" . $datafile_url));
				
				$id = 1;

				foreach ($xml->page as $output) {
					if ($output->key >= $id) {
						$id = $output->key + 1;
					}
				}

				$article = $xml->addChild("page","");
				$article->addChild("key",$id);
				$article->addChild("content",base64_encode($_POST['content']));
				$buffer = $xml->asXML();
				unlink("../" . $datafile_url);
				$file = fopen("../" . $datafile_url,"w");
				fputs($file,$buffer);
				fclose($file);
				$page = "<p>" . $the_page_has_been_published[$lang] . ".</p>";
				$page .= "<p>" . $your_page_is_available_at[$lang] . " : <strong><i>index.php?page=" . $id . "</i></strong>.</p>";
				$page .= "<p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
			}
			else {
				$page = "<p>" . $you_havnt_filled_some_fields[$lang] . ".</p><p><a href=\"index.php\" class=\"btn btn-warning\">" . $return_to_index[$lang] . "</a></p>";
			}
		}
		else {
			header("Location: index.php");
		}

		include("design.html");
	}

	else {
		header("Location: login.php");
	}

?>