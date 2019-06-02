<?php
class website{
	static $_db;
	public function __construct(){
		$this->_db = Db::getInstance();
	}

	public function get($_website){
		(new self)->_db->where("website", $_website);
		return (new self)->_db->get('websites');		
	}
	
	public function getAll($_active = false){
		if($_active){
			(new self)->_db->where("active", 1);
			$return = (new self)->_db->get('websites');
		}else{
			$return = (new self)->_db->get('websites');
		}
		return $return;
	}
	
	public function CountAll(){
		return (new self)->_db->getValue('websites', 'count(*)');			
	}
	
	public function isExists($_website){
		(new self)->_db->where("website", $_website);
		if((new self)->_db->has('websites')){
			return true;
		}else{
			return false;
		}
		return false;
	}
}
?>