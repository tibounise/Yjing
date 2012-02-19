<?php

include "params.php";
include "functions.php";
include "themes/" . $actual_theme . "/theme.php";

$site_infos = getSiteInfos($datafile_url);

if (preg_match("#^[0-9]+$#", $_GET["article"])) {

	$article = getArticle($_GET["article"], $datafile_url);

	echo showArticle($site_infos[0],$navigation_bar_content,$article[0],$article[2],$article[3],$article[1]);

}

else {

	$page = getPage("1", $datafile_url);

	echo showPage($site_infos[0],$navigation_bar_content,$page[0]);

}

?> 