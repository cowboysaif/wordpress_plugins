<?php 
	/*
	Plugin Name: Simple form plugin
	Plugin URI: http://google.com
	Description: A little plugin to automate the form process
	Author: Saif Taifur Anwar
	Version: 0.2
	Author URI: mailto:info@cowboysaif@gmail.com
	*/
	
	function shortcode_fnc1(){
		if ( is_page() ) {
 		include('form1.php');
        
		}
	}
	function shortcode_fnc2(){
		if ( is_page() ) {
 		include('form2.php');
        
		}
	}
	function shortcode_fnc3(){
		if ( is_page() ) {
 		include('form3.php');
        
		}
	}
	function shortcode_fnc4(){
		if ( is_page() ) {
 		include('form4.php');
        
		}
	}
	function shortcode_fnc5(){
		if ( is_page() ) {
 		include('form5.php');
        
		}
	}
	add_shortcode( 'form1', 'shortcode_fnc1' );
	add_shortcode( 'form2', 'shortcode_fnc2' );
	add_shortcode( 'form3', 'shortcode_fnc3' );
	add_shortcode( 'form4', 'shortcode_fnc4' );
	add_shortcode( 'form5', 'shortcode_fnc5' );
?>