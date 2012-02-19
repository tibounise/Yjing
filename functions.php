<?php

function getArticle($key,$database) {
	$file = curl_init();
	curl_setopt($file,CURLOPT_URL, $database);
	curl_setopt($file,CURLOPT_RETURNTRANSFER, true);
	$content = curl_exec($file);

	$xml = new simpleXMLElement($content);

	foreach ($xml->article as $output) {
		if ($output->key == $key) {
			return array(0 => $output->title, 1 => $output->content, 2 => $output->pubdate, 3 => $output->author);
		}
	}
}

function getPage($key,$database) {
	$file = curl_init();
	curl_setopt($file,CURLOPT_URL, $database);
	curl_setopt($file,CURLOPT_RETURNTRANSFER, true);
	$content = curl_exec($file);

	$xml = new simpleXMLElement($content);

	// $xml->author[0];

	foreach ($xml->page as $output) {
		if ($output->key == $key) {
			return array(0 => $output->content);
		}
	}
}

function getSiteInfos($database) {
	$file = curl_init();
	curl_setopt($file,CURLOPT_URL, $database);
	curl_setopt($file,CURLOPT_RETURNTRANSFER, true);
	$content = curl_exec($file);

	$xml = new simpleXMLElement($content);

	return array(0 => $xml->title[0], 1 => $xml->url[0]);
}

function openFile($url) {
	if (file_exists($url)) {
		$file = fopen($url,"r");
		while (!feof ($file))
			{
				$buffer = fgets($file, 4096);
				$output = $output . $buffer;
  			}
  		fclose($file);
  	}
  	else {
  		$output = false;
  	}
	return $output;
}

?>