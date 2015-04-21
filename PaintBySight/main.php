<?php
global $wpdb;
global $sql_for_post;
global $load_style;
set_time_limit(0);
global $urls;
	/*
	Plugin Name:  PaintBySight Calendar by Swift Evolutions Inc
	Plugin URI: http://google.com
	Description: A little plugin to manage calendar, class and payment system.
	Author: Saif Taifur
	Version: 0.1
	Author URI: mailto:cowboysaif@hotmail.com
	*/
	
	// invoking wp action to show a menu in admin panel	
	add_action('admin_menu' , 'calendar_menu_setup');
	add_action('activated_plugin','calendar_save_error');
	add_action( 'admin_init', 'calendar_admin_init' );
    function calendar_save_error(){
    update_option('plugin_error',  ob_get_contents());
    }
	// registering the action in the plugin activation time
	
	function calendar_save_options() {
		global $wpdb;
		$table_name = $wpdb->prefix. "calendar";		
			$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . "(
	`id` int NOT NULL AUTO_INCREMENT,
	`event_name` TEXT NOT NULL,
	`event_date` TEXT NOT NULL,
	`num_part` TEXT NOT NULL,
	`registered` TEXT NOT NULL,
	`event_time` TEXT NOT NULL,
	`event_description` TEXT NOT NULL,
	`event_image` TEXT NOT NULL,
	`event_type` TEXT NOT NULL,
	`material` TEXT NOT NULL,
	`event_address` TEXT NOT NULL,
	`event_published` TEXT NOT NULL,
	primary key(`id`) ) ENGINE = InnoDB;";
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	// sql done right ?
	$f = fopen(WP_PLUGIN_DIR . '/PaintBySight/sql.txt' , "w");
	fwrite($f,$sql);
	fclose($f);
	}
	
	register_activation_hook( __FILE__, 'calendar_save_options' );
	
	function calendar_admin_init() {
       /* Register our stylesheet. */
	   $load_style = false;
       wp_register_style( 'calendar_admin_stylesheet', plugins_url('calendar_admin_stylesheet.css', __FILE__) );
   }
	function calendar_menu_setup() {
		
	   //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	   /*
	   * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	   * @param string $menu_title The text to be used for the menu
	   * @param string $capability The capability required for this menu to be displayed to the user.
	   * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	   * @param callback $function The function to be called to output the content for this page.
		*/
		$page= add_options_page ('PaintBySight Calendar settings' , 'PaintBySight Calendar' , 'manage_options' , 'calendar' , 'calendar_admin_setting_gui' );
		add_action( 'admin_print_styles-' . $page, 'calendar_admin_styles' );
	}
	function calendar_admin_setting_gui() {
		include('calendar_admin_setting_gui.php');
	}
	function calendar_admin_styles() {
       /*
        * It will be called only on your plugin admin page, enqueue our stylesheet here
        */
       wp_enqueue_style( 'calendar_admin_stylesheet' );
   }
   
  /**
 * Proper way to enqueue scripts and styles
 */
function calendar_css() {
	wp_register_style( 'cc', plugins_url( '/calendar_stylesheet.css' , __FILE__ ) );
	wp_enqueue_style('cc', plugins_url( '/calendar_stylesheet.css' , __FILE__ )  );

}

add_action( 'wp_enqueue_scripts', 'calendar_css' );

   add_shortcode( 'calendar', 'calendar_page' );
   function calendar_page() {
	   global $wpdb;
   		include('calendar_page.php');
   }
   
  	
	
	
?>