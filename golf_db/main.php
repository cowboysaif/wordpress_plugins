<?php 
  /*
  Plugin Name: Golf course database
  Plugin URI: http://www.thegolfcourselocator.com/
  Description: A little plugin to show glof course information.
  Author: cowboysaif ( cowboysaif@hotmail.com )
  Version: 0.1
  Author URI: https://www.odesk.com/users/~01aa3e8738f0b2edc6
  */
  
  add_shortcode('golf_course_search_form' , 'golf_course_database_search_form');
  
  function golf_course_database_search_form() {
  	include('golf_course_database_search_form.php');
	return $output;
  }
  
?>