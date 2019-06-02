<?php
class Wp{
	private $_ixr;
	private $_xmlrpc;
	private $_username;
	private $_password;
	private $_title;
	private $_body;
	private $_image;
	
	public function __construct($_xmlrpc, $_username, $_password){
		include_once 'IXR_Client.php';
		$this->_ixr = new IXR_Client($_xmlrpc);
		$this->_xmlrpc = $_xmlrpc;
		$this->_username = $_username;
		$this->_password = $_password;
	}
	
	public function publishPost($title, $body, $image, $category, $allowComments = 1){
		$this->_ixr->debug 	= false;
		$category 		= $category;
		$keywords 		= "";
		$customfields	= array('key' => 'Admin', 'value' => 'Admin');	 
						
		$content = array(
			'title' => $title,
			'description' => $body,
			'mt_allow_comments' => $allowComments,
			'mt_allow_pings' => 0,
			'post_type' => 'post',
			'mt_keywords' => $keywords,
			'categories' => array($category),
			'custom_fields' => array($customfields)
		); 
					
		$params = array(0, $this->_username, $this->_password, $content, false);
		if(!$this->_ixr->query('metaWeblog.newPost', $params)){
			die('Something went wrong - '.$this->_ixr->getErrorCode().' : '.$this->_ixr->getErrorMessage()); 
		}else{
			$post_id = $this->_ixr->getResponse();
			#echo 'Post Published with id'.$post_id;
			//image_name.jpg
			$params['name'] = md5($image). '.jpg'; 
			//type of image
			$params['type'] = 'image/jpg';
			//url or full path of an image
			$params['bits'] =  new IXR_Base64(base64_decode($image));
			//overwrite if exist
			$params['overwrite'] = true;
			if(!$this->_ixr->query('wp.uploadFile', 1, $this->_username, $this->_password, $params)){
				die('Something went wrong - newMediaObject'.$this->_ixr->getErrorCode().' : '.$this->_ixr->getErrorMessage().'');
			}else{
				$media = $this->_ixr->getResponse();
				$content = array(
						'post_status' => 'publish',
						'post_thumbnail' => $media['id']
				);
						
				if(!$this->_ixr->query('wp.editPost', 0, $this->_username, $this->_password, $post_id, $content)){
					die('Something went wrong editPost - '.$this->_ixr->getErrorCode().' : '.$this->_ixr->getErrorMessage().'');
				}	
				$params = array($post_id, $this->_username, $this->_password);
				if(!$this->_ixr->query('metaWeblog.getPost', $params)){
					die('Something went wrong - '.$this->_ixr->getErrorCode().' : '.$this->_ixr->getErrorMessage()); 
				}else{
					#return true;
					$data = $this->_ixr->getResponse();
					return $data['link'];					
				}
			}
		}
		return false;
	}
	
	public function getRecentPosts($num = null){
		$this->_ixr->debug = false;
		$params = array(0, $this->_username, $this->_password, $num);
		
		if(!$this->_ixr->query('metaWeblog.getRecentPosts', $params)){
			 die('Something went wrong - '.$this->_ixr->getErrorCode().' : '.$this->_ixr->getErrorMessage()); 			
		}
		
		if($response = $this->_ixr->getResponse()){
			return $response;
		}else{
			return false;
		}
		return false;
	}
	
	/* Vraca url slike za taj i taj post */
	public function getPostImage($pid)
	{
		$this->_ixr->debug = false;
		$params = array(0, $this->_username, $this->_password, $pid);
		if(!$this->_ixr->query('wp.getMediaItem', $params)){					
			die('Something went wrong - '.$this->_ixr->getErrorCode().' : '.$this->_ixr->getErrorMessage()); 
		}
		$response = $this->_ixr->getResponse();
		return $response['link'];
	}
}
?>