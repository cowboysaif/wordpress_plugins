<?php 
	/*
	Plugin Name: WP Simple Scraper
	Plugin URI: http://wpsimplescraper.com/
	Description: A little curl-based plugin that generate an index of internal url(s) of a website.
	Author: TruBlu Web Consulting
	Version: 0.2
	Author URI: mailto:info@trubluwebconsulting.com
	*/
	
	// invoking wp action to show a menu in admin panel
	
	add_action('admin_menu' , 'wpss_menu_setup');
	add_action('activated_plugin','save_error');
    function save_error(){
    update_option('plugin_error',  ob_get_contents());
    }
	
	register_activation_hook( __FILE__, 'save_options' );
  
	function save_options() {
	  update_option('wpss_url', 'www.prothom-alo.com');
	  update_option('wpss_url_extension' , 'javascript: , .css , .js , .ico , .jpg , .png , .jpeg , .swf , .gif');
	  update_option('wpss_url_word' , 'contact , privacy , policy');
	  update_option('wpss_depth' , '60' );
	  update_option('wpss_ajax' , 'on' );
	  file_put_contents(  '../wp-content/plugins/wpss/' . 'wpss_on' , 'false' );
	  file_put_contents('../wp-content/plugins/wpss/' . 'wpss_download_session' , 'off' );
	  file_put_contents( '../wp-content/plugins/wpss/' . 'wpss_progress' , 0 );
	  file_put_contents( '../wp-content/plugins/wpss/' . 'wpss_invalid' , 'false' );
	}
	function wpss_menu_setup () {
		
	   //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	   /*
	   * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	   * @param string $menu_title The text to be used for the menu
	   * @param string $capability The capability required for this menu to be displayed to the user.
	   * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	   * @param callback $function The function to be called to output the content for this page.
		*/
		add_options_page ('WP Simple Scraper settings' , 'WP Simple Scraper' , 'manage_options' , 'wpss' , 'wpss_admin_setting_gui' );
	}
	
	  // gui of dashboard -> settings 
	 function wpss_admin_setting_gui() {
		include('wpss_admin_setting_gui.php');
	}
?>