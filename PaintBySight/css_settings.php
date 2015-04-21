<?php

 $fontsSeraliazed = file_get_contents('http://phat-reaction.com/googlefonts.php?format=php');
 $fontArray = unserialize($fontsSeraliazed);
 //print_r($fontArray);
 
 function read_css_classes($post_name) {
	$file= WP_PLUGIN_DIR . "/PaintBySight/calendar_stylesheet.css";
	$current = file_get_contents($file);
	//var_dump(strrpos($post_name, "__", 0));
	$css_class_name = substr($post_name , 0 , strrpos($post_name, "__", 0));
	$style_name = substr($post_name , strrpos($post_name, "__", 0)+2);
	$location = strpos($current, $css_class_name);
	//echo $location;
	$location = strpos($current, $style_name , $location);
	$location = strpos($current, ": " , $location);
	$location_end = strpos($current, ";" , $location);
	//echo $location;
	//echo $location_end - $location;
	$result = substr($current, $location+2 , $location_end-2 - $location);
	//echo $result;
	return $result;
	//$current = substr_replace($current,stripcslashes($_POST[$post_name]), $location, $location_end - $location);
	//echo $current;
	//file_put_contents($file, $current);
	
	//var_dump(strrpos($current, $_POST['event_box_event_name'], 0)); 
}
 
//Default values


echo"<form action='' method='post'>
<input type='hidden' name='css_done' value='1'></input>
<div class='admin_div'>
<p>Change event css when it appears in the calendar table</p>
<div class='admin_sub_div'>

		<p>Event image :</p>
		<p>Max height :
		<input type='text' name='event_box_image__max-height' value=".read_css_classes('event_box_image__max-height')."></input>
		</p>
		<p>Max width :
		<input type='text' name='event_box_image__max-width' value=".read_css_classes('event_box_image__max-width')."></input>
		</p>
		</div>
		<div class='admin_sub_div'>
		<p>Event time: </p>
		<p>Font Name :</p>
";
		echo '<select name="event_box_time__font-family">';
		for ( $i=0; $i<sizeof($fontArray); $i++ ) {
			
			$font_name = str_replace("font-family: " , "",$fontArray[$i]["font-family"]);
			$font_name = str_replace(";" , "",$font_name);		
			if ( $font_name == read_css_classes("event_box_time__font-family") ) {
			echo '<option value="'.$font_name.'" selected>'.$font_name.'</option>';
			}
			else {
			echo '<option value="'.$font_name.'">'.$font_name.'</option>';
			}
		}
		echo "</select></p>";
		echo "<p>Font size :
		<input type='text' name='event_box_time__font-size' value=".read_css_classes('event_box_time__font-size')."></input>
		</p>";
		echo '<p>Font color :
		<input class="color {onImmediateChange:\'updateInfo(this);\'}" value="'.read_css_classes('event_box_time__color').'"></input>
		<input type="hidden" id="event_box_time__color" name="event_box_time__color" value="#'.read_css_classes('event_box_time__color').'"></input>
		</p>
		</div>';
		
		echo 
		"<div class='admin_sub_div'>
		<p>Event Name: </p>
		<p>Font Name :</p>
