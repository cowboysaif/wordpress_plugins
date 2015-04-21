<?php

//echo $_POST['event_box_time__font-family'];

function edit_css_classes($post_name) {
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
	
	$current = substr_replace($current,stripcslashes($_POST[$post_name]), $location + 2, ($location_end - $location)-2);
	//echo $current;
	file_put_contents($file, $current);
	
	//var_dump(strrpos($current, $_POST['event_box_event_name'], 0)); 
}


edit_css_classes('event_box_image__max-height');
edit_css_classes('event_box_image__max-width');
edit_css_classes('event_box_time__font-family');
edit_css_classes('event_box_time__color');
edit_css_classes('event_box_time__background-color');
edit_css_classes('event_box_name__font-family');
edit_css_classes('event_box_name__color');
edit_css_classes('event_box_name__background-color');

?>