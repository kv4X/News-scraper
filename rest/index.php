	<?php
include '../init.php';
	
if(isSet($_GET)){
	$action = $_GET['action'];
	$allowed = array("getAll", "delete", "publish");

#CHECK IS ACTION EMPTY OR
	if(!empty($action)){
#CHECK IS ACTION IN ALLOWD ARRAY
		if(in_array($action, $allowed)){
#GETALL SCRAPED LINKS
			if($action == 'getAll'){
				$response['status']=200;
				$response['status_message']='Found';
				$response['data']=data::getAll();
#DELETE SCRAPED LINK
			}else if($action == 'delete'){
				if(isSet($_GET['postLink'])){
					if(data::move($_GET['postLink'])){
						$response['status']=200;
						$response['status_message']='Deleted';
						$response['data']='';
					}else{
						$response['status']=404;
						$response['status_message']='Post not found';
						$response['data']='';						
					}
				}
#PUBLISH SCRAPED LINK
			}else if($action == 'publish'){
				if(!empty($_GET['postLink'])){
					if(isSet($_GET['postLink'])){
						$scrape = $_GET['postLink'];
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
								if($wp->publishPost($postTitle, $postContent.'<br>(Izvor: <a target="_blank" href="'.$scrape.'">'.$domain.'</a>)', $postImage, $postCategory, $allowComments = 1)){
										$response['status']=200;
										$response['status_message']='Posted';
										$response['data']=array('title' => $postTitle, 'content' => $postContent, 'image' => $postImage, 'category' => $postCategory);
									/*if(data::move($_GET['postLink'])){
										$response['status']=200;
										$response['status_message']='Posted';
										$response['data']=array($postTitle, $postContent, $postImage, $postCategory);
									}
									*/
								}			
							}else{
								$response['status']=404;
								$response['status_message']='Error';
								$response['data']='';								
							}
						}else{
							$response['status']=404;
							$response['status_message']='Site not found';
							$response['data']='';	
						}
					}
				}else{
					$response['status']=404;
					$response['status_message']='Parameter postLink is empty';
					$response['data']='';	
				}
			}
		}else{
			$response['status']=404;
			$response['status_message']='Action not found';
			$response['data']='';			
		}
	}else{
		$response['status']=404;
		$response['status_message']='Action not found';
		$response['data']='';
	}
	
	if(isSet($response)){
		response($response);
	}
}


function response($response){
	header("HTTP/1.1 ".$response['status']);
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	
	$json_response = json_encode($response, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
	echo $json_response;
}