<?php 
  /*
  Plugin Name: Remote SQL Plugin
  Plugin URI: http://www.varixone.com
  Description: A little plugin to import various data from a remote mysql and putting it to a local database.
  Author: cowboysaif ( cowboysaif@hotmail.com )
  Version: 0.1
  Author URI: http://www.varixone.com
  */
  
  // invoking function to set up a menu in admin panel 
  // http://net.tutsplus.com/tutorials/wordpress/creating-a-custom-wordpress-plugin-from-scratch/
  add_action('plugins_loaded' , 'widget_setup');
  add_action('admin_menu' , 'menu_setup');
  register_activation_hook( __FILE__, 'save_error' );
function save_error(){
	
    update_option('plugin_error',  ob_get_contents() );
}

  function menu_setup () {
	 //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	 /*
	 * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	 * @param string $menu_title The text to be used for the menu
	 * @param string $capability The capability required for this menu to be displayed to the user.
	 * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	 * @param callback $function The function to be called to output the content for this page.
      */
	  add_options_page ('Remote SQL Plugin settings' , 'Remote SQL' , 'manage_options' , 'rsql' , 'rsql_admin_setting_gui' );
  }
  /*
  wp_register_sidebar_widget(
    'your_widget_1',        // your unique widget id
    'Your Widget',          // widget name
    'your_widget_display',  // callback function
    array(                  // options
        'description' => 'Description of what your widget does'
    )
);
*/
  function widget_setup() {
	  
  wp_register_sidebar_widget("rsql" , "Remote SQL" , "rsql_widget_gui" , array ( description => "Remote SQL description goes here..") );  
  wp_register_widget_control('rsql', 'Remote SQL' , 'rsql_widget_control_gui' , array (description => "description goes..."));
  

  }
  // gui of dashboard -> settings 
  function rsql_admin_setting_gui() {
	  include('admin_setting_gui.php');
  }
  function rsql_widget_gui() {
	  
	  include('widget_gui.php');
  }
  function rsql_widget_control_gui() {
	  include('widget_control_gui.php');
  }
?>