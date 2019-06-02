<?php
class data{
	static $_db;
	public function __construct(){
		$this->_db = Db::getInstance();
	}

	public function getAll(){
		(new self)->_db->orderBy ("dateAdded", "desc");
		return (new self)->_db->get('scrapedhomepage');		
	}
	
	public function add($_website, $_postTitle, $_postLink, $_postImage, $_postCategory){
		$data = Array(
				"website" => $_website,
				"postTitle" => $_postTitle,
				"postLink" => $_postLink,
				"postImage" => $_postImage,
				"postCategory" => $_postCategory
			);
		$id = (new self)->_db->insert('scrapedhomepage', $data);
	}
	
	public function isExists($_postLink){
		(new self)->_db->where("postLink", $_postLink);
		if((new self)->_db->has('scrapedhomepage')){
			return true;
		}else{
			(new self)->_db->where("postLink", $_postLink);
			if((new self)->_db->has('scrapedhomepage_used')){
				return true;
			}
			return false;
		}
		return false;
	}
	
	public function move($_postLink){
		if(data::isExists($_postLink)){
			(new self)->_db->where("postLink", $_postLink);
			$data = (new self)->_db->get('scrapedhomepage');
			if($data){
				unset($data[0]['ID']);
				$data2 = (new self)->_db->insert('scrapedhomepage_used', $data[0]);
				if($data2){
					(new self)->_db->where('postLink', $_postLink);
					if((new self)->_db->delete('scrapedhomepage')){
						return true;
					}
				}
			}
		}
		return false;
	}
}
?>