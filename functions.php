<?php

function getArticle($key,$database) {

	$xml = new simpleXMLElement(file_get_contents($database));

	foreach ($xml->article as $output) {
		if ($output->key == $key) {
			return array(0 => $output->title, 1 => $output->content, 2 => $output->pubdate, 3 => $output->author);
		}
	}
}

function getPage($key,$database) {

	$xml = new simpleXMLElement(file_get_contents($database));

	// $xml->author[0];

	foreach ($xml->page as $output) {
		if ($output->key == $key) {
			return array(0 => $output->content);
		}
	}
}

function getSiteInfos($database) {

	$xml = new simpleXMLElement(file_get_contents($database));

	return array(0 => $xml->config->title[0], 1 => $xml->config->url[0]);
}

function openFile($url) {
	if (file_exists($url)) {
		$file = fopen($url,"r");
		$output = "";
		while (!feof ($file))
			{
				$buffer1 = fgets($file, 4096);
				$buffer2 = $output . $buffer1;
				$output = $buffer2;
  			}
  		fclose($file);
  	}
  	else {
  		$output = false;
  	}
	return $output;
}

?>