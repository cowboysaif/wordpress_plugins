<?php 
  /*
  Plugin Name: Url Scrapper
  Plugin URI: http://www.varixone.com
  Description: A little curl-based plugin that generate an index of internal url(s) of a website.
  Author: cowboysaif ( cowboysaif@hotmail.com )
  Version: 0.1
  Author URI: http://www.varixone.com
  */
  
  // invoking wp action to show a menu in admin panel
  ignore_user_abort(true);
  set_time_limit(0);
  add_action('admin_menu' , 'urlc_menu_setup');
  
  register_activation_hook( __FILE__, 'save_options' );

  function save_options() {
	update_option('urlc_url', 'www.prothom-alo.com');
	update_option('urlc_extension' , 'javascript: , .css , .js , .ico , .jpg , .png , .jpeg , .swf , .gif');
	update_option('urlc_title' , 'contact , privacy , policy');
	update_option('urlc_depth' , '60' );
	update_option('urlc_on' , 'false' );
	update_option('urlc_download_session' , 'off' );
	update_option('urlc_invalid' , 'false' );
  }
  function urlc_menu_setup () {
	  
	 //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	 /*
	 * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	 * @param string $menu_title The text to be used for the menu
	 * @param string $capability The capability required for this menu to be displayed to the user.
	 * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	 * @param callback $function The function to be called to output the content for this page.
      */
	  add_options_page ('Url Scrapper Settings' , 'Url Scrapper' , 'manage_options' , 'urlc' , 'urlc_admin_setting_gui' );
  }
  
    // gui of dashboard -> settings 
   function urlc_admin_setting_gui() {
	  include('urlc_admin_setting_gui.php');
  }
  add_action('admin_footer' , 'start_scrap');
  function start_scrap() {
	  $status = get_option('urlc_on');
	  if ( $status == 'true' ) {
      include('scrapper.php');
	$extension = get_option('urlc_extension');
	$title = get_option('urlc_title');

	  	$exclude_extension = explode( ' , ' , $extension );
	$exclude_title = explode ( ' , ' , $title );
	  $scrapper = new scrapper( get_option('urlc_url') , intval(get_option('urlc_depth')) ,$exclude_extension , $exclude_title ); 
	  }
  }
  ?>