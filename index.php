<?php

include "params.php";
include "functions.php";

if (preg_match("#^[0-9]+$#", $_GET["article"])) {

	$output = getSiteInfos($datafile_url);

	$title_site = $output[0];

	$output = getArticle($_GET["article"], $datafile_url);

	$article_title = $output[0];
	$article_content = html_entity_decode($output[1]);
	$article_pubdate = $output[2];
	$article_author = $output[3];

	include "themes/" . $actual_theme . "/article.html";

}

else {

	$output = getSiteInfos($datafile_url);

	$title_site = $output[0];

	$output = getPage("1", $datafile_url);

	$article_content = html_entity_decode($output[0]);

	include "themes/" . $actual_theme . "/index.html";

}

?> 