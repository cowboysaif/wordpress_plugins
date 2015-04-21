<?php
  $full_url;
  $depth; 
 if ($_POST["hiddenField"] == "submitted" ) {
	$url = $_POST['url'];
	$extension = $_POST['exclude_extension'];
	$title = $_POST['exclude_title'];
	$depth = $_POST['depth'];
	update_option('urlc_url', $url);
	update_option('urlc_extension' , $extension );
	update_option('urlc_title' , $title );
	update_option('urlc_depth', $depth);
	$exclude_extension = explode( ' , ' , $extension );
	$exclude_title = explode ( ' , ' , $title );
	$full_url = 'http://' . $url;	
	//$scrapper = new scrapper( $full_url , intval($depth) );


}

else {
	$url = get_option('urlc_url');
	$extension = get_option('urlc_extension');
	$title = get_option('urlc_title');
	$depth = get_option('urlc_depth');
	
}
    if ( get_option('urlc_on') == 'false' && get_option('urlc_download_session') == 'off' && $_POST['hiddenField'] == 'submitted') {
      
	  update_option('urlc_on' , 'true' );
	 //$scrapper = new scrapper( $url , $depth);
   
     echo '<div class = "updated"><p><strong>Scrapping has started in background. Hit refresh after sometime.</p></div>'; 
	}
	else if ( get_option('urlc_on') == 'false' && get_option('urlc_download_session') == 'on') {
    $disabled = '';
	$url_path = WP_PLUGIN_URL . 'urlc.csv';
	update_option('urlc_download_session' , 'off' );
	echo '<div class = "updated"><strong><a href="'.$url_path.'">csv file generated. click here</a></strong></div>';
	
	}

	else if ( get_option('urlc_on') == 'false' && get_option('urlc_download_session') == 'off' && $_POST['hiddenField'] == 'submitted' ) {
	$disabled = 'disabled = "disabled"';
	echo '<div class = "updated"><p><strong>Server is busy creating csv file. Hit refresh after sometime.</strong></p></div>';
	}
	else if ( get_option('urlc_invalid') == 'true' ) {
	echo '<div class = "updated"><p><strong>Invalid website address.</strong></p></div>';
	update_option('urlc_invalid' , 'false' );
	}
	else {
	echo '<div class = "updated"><p><strong>After pressing submit button , please wait for 4-5 seconds until the page is fully loaded.</strong></p></div>';	
	}
echo '
<div id = "wrap">
<form name="form1" method="post" action="">
  <p>
  <input name = "hiddenField" type = "hidden" id = "hiddenField" value = "submitted"/>
    <label for="url"></label>
    Url of the website to scrap : http://
    <input name="url" type="text" id="url"  value="'.$url.'" size="45">
  </p>
  <p>
    <label for="exclude_extension"></label>
    Exclude url(s) that has a filename extension (comma separated ) : 
    <input name = "exclude_extension" size="60" id="exclude_extension" value = "'.$extension .'"></textarea>
  </p>
  <p>Exclude pages that has a title containing words : (comma separated ) : 
    <label for="exclude_title"></label>
    <input name="exclude_title" size ="60" id="exclude_title" value = "'.$title.'"></textarea>
  </p>
  <p>Depth of the scrapping :
  <input name = "depth" id = "depth" size = 6 value = "'.$depth.'"</textarea>
  </p>
<p>   
  <input type="submit" name="submit" id="submit" ' .$disabled . ' value="Submit">

</p>

</form>
</div>';
?>
 
    
