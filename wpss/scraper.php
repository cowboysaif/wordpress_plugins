  <?php
	  ignore_user_abort(true);
	  set_time_limit(0);
	  class scraper {
	
	  public $urls = array();
	  private $base_url;
	  private $page;
	  private $check_extension = array();
	  private $base;
	  private $max_url_limit;
	  private $similar_word_flag;
	  private $progress;
	  function __construct( $url ,  $limit , $exclude_extension , $exclude_word , $similar_word_flag  ){
		  
		 $this->base_url = $url;
		 $this->progress = 0;
		 $this->similar_word_flag = $similar_word_flag;
		 $this->base = str_replace("http://", "", $url);
		 $this->base = str_replace("https://", "", $this->base);
		 $this->urls[] = $this->base_url;
		 $this->max_url_limit = $limit;
		 file_put_contents("../../plugins/wpss/wpss_on" , 'true' );
		 $this->exclude($exclude_extension , $exclude_word );
		 $this->get_links();
		  
		 
	  }
	  
	  public function exclude( $exclude_extension , $exclude_title ) {
		   $this->check_extension = array_merge( $exclude_extension , $exclude_title );  
	  }
	  
	  public function get_links() {
		$i = 0;
		while ( $i < $this->max_url_limit ) {
		$this->progress = intval(($i/$this->max_url_limit) * 100 );
		file_put_contents("../../plugins/wpss/wpss_progress" , $this->progress);
		$temp_urls = $this->init($this->urls[$i]);
		$this->store_links($temp_urls);
		echo $i;
		$i++;
		}
		    file_put_contents("../../plugins/wpss/wpss_on", 'false');
			$path = 'wpss-'. date('Y-m-d') . '.csv';
			file_put_contents('wpss_csv' , $path );			
			$fp = fopen( $path , 'w');
			fputcsv($fp , $this->urls);
			fclose($fp);
			file_put_contents("../../plugins/wpss/wpss_download_session" , 'on' );
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
				  file_put_contents('../../plugins/wpss/wpss_invalid' , 'true' );
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
				  if ( $this->similar_word_flag == true ) {
				  if(stripos($url, $val) !== false)
				  {
					  $valid = false;
					  break;
				  }
				  
				  }
				  
				  else {
				  if (preg_match("/\b" . $val . "\b/i" , $url ))
				  {
					  $valid = false;
					  break;
				  }					
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
	  file_put_contents("../../plugins/testing" , 123 ); 
	  $url = $_GET['url'];
	  $extension = $_GET['exclude_extension'];
	  $word = $_GET['exclude_word'];
	  $depth = intval($_GET['depth']);
	  $similar_word_flag = $_GET['flag'];
	  if ( $similar_word_flag == 1 ) {
	  $similar_word_flag = true;
	  }
	  else {
	   $similar_word_flag = false;  
	  }
	  $exclude_extension = explode( ' , ' , $extension );
	  $exclude_word = explode ( ' , ' , $word );
	  $full_url = 'http://' . $url;	
	  echo $url;
	  print_r($exclude_extension);
	  print_r($exclude_word);
	  print_r($depth);
	  print_r($similar_word_flag);  
      $scraper = new scraper( $full_url , $depth , $exclude_extension , $exclude_word , $similar_word_flag );
  ?>