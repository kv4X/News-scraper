<?php
include 'init.php';

if(isSet($_POST['link']) && !empty($_POST['link'])){
	$scrape = $_POST['link'];
	
	$domain = parse_url($scrape)['host'];
	$data = website::get($domain)[0];
	
	$website = $data['website'];
	$ignoreText = json_decode($data['postIgnoreText'], true);
	$ignoreDiv = json_decode($data['postIgnoreDiv'], true);

	$pageScraper = new scraperHome('http://'.$website, $data['homePagePost'], $data['homePageImage']);
	foreach($pageScraper->getArticlesLink() as $link){
		if(!data::isExists($link[0])){
			echo '<pre>';
			echo 'Title: '.$link[2].'<br>';
			echo 'Image: '.$link[0].'<br>';
			echo 'Body: '.$link[1].'<br>----------';
			echo '</pre>';
		}
	}
}
?>
<form method="POST">
	<label for="link">Link sajta: </label><input id="link" type="text" name="link"><br>
	<input type="submit" value="Objavi me!"><br>
</form>
