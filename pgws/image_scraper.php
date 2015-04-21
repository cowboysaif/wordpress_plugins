<?php 

class image_scraper {
	 public  $image_urls;
	 private $base_url;
	 private $page;
	 private $progress;
	 private $min_height;
	 private $min_width;
	 function __construct( $url  , $height , $width ){
		  
		 $this->base_url = $url;
		 $this->min_height = $height;
		 $this->min_width = $width;
		 $this->get_image_links();
		 $this->screen_images();
		 
	  }
	  
	  function get_image_links() {
		  $this->image_urls = $this->init($this->base_url);
	  }
	  private function init($url) {
	  $ch = curl_init();
	
	  curl_setopt($ch,CURLOPT_URL,$this->base_url);
	  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	  $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	  curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	  curl_setopt($ch, CURLOPT_FAILONERROR, true);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	  curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	  curl_setopt($ch, CURLOPT_TIMEOUT, 400);

		$data = curl_exec($ch);
	  if ( $data == false ) {
	  exit(0);  
	  }
		curl_close($ch);
	  $dom = new DOMDocument();
	 
	  @$dom->loadHTML($data);
	  
	  // grab all the on the page
	  $xpath = new DOMXPath($dom);
	  $hrefs = $xpath->evaluate("/html/body//img");
	  
	  $url = array();
	  for ($i = 0; $i < $hrefs->length; $i++) {
			 $href = $hrefs->item($i);
			 $url[] = $href->getAttribute('src');
	  }
	  return $url;
	  }
	  
	  function screen_images() {
		  $temp_image_urls = array();
		  for ( $i = 0 ; $i < sizeof($this->image_urls) ; $i++ ) {
		  		list($width, $height, $type, $attr) = getimagesize($this->image_urls[$i]);
				
				if ( $width >= $this->min_width && $height >= $this->min_height  ) {
				$temp_image_urls[] = $this->image_urls[$i];
				}
		  }
		  $this->image_urls = NULL;
		  $this->image_urls = $temp_image_urls;
	  }
	  function export_urls() {
		  return $this->image_urls;
	  }
}

?>

