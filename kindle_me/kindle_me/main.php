<?php
	/*
	Plugin Name: Kindle me !
	Plugin URI: www.kindlerevolutions.com
	Description: A little plugin to help a writer craete and post ebook compatible for kindle and other reading devices.
	Author: Andreas Quintana
	Version: 0.1
	Author URI: mailto:andreasqs@gmail.com
	*/
	
	// invoking wp action to show a menu in admin panel
	add_filter('the_content' , 'post_modify' );
	add_action('admin_menu' , 'kms_menu_setup');
	register_activation_hook( __FILE__, 'save_n_check_options' );
	function kms_menu_setup () {
		
	   //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	   /*
	   * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	   * @param string $menu_title The text to be used for the menu
	   * @param string $capability The capability required for this menu to be displayed to the user.
	   * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	   * @param callback $function The function to be called to output the content for this page.
		*/
		add_options_page ('kindle me settings' , 'Kindle Me' , 'manage_options' , 'kms' , 'kms_admin_setting_gui' );
	}
	// gui of dashboard -> settings 
	 function kms_admin_setting_gui() {
		include('kms_admin_setting_gui.php');
	}
	function save_n_check_options() {
		if ( get_cat_ID('kindle me') == 0 ) {
			update_option('kms_ready' , 'false' );
			$bookstore = array();
			update_option('kms_book' , $bookstore );
		}
		else {
			update_option('kms_ready' , 'true');			
		}
	
	}
	function post_modify ($content) {
		$bookstore = get_option('kms_book');

		global $post;
		$categories = get_the_category($post->ID);
		
		if ( $bookstore[$categories[0]->name]['published'] == 1 ) { 
		$fileName = $bookstore[$categories[0]->name]['book_name'];
		$button = '<img src="'.WP_PLUGIN_URL.'/kindle_me/index.jpg" width="32" height="32"value="Download" onClick="window.location.href=\''.$bookstore[$categories[0]->name]['url'].'\'">';
		$content = $button . $content;
		}
		
		return $content;
		
	}
?>