<?php 
	/*
	Plugin Name: Easy Mobile Builder
	Plugin URI: https://www.facebook.com/pages/Absolute-Leverage-LLC/372558622837207
	Description: This plugin will deploy a Mobile site page builder on the Wordpress page immediately or the user can set it to be deployed.
	Author: Saif Taifur
	Version: 0.1
	Author URI: mailto:saif.taifur@outlook.com
	*/
	
	// invoking wp action to show a menu in admin panel
	add_action( 'admin_enqueue_scripts', 'add_stylesheet' );
	add_action('admin_menu' , 'emb_menu_setup');
	add_action('activated_plugin','emb_save_error');
	function emb_save_error(){
    update_option('plugin_error',  ob_get_contents());
    }
	// registering the action in the plugin activation time
	register_activation_hook( __FILE__, 'emb_save_options' );
	function emb_save_options() {
	// for future implementation, in case anything goes wrong
	}
	function emb_menu_setup() {
		
	   //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	   /*
	   * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	   * @param string $menu_title The text to be used for the menu
	   * @param string $capability The capability required for this menu to be displayed to the user.
	   * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	   * @param callback $function The function to be called to output the content for this page.
		*/
		add_menu_page ('Mobile Builder' , 'Easy Mobile Builder' , 'manage_options' , 'emb' , 'emb_admin_setting_gui' );
	}
	// gui of dashboard -> settings 
	 function emb_admin_setting_gui() {
		 include('gui_index.php');	
	}
	
	function add_stylesheet() {
        wp_enqueue_style( 'emb_stylesheet', plugins_url('emb_stylesheet.css', __FILE__) );
    }
	
	
?>