  <?php
  ignore_user_abort(true);
  set_time_limit(0);
  class scrapper {

  public $urls = array();
  private $base_url;
  private $page;
  private $check_extension = array();
  private $base;
  private $max_url_limit;
  function __construct( $url ,  $limit , $exclude_extension , $exclude_title ){
	
	 $this->base_url = $url;
	 $this->base = str_replace("http://", "", $url);
	 $this->base = str_replace("https://", "", $this->base);
	 $this->urls[] = $this->base_url;
	 $this->max_url_limit = $limit;
	 update_option('urlc_on' , 'true' );
	 update_option('urlc_invalid' , 'false' );
	 $this->exclude($exclude_extension , $exclude_title);
	 $this->get_links();
	  
	 
  }
  
  public function exclude( $exclude_extension , $exclude_title ) {
       $this->check_extension = array_merge( $exclude_extension , $exclude_title );  
  }
  
  public function get_links() {
	  $i = 0;
	  while ( sizeof($this->urls) < $this->max_url_limit ) {
	  $temp_urls = $this->init($this->urls[$i]);
	  $this->store_links($temp_urls);
	  $i++;
	  }
	  update_option('urlc_on', 'false');
	  
	  	$path = WP_PLUGIN_DIR . 'urlc.csv';
		
		$fp = fopen( $path , 'w');
		fputcsv($fp , $this->urls);
        fclose($fp);
		update_option('urlc_download_session' , 'on' );
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
	update_option('urlc_on', 'false');
	update_option('urlc_invalid' , 'true' );
	exit(0);  
  }
  curl_close($ch);
  $dom = new DOMDocument();
  @$dom->loadHTML($data);
  
  // grab all the on the page
  $xpath = new DOMXPath($dom);
  $hrefs = $xpath->evaluate("/html/body//a");
  $url = array();
  for ($i = 0; $i < $hrefs->length; $i++) {
		 $href = $hrefs->item($i);
		 $url[] = $href->getAttribute('href');
  }
  return $url;
  }
  
  private function validate($url){
		  $valid = true;
		  
		  foreach($this->check_extension as $val)
		  {
			  if(stripos($url, $val) !== false)
			  {
				  $valid = false;
				  break;
			  }
		  }
		  return $valid;
	  }
  
  private function store_links($garbage_url){

		  for($i = 1; $i < sizeof($garbage_url); $i++)
		  {
			  //walking through links
			  foreach($garbage_url as $url)
			  { 
			      if(strpos($url, "http") === false ) {
					//probably a short url
					$url = $this->base_url . $url;  
				  }
				  //if doesn't start with http and is not empty
				  if(strpos($url, "http") === false  && trim($url) !== "")
				  {
					  //checking if absolute path
					  if($url[0] == "/") $url = substr($url, 1);
					  //checking if relative path
					  else if($url[0] == ".")
					  {
						  while($url[0] != "/")
						  {
							  $url = substr($url, 1);
						  }
						  $url = substr($url, 1);
					  }
					  //transforming to absolute url
					  $url = "http://".$this->base."/".$url;
				  }
				  //if new and not empty
				  if(!in_array($url, $this->urls) && trim($url) !== "")
				  {
					  //if valid url
					  if($this->validate($url))
					  {
						  //checking if it is url from our domain
						  if(strpos($url, "http://".$this->base) === 0 || strpos($url, "https://".$this->base) === 0)
						  {
							  //adding url to sitemap array
							  $this->urls[] = $url;
							  //adding url to new link array
							  
						  }
					  }
				  }
			  }
		  }
		  
		  
	  }
  }
