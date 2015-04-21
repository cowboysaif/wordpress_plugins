<?php	
	global $wpdb;
	$target_path = WP_PLUGIN_DIR . "/PaintBySight/uploads/";
	$target_path = $target_path .$_POST['event_date']. basename( $_FILES['event_image']['name']); 
	move_uploaded_file($_FILES['event_image']['tmp_name'], $target_path); 
	$table_name = $wpdb->prefix. "calendar";
	$sql = "INSERT INTO `".$table_name."` (`event_name`, `event_date`, `num_part`, `registered` , `event_time`, `event_description`, `event_image`, `event_type`, `material`, `event_address`, `event_published`) VALUES ('".$_POST['event_name']."', '".$_POST['event_date']."', '".$_POST['num_part']."', 0 ,'".$_POST['event_time']."', '".$_POST['event_description']."', '".$_POST['event_date'].basename( $_FILES['event_image']['name'])."', '".$_POST['event_type']."', '".$_POST['material']."', '".$_POST['event_address']."', '".$_POST['publish']."');";
	//echo $sql;
	$wpdb->query($sql);
	echo 'Event successfully summitted. You can go back to main menu to see the list of events.';
	echo '<form action="" method="post">
			<input type="hidden" name="event_complete" value="1"></input>
			<input type="submit" value="Go back to main menu"></input>
			</form>';
?>