";
		echo '<select name="event_box_name__font-family">';
		for ( $i=0; $i<sizeof($fontArray); $i++ ) {
			
			$font_name = str_replace("font-family: " , "",$fontArray[$i]["font-family"]);
			$font_name = str_replace(";" , "",$font_name);		
			if ( $font_name == read_css_classes("event_box_name__font-family") ) {
			echo '<option value="'.$font_name.'" selected>'.$font_name.'</option>';
			}
			else {
			echo '<option value="'.$font_name.'">'.$font_name.'</option>';
			}
		}
		echo "</select></p>";
		echo "<p>Font size :
		<input type='text' name='event_box_name__font-size' value=".read_css_classes('event_box_name__font-size')."></input>
		</p>";
		echo '<p>Font color :
		<input class="color {onImmediateChange:\'updateInfo(this);\'}" value="'.read_css_classes('event_box_name__color').'"></input>
		<input type="hidden" id="event_box_name__color" name="event_box_name__color" value="#'.read_css_classes('event_box_name__color').'"></input>
		</p>
		</div>';
		
		echo '<p>Background color :
		<input class="color {onImmediateChange:\'updateInfo(this);\'}" value="'.read_css_classes('event_box_time__background-color').'"></input>
		<input type="hidden" id="event_box__background-color" name="event_box__background-color" value="#'.read_css_classes('event_box__background-color').'"></input>
		</p>';
		echo '</div>';
		
		//////////Popup/////////////
		
		echo '<div class="admin_div">';
		echo '<p>Change Popup design: </p>';
		echo '<div class="admin_sub_div">
				<p>Position of the popup in the screen :</p>
				<p>Left :
		<input type="text" name="event_popup_left" value='.read_css_classes('event_popup_left').'></input>
		</p>
			<p>Top :
		<input type="text" name="event_popup_top" value='.read_css_classes('event_popup_top').'></input>
		</p>';
		
		//////////Popup event name/////////////
		
		echo '<p>Event Name: </p>';
		echo "<p>Font Name :</p>
";
		echo '<select name="event_popup_name__font-family">';
		for ( $i=0; $i<sizeof($fontArray); $i++ ) {
			
			$font_name = str_replace("font-family: " , "",$fontArray[$i]["font-family"]);
			$font_name = str_replace(";" , "",$font_name);		
			if ( $font_name == read_css_classes("event_box_time__font-family") ) {
			echo '<option value="'.$font_name.'" selected>'.$font_name.'</option>';
			}
			else {
			echo '<option value="'.$font_name.'">'.$font_name.'</option>';
			}
		}
		echo "</select></p>";
		echo "<p>Font size :
		<input type='text' name='event_popup_name__font-size' value=".read_css_classes('event_popup_name__font-size')."></input>
		</p>";
		echo '<p>Font color :
		<input class="color {onImmediateChange:\'updateInfo(this);\'}" value="'.read_css_classes('event_box_time__color').'"></input>
		<input type="hidden" id="event_popup_name__color" name="event_popup_name__color" value="#'.read_css_classes('event_popup_name__color').'"></input>
		</p>';
		
		//////////Popup event date and time/////////////
		
		echo '<p>Event Date and Time</p>';
		echo "<p>Font Name :</p>";
		echo '<select name="event_popup_time__font-family">';
		for ( $i=0; $i<sizeof($fontArray); $i++ ) {
			
			$font_name = str_replace("font-family: " , "",$fontArray[$i]["font-family"]);
			$font_name = str_replace(";" , "",$font_name);		
			if ( $font_name == read_css_classes("event_box_time__font-family") ) {
			echo '<option value="'.$font_name.'" selected>'.$font_name.'</option>';
			}
			else {
			echo '<option value="'.$font_name.'">'.$font_name.'</option>';
			}
		}
		echo "</select></p>";
		echo "<p>Font size :
		<input type='text' name='event_popup_time__font-size' value=".read_css_classes('event_popup_time__font-size')."></input>
		</p>";
		echo '<p>Font color :
		<input class="color {onImmediateChange:\'updateInfo(this);\'}" value="'.read_css_classes('event_box_time__color').'"></input>
		<input type="hidden" id="event_popup_time__color" name="event_popup_time__color" value="#'.read_css_classes('event_popup_time__color').'"></input>
		</p>';
		
		////////// Popup image 
		
		echo '<p>Image Size:</p>';
		echo "<p>Max Height :
		<input type='text' name='event_popup_image__max_height' value=".read_css_classes('event_popup_image__max_height')."></input>
		</p>";
		echo "<p>Max width :
		<input type='text' name='event_popup_image__max_width' value=".read_css_classes('event_popup_image__max_width')."></input>
		</p>";
				echo'</div>
			</div>
			';
		
		
		///////////// Event Detail
		
		echo '<div class="admin_div">';
		echo '<p>Change event detail popup: </p>';
		echo '<div class="admin_sub_div">';
		
		echo '</div>';
		echo '</div>';
		echo '
		<input type="submit" value="Submit"></input>
		</form>
		';
		
?>