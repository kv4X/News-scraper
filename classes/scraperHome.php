<?php
class scraperHome extends simple_html_dom{ 
	private $_url;
	private $_content;
	private $_postLinks;
	private $_linkSelector;
	private $_imageSelector;
	
	public function __construct($_url, $_linkSelector = null, $_imageSelector = null){
		parent::__construct();
		$this->_url = $_url;
		$this->_linkSelector = $_linkSelector;
		$this->_imageSelector = $_imageSelector;
		$this->_content = $this->load(file_get_contents($this->_url));
	}
	
	#DAJE NAM SVE LINKOVE POSTOVA SA POCETNE STRANICE
	public function getArticlesLink(){
		$links = array();
		$i = -1;
		foreach($this->_content->find($this->_linkSelector) as $element){
			$i++;
			$links[] = array($element->href, $this->getArticlesImage()[$i], $this->getArticlesTitle()[$i]);
		}
		return $links;
	}
	
	#DAJE NAM SVE SLIKE OD POSTOVA SA POCETNE STRANICE
	public function getArticlesImage(){
		$images = array();
		foreach($this->_content->find($this->_imageSelector) as $element) 
			$images[] = $element->src;
	
		return $images;	
	}
	
	public function getArticlesTitle(){
		$titles = array();
		foreach($this->_content->find($this->_linkSelector) as $element)
			$titles[] = $element->innertext;

		return $titles;
	}	
	
}
?>