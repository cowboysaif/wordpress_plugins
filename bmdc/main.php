<?php 
  /*
  Plugin Name: BMDC Doctor's info
  Plugin URI: http://www.gonona.net
  Description: A little plugin to show doctor's information from database.
  Author: cowboysaif ( cowboysaif@hotmail.com )
  Version: 0.1
  Author URI: http://www.gonona.net
  */
  
  // adding shortcode to include it in the page
 add_shortcode( 'form1', 'doctors_info' );
 
 function doctors_info() {
 include('doctors_info.php');
 }
  
?>