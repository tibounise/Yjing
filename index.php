<?php

include "params.php";
include "functions.php";

$site_infos = getSiteInfos($datafile_url);

include "themes/" . $site_infos[2] . "/theme.php";

if (isset($_GET['article']) AND preg_match("#^[0-9]+$#", $_GET["article"])) {

	$article = getArticle($_GET["article"], $datafile_url);

	echo showArticle($site_infos[0],$site_infos[1],$article[0],$article[2],$article[3],$article[1]);

}

elseif (isset($_GET["page"]) AND preg_match("#^[0-9]+$#", $_GET["page"])) {

	$page = getArticle($_GET["page"], $datafile_url);

	echo showPage($site_infos[0],$site_infos[1],$page[0]);

}

else {

	$page = getPage("1", $datafile_url);

	echo showPage($site_infos[0],$site_infos[1],$page[0]);

}

?> 