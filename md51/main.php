<?php 
	/*
	Plugin Name: Wordpress MD5 license plugin
	Plugin URI: http://www.symphonyinteractive.com
	Description: A Wordpress plugin that will allow customers to register/license their software products to a specific 		device.
	Author: Symphony Interactive
	Version: 0.3
	Author URI: mailto:info@symphonyinteractive.com
	*/
	
	// invoking wp action to show a menu in admin panel	
	session_start();
	add_action('admin_menu' , 'md5_menu_setup');
	add_action('activated_plugin','md5_save_error');
	function md5_save_error(){
    update_option('plugin_error',  ob_get_contents());
    }
	// registering the action in the plugin activation time
	register_activation_hook( __FILE__, 'md5_save_options' );
	function md5_save_options() {
	// for future implementation, in case anything goes wrong
	}
	function md5_menu_setup() {
		
	   //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	   /*
	   * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	   * @param string $menu_title The text to be used for the menu
	   * @param string $capability The capability required for this menu to be displayed to the user.
	   * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	   * @param callback $function The function to be called to output the content for this page.
		*/
		add_menu_page ('Wordpress md5 license plugin' , 'MD5 license Plugin' , 'manage_options' , 'md5' , 'md5_admin_setting_gui' );
	}
	// gui of dashboard -> settings 
	 function md5_admin_setting_gui() {
		include('submit_page.php');
		include('md5_admin_setting_gui.php');
		
	}
	//add shortcode for the registration page
	function md5_registration_page() {
		
		include('md5_registration_page.php');
	}
	
	add_shortcode( 'md5_registration', 'md5_registration_page' );
	add_action( 'after_setup_theme', 'custom_login' );
	function custom_login() {
		if ( isset($_POST['login_username'])) {
		$creds = array();
		$creds['user_login'] = $_POST['login_username'];
		$creds['user_password'] = $_POST['login_password'];
		$creds['remember'] = true; //Or false

		$user = wp_signon( $creds, false );
		if ( is_wp_error($user) ){
			
   			
		}else{
   			wp_set_current_user($user->ID);
		}
		}
	}

// magic happening under. i have no idea !
	/*-------------------------------------------------------------------------------------*/
/* Login Hooks and Filters
/*-------------------------------------------------------------------------------------*/
if( ! function_exists( 'custom_login_fail' ) ) {
    function custom_login_fail( $username ) {
        $referrer = $_SERVER['HTTP_REFERER']; // where did the post submission come from?
        // if there's a valid referrer, and it's not the default log-in screen
        if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
            if ( !strstr($referrer,'&login=failed') ) { // make sure we don’t append twice
                wp_redirect( $referrer.'&login=failed' ); // append some information (login=failed) to the URL for the theme to use
            } else {
                wp_redirect( $referrer );
            }
            exit;
        }
    }
}
add_action( 'wp_login_failed', 'custom_login_fail' ); // hook failed login

		

	
?>