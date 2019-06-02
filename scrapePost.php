<?php
include 'init.php';

if(isSet($_POST['link']) && !empty($_POST['link'])){
	$scrape = $_POST['link'];
	
	$domain = parse_url($scrape)['host'];
	$domain = str_replace("www.", "", $domain);
	if(website::isExists($domain)){
		$data = website::get($domain)[0];
		$ignoreText = json_decode($data['postIgnoreText'], true);
		$ignoreDiv = json_decode($data['postIgnoreDiv'], true);

		$postScraper = new scraperPost($scrape, $data['postTitle'], $data['postContent'], $data['postImage'], $ignoreText, $ignoreDiv);
		
		$postTitle = $postScraper->getArticleTitle();
		$postImage = $postScraper->getArticleImage($base64 = true);
		$postContent = $postScraper->getArticleContent();
		$postCategory = $data['category'];
		
		if(!empty($postTitle)){
			if($wp->publishPost($postTitle, $postContent.'<br>(Preuzeto sa: <a target="_blank" href="'.$scrape.'">'.$domain.'</a>)', $postImage, $postCategory, $allowComments = 1)){
				echo '<pre>';
				echo 'Title: '.$postTitle.'<br>';
				#echo 'Image: '.$image.'<br>';
				echo 'Body: '.$postContent.'<br>';
				echo '</pre>';
			}			
		}
	}else{
		die('Stranica sa koje pokusavate da preuzmete sadrzaj nije podesena!');
	}
}
?>
<form method="POST">
	<label for="link">Link Posta: </label><input id="link" type="text" name="link"><br>
	<input type="submit" value="Objavi me!"><br>
</form>

