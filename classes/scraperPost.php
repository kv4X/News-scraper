<?php
class ScraperPost extends simple_html_dom{ 
	private $_url;
	private $_html;
	private $_html2;
	private $_title;
	private $_content;
	private $_image;
	private $_titleSelector;
	private $_contentSelector;
	private $_imageSelector;
	private $_ignoreText;
	private $_ignoreDiv;
	
	public function __construct($_url, $_titleSelector = null, $_contentSelector = null, $_imageSelector = null, $_ignoreText = null, $_ignoreDiv = null){
		parent::__construct();
		$this->_url = $_url;
		$this->_titleSelector = $_titleSelector;
		$this->_contentSelector = $_contentSelector;
		$this->_imageSelector = $_imageSelector;
		$this->_ignoreText = $_ignoreText;
		$this->_ignoreDiv = $_ignoreDiv;
		$this->_FGT = file_get_contents($this->_url);
		$this->_html = $this->load($this->_FGT);
	}
	
	public function getArticleTitle(){
		$this->_title = $this->_html->find($this->_titleSelector, 0)->innertext;
		$this->_title = str_replace($this->_ignoreText, '', $this->_title);
		return $this->_title;
	}
	
	public function getArticleContent(){
		$this->_content = '';
		foreach($this->_html->find($this->_contentSelector) as $element){
			
			#REMOVE DIVS
			foreach($this->_ignoreDiv as $ignore){
				foreach($this->_html->find($this->_contentSelector.' '.$ignore) as $delete){
					$delete->outertext = '';
				}
			}
			

			#ADSENSE CODE REMOVER
			foreach($element->find("script[src=//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js]") as $delete){$delete->outertext = '';}
			foreach($element->find("script[src=//pagead2.googlesyndication.com/pagead/show_ads.js]") as $delete){$delete->outertext = '';}
			foreach($element->find("script[src=/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js]") as $delete){$delete->outertext = '';}
			foreach($element->find("script[src=https://apis.google.com/js/platform.js]") as $delete){$delete->outertext = '';}
			foreach($element->find("script[src=//cdn.popcash.net/pop.js]") as $delete){$delete->outertext = '';}
			foreach($element->find("script[src=//st-n.ads1-adnow.com/js/a.js]") as $delete){$delete->outertext = '';}
			foreach($element->find("ins") as $delete){$delete->outertext = '';}
			foreach($element->find("script") as $delete){if(strpos($delete->innertext, 'adsbygoogle') !== false){$delete->outertext = '';}}
			#ADNOW
			foreach($element->find("script") as $delete){if(strpos($delete->innertext, 'sc_adv_out') !== false){$delete->outertext = '';}}
			foreach($element->find("script") as $delete){if(strpos($delete->src, 'adnow') !== false){$delete->outertext = '';}}
			
			#BLANK DIVS REMOVER
			foreach($element->find('div') as $delete){if(trim($delete->innertext) == ''){$delete->outertext = '';}}
			foreach($element->find("noscript") as $delete){$delete->outertext = '';}

			$this->removeAttributes($element, 'div');
			$this->removeAttributes($element, 'figure');
			$this->removeAttributes($element, 'a');
			$this->removeAttributes($element, 'span');
			$this->removeAttributes($element, 'i');
		
			/* PREBACUJE ELEMENT U PARENT ELEMENT
			$imgs = $element->find('img');
			foreach($imgs as $img) {
				$img2 = $img->outertext;
				$img->outertext = '';
				$img->parent()->outertext = $img2 . $img->parent()->outertext;
				
			}
			*/
			
			
			$data = $element->innertext;
			
			#URL SLIKE U BASE64 KOD
			$data = preg_replace_callback(
							"/(<img\\s)[^>]*src=(\\S+)[^>]*(\\/?>)/m",
							function($match){$gogo = str_replace('"', '', $match[2]); return '<img src="data:image/png;base64,'.$this->getImageBase64Encode($gogo).'">';},
							$data
						);
			
			#REMOVE IGNORE TEXT
			foreach($this->_ignoreText as $ignore){
				$data = str_replace($ignore, '', $data);
			}
			
			$data = preg_replace("#<ul[^>]*>.*?<\/ul>#is", '', $data);
			$data = preg_replace("#<!--*>.*?-->#is", '', $data);
			$data = preg_replace("#<ul[^>]*>#is", '', $data);
			$data = preg_replace("#<p>&nbsp;</p>#is", '', $data);
		#	$data = preg_replace("#<div[^>]*>#is", '', $data);
		#	$data = preg_replace("#</div[^>]*>#is", '', $data);
			$this->_content .= $data;
		}
		return $this->_content;
	}
	
	public function getArticleImage($base64){
		if($this->_imageSelector == 'og:image'){
			$image = $this->_html->find("head meta[property='og:image']", 0)->content;
		}else{
			$image = $this->_html->find($this->_imageSelector, 0)->src;
		}
		
		if($base64){
			$image = base64_encode(file_get_contents($image));
		}
		return $image;
	}
	
	public static function getImageBase64Encode($gogo){
		$url = @file_get_contents($gogo);
		if($url){
			return base64_encode($url);
		}else{
			#return base64_encode(file_get_contents('http://pixup.us/upload/small/2013/06/04/51ad91814dd33.jpg'));
		}
	}
	
	public static function removeAttributes($element, $tagName){
		$divs = $element->getElementsByTagName($tagName);
		foreach($divs as $div){
			foreach ($div->getAllAttributes() as $attr => $val) {
				$div->removeAttribute($attr);
			}  
		}
		return $element;
	}
}
?>