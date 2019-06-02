<?php
include '../init.php';

$websites = website::getAll($active = true);

foreach($websites as $data){
	$website = $data['website'];
	$ignoreText = json_decode($data['postIgnoreText'], true);
	$ignoreDiv = json_decode($data['postIgnoreDiv'], true);

	$pageScraper = new scraperHome('http://'.$website, $data['homePagePost'], $data['homePageImage']);
	foreach($pageScraper->getArticlesLink() as $link){
		if(!data::isExists($link[0])){
			data::add($website, $link[2], $link[0], $link[1], $data['category']);
		}
	}
}