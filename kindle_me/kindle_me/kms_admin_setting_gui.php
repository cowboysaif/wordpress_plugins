<html>
<style type="text/css">
#bg {
	background-color: #FFF;
	background-repeat: no-repeat;
}
#wizard_image {
	position:absolute;
	left:443px;
	top:14px;
	width:215px;
	height:208px;
	z-index:2;
}
#wizard_heading1 {
	position:absolute;
	left:14px;
	top:15px;
	width:418px;
	height:36px;
	z-index:3;
	font-family: Tahoma, Geneva, sans-serif;
	font-style: italic;
	color: #333;
	font-size: 20px;
}
#body {
	position:absolute;
	left:14px;
	top:68px;
	width:417px;
	height:401px;
	z-index:4;
	font-family: Tahoma, Geneva, sans-serif;
	color: #333;
	padding: 20px;
}
#button {
	position:absolute;
	left:217px;
	top:345px;
	width:206px;
	height:56px;
	z-index:5;
}
#apDiv1 {
	position:absolute;
	left:181px;
	top:112px;
	width:661px;
	height:475px;
	z-index:6;
}
#edit_cat {
	position:absolute;
	left:-153px;
	top:-70px;
	width:463px;
	height:295px;
	z-index:7;
}
#button_next {
	position:absolute;
	left:80px;
	top:250px;
	width:100px;
	height:26px;
	z-index:5;
}

#table {
	font-family: Tahoma, Geneva, sans-serif;
	padding: 0px;
	text-align: center;
	background-color: #FFF;
}

</style>
<body>
<?php
if ( get_option('kms_ready') == 'true' ) {
echo '<img src="' . WP_PLUGIN_URL . '/kindle_me/kindle_me_banner.jpg" width="800" height="225" hspace="200">'; 
}
?>
<?php
 
$cat_id =  get_cat_ID('kindle me');
$cats = get_categories('child_of='.$cat_id);


include('converter.php');
if ( get_option('kms_ready') == 'true' &&  isset($_POST['cat_name']) == false ||  ( isset($_POST['cat_name']) && $_POST['cat_name'] == 'kms_publish')  ) {
echo '<table id = "table" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" width="839" height="136" border="0">
    <tr>
      <td>Book Name :</td>
      <td>Converted :</td>
      <td>Published :</td>
    </tr>';
foreach ( $cats as $cat ) {
	echo '
    <tr>
      <td>'. $cat->cat_name .'</td>
      <td><form name="form1" method="post" action="">
      <input name="cat_name" type="hidden" value="'.$cat->cat_name.'">';
	  $bookstore = get_option('kms_book');
	  $fileName = $cat->cat_name;
	  $forbidden_character = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}" );
	  
        $fileName = str_replace($forbidden_character, '', $fileName);
        $fileName = preg_replace('/[\s-]+/', '-', $fileName);
        $fileName = trim($fileName, '.-_');
		
	  if ( $bookstore[$cat->cat_name]['converted'] == 1 ) {
		  $bookstore[$cat->cat_name]['book_name'] = $fileName;
		  update_option('kms_book' , $bookstore );
	  	echo '<input type="button" value="Download" onClick="window.location.href=\''.WP_PLUGIN_URL.'/kindle_me/'. $fileName .'.epub\'">';
	  }
		  echo '<input type="submit" name="convert1" id="convert1" value="Click to convert">';
	  
	  
      echo '</form></td>
      <td><form name="form2" method="post" action="">
      <input name="cat_name" type="hidden" value="kms_publish">
	  <input name="cat_real_name" type="hidden" value="'.$cat->cat_name.'">
        <input type="submit" name="publish" id="publish" value="Insert Link">
      </form></td>
    </tr>';
}
echo '</table>';
}

include('wizard.php');
?>
</body>
</html>
