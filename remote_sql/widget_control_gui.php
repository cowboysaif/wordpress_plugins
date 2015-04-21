<?php

$link = get_option("link_num");	


if ( $_POST["posted"] == "1" ) {
	$link = $_POST["link_text"];
    update_option("link_num" , $link ) ;
	$selected_catagory = $_POST["catagory"];
	update_option("sql_catagory" , $selected_catagory );

echo "Option Saved.";

}


echo '
<p>

      <label>Number of links to be displayed    : </label>
       
        <input type="text" name = "link_text" id="link_text" value = "' . $link . '"/>
        <input name="posted" id = "posted" type="hidden" value="1" />
</p>
<p>Catagory to choose :</p>
<p>
  <select name="catagory" id="catagory">';
	  //http://www.w3schools.com/tags/tryit.asp?filename=tryhtml_option
	  $name = get_option('sql_cats');
	  $selected_catagory = get_option('sql_catagory');
	  $i = 0 ;
	  while ( $i < sizeof($name) ) {
		  if ( $name[$i] == $selected_catagory ) {
			echo '<option selected="selected">'.$name[$i] . '</option>';  
		  }
		  else {
		  echo '<option>'.$name[$i] . '</option>';
		  }
		  $i++;
	  }
	   echo '</select>
</p>';
?>