<html>
<head>
<script type="text/javascript">
function showHint()
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  
<?php 
	   $similar_word_flag;
	   if ( isset($_POST['similar_words']) ) {
		if ( $_POST['similar_words'] == 'false' ) {
			$similar_word_flag = false; 
		}
		else {
			$similar_word_flag = true; 
		}
	   }
	   
	   else {
		   $similar_word_flag = true; 
	   }
?>
xmlhttp.open("GET","<?php echo WP_PLUGIN_URL ?>/wpss/scraper.php?url=<?php echo $_POST['url'];?>&exclude_extension=<?php echo $_POST['exclude_extension']?>&exclude_word=<?php echo $_POST['exclude_word']?>&depth=<?php echo $_POST['depth']?>&flag=<?php echo  $similar_word_flag?>",true );
xmlhttp.send();
}
</script>
<?php
	$full_url;
	$depth; 
	ignore_user_abort(true);
	set_time_limit(0);
    $similar_word_flag = true;
   if ($_POST["hiddenField"] == "submitted" ) {

	  $url = $_POST['url'];
	  $extension = $_POST['exclude_extension'];
	  $word = $_POST['exclude_word'];
	  $depth = $_POST['depth'];
	  update_option('wpss_url', $url);
	  update_option('wpss_url_extension' , $extension );
	  update_option('wpss_url_word' , $word );
	  update_option('wpss_depth', $depth);

	  //$scraper = new scraper( $full_url , intval($depth) );
  
  
  }
  
  else {
	  $url = get_option('wpss_url');
	  $extension = get_option('wpss_url_extension');
	  $word = get_option('wpss_url_word');
	  $depth = get_option('wpss_depth');
	  
  }
	  if ( file_get_contents(  '../wp-content/plugins/wpss/' . 'wpss_on' ) == 'true' && file_get_contents('../wp-content/plugins/wpss/' . 'wpss_download_session' ) == 'off' && file_get_contents(  '../wp-content/plugins/wpss/' . 'wpss_invalid' ) == 'false'  ) {
	   echo '<div class = "updated"><p><strong>scraping is running in background. Hit refresh to monitor its progress. You can also close the browser.</p></div>';
	   echo '<div class = "updated">
	   <p>';
	   $progress = intval(file_get_contents('../wp-content/plugins/wpss/wpss_progress'));
	   for ( $i = 1 ; $i < $progress ; $i ++ ) {
	   echo '<image src = "../wp-content/plugins/wpss/bar.png"/>';  
	   }
	   
	   echo'&nbsp;&nbsp;<strong>' . file_get_contents('../wp-content/plugins/wpss/wpss_progress') . '% done </strong></p></div>';
	  }
	  else if ( file_get_contents(  '../wp-content/plugins/wpss/' . 'wpss_on' ) == 'false' && file_get_contents('../wp-content/plugins/wpss/' . 'wpss_download_session' ) == 'on' && file_get_contents(  '../wp-content/plugins/wpss/' . 'wpss_invalid' ) == 'false'  ) {
	  $url_path = WP_PLUGIN_URL . '/wpss/'. file_get_contents('../wp-content/plugins/wpss/wpss_csv');
	  echo '<div class = "updated"><a href="'.$url_path.'">csv file generated. click here</a></div>';
	  file_put_contents('../wp-content/plugins/wpss/wpss_download_session' , 'off' );
	  }
  
	  else if ( file_get_contents(  '../wp-content/plugins/wpss/' . 'wpss_on' ) == 'false' && file_get_contents('../wp-content/plugins/wpss/' . 'wpss_download_session' ) == 'on' && file_get_contents(  '../wp-content/plugins/wpss/' . 'wpss_invalid' ) == 'true'  ) {
	  echo '<div class = "updated"><p><strong>Invalid url or connection problem. Could not scrap</strong></p></div>';
	  file_put_contents('../wp-content/plugins/wpss/wpss_invalid' . 'false');
	  }
	  else if ( $_POST['hiddenField'] == 'submitted' ) {
	      echo '<div class = "updated"><p><strong>Scraper started to run in background. Hit refresh to monitor its progress. You can also close the browser.</strong></p></div>' ;
		  echo '<script type = "text/javascript">showHint("some value")</script>';
		  file_put_contents('../wp-content/plugins/wpss/wpss_on' , 'true' );
	  }
	  else {
		  echo '<div class = "updated"><p><strong>Scraper Ready !</strong></p></div>' ;
	  }
  ?>


<?php
  echo '
  <div id = "wrap">
  <form name="form1" method="post" action="">
	<p>
	<input name = "hiddenField" type = "hidden" id = "hiddenField" value = "submitted"/>
	  <label for="url"></label>
	  Url of the website to scrap : http://
	  <input name="url" type="text" id="url"  value="'.$url.'" size="45">
	  <img src="' . WP_PLUGIN_URL . '/wpss/help-ico.png" width="16" height="16" title = "Input the url of the website you wish to scrap. The scraper will connect with the website and gather internal url(s). Be sure to have a valid website address."></image>
	  
	</p>
	<p>
	  <label for="exclude_extension"></label>
	  Exclude url(s) that has a filename extension (comma separated ) : 
	  <input type = "text" name = "exclude_extension" size="60" id="exclude_extension" value = "'.$extension .'"></input>
	  <img src="' . WP_PLUGIN_URL . '/wpss/help-ico.png" width="16" height="16" title = "Please enter the extensions of the url(s) that you want to avoid , e.g. url ending with .jpg etc. Enter each extensions ending with a comma (,)"></image>
	</p>
	<p>Exclude pages that has a url containing words : (comma separated ) : 
	  <label for="exclude_word"></label>
	  <input type = "text" name="exclude_word" size ="60" id="exclude_word" value = "'.$word.'"></input>
	  <img src="' . WP_PLUGIN_URL . '/wpss/white.png" width="16" height="16" ></image> 
	  <input name="similar_words" id = "similar_words" type="checkbox" value="false"/> 
	  
	  &nbspMatch whole word&nbsp&nbsp
	  <img src="' . WP_PLUGIN_URL . '/wpss/help-ico.png" width="16" height="16" title = "If you do not want urls that contain specific words, enter those in the textbox, separated by commas. Check the box []match whole word to exclude url like http://example.com/contact. Uncheck if you want to exlclude url like : http://example.com/contact-us"></image>
	
	</p>
	<p>Depth of the scraping :
	<input name = "depth" id = "depth" size = 6 value = "'.$depth.'"></input>
	 <img src="' . WP_PLUGIN_URL . '/wpss/help-ico.png" width="16" height="16" title = "Enter how deep the url scraper will go and retrieve urls. The more the value the more url it will gather. But also it will take more time to generate the csv file."></image>
	</p>
  <p>';
  if (  file_get_contents(  '../wp-content/plugins/wpss/' . 'wpss_on' ) == 'true' ) {   
	echo '<input type="submit" name="submit" id="submit" value="Refresh"></input>';
  }
  else {
	echo '<input type="submit" name="submit" id="submit" value="Scrap url"></input>';  
  }
  echo '
</form>

  </div>';
?>
