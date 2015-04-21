<?php
// all about MD5 salt
if ( isset($_POST['md5_setting'])) {
	if (ctype_upper($_POST['md5_setting'])) {
	update_option('md5_setting',$_POST['md5_setting']);
	echo '<div class="updated" style="font-weight: bold; color: green; font-size: 120%; padding-bottom: 5px;">MD5 salt updated !</div>';
	}
	else {
	echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">Error in MD5: Please check if all are uppercase.</div>';	
	}
}


	

?>