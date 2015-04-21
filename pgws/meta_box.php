<?php 
global $post;
if ( get_post_meta($post->ID, 'url',true) != "" ) {
	$url = get_post_meta($post->ID, 'url',true);
	include('image_scraper.php');
	$scraper = new image_scraper($url , 50 , 50 );
	$urls = $scraper->export_urls();
	update_option('pgws_scrap_arr' , $urls );
}
echo '
<style type="text/css">
table, td, th
{
border:1px solid blue;
}
th
{

background-color:green;
color:white;
}

td
{
padding:10px;
}
</style>



<form id="form1" name="form1" method="post" action="">
  <p>
    <label for="url">Paste URL to get images : </label>
    <input name="url" type="text" id="url" size="50" value = "'.get_post_meta($post->ID, 'url',true).'" />
    <input type="submit" name="scrub" id="scrub" value="Scrub" />
  </p>

 <label for="image_base_name">Image base name (image-base-name) : </label>
 <input type="text" name="image_base_name" id="image_base_name" value="'.get_post_meta($post->ID, 'image_base_name',true).'" />
';

if ( isset($urls) ) {
	
	echo '
  <p/>
 
  
  <table>';
  for ( $i = 0 ; $i < sizeof ($urls) ; $i = $i + 3 ) {
    echo '<tr>';
	echo '<td><img name="image[]" src=' . $urls[$i] . ' width="100" height="100"/><p/><input name="image_suffix[]" type="text" value ="none" ><p/><input name="add_to_gallery[]" type="checkbox"  value="'.$i.'"><label for="add_to_gallery">Add to gallery</label></td>';
	echo '<td><img name="image[]" src=' . $urls[$i+1] . ' width="100" height="100"/><p/><input name="image_suffix[]" type="text" value = "none"><p/><input name="add_to_gallery[]" type="checkbox" value="'.($i+1).'"><label for="add_to_gallery">Add to gallery</label></td>';
	echo '<td><img name="image[]" src=' . $urls[$i+2] . ' width="100" height="100"/><p/><input name="image_suffix[]" type="text" value ="none"><p/><input name="add_to_gallery[]" type="checkbox" value="'.($i+2).'"><label for="add_to_gallery">Add to gallery</label></td>';
	echo '</tr>';
  }
  echo ' 
   </table>';
}

 echo'
   
		<script type="text/javascript" >
		http://www.shiningstar.net/articles/articles/javascript/checkboxes.asp
		function checkAll(field)
		{
		for (i = 0; i < field.length; i++)
			field[i].checked = true ;
		}
		
		function uncheckAll(field)
		{
		for (i = 0; i < field.length; i++)
			field[i].checked = false ;
		}
		
		</script>
	<p/>
	<input name="alt" id = "alt" type="checkbox" value="'.get_post_meta($post->ID, 'alt',true).'"><label for="alt">Use image name for alt tag</label><p/>
	<input name="caption" type="checkbox" value="'.get_post_meta($post->ID, 'caption',true).'"><label for="alt">Use image name for caption</label><p/>
	<input type="submit" name="submit" id="submit" value="Submit" />
	<input name="check_all" type="button" value="Check all" onClick="checkAll(document.form3.add_to_gallery)">
	<input name="check_all" type="button" value="Uncheck all" onClick="uncheckAll(document.form3.add_to_gallery[])">
</form>';

?>
