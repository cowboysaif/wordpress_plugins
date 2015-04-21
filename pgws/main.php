<?php 
set_time_limit(0);
global $urls;
	/*
	Plugin Name: Picture gallery web scraper
	Plugin URI: http://google.com
	Description: A little curl-based plugin that generate picutes for picture gallery in wordpress.
	Author: Saif Taifur
	Version: 0.1
	Author URI: mailto:cowboysaif@hotmail.com
	*/
	
	// invoking wp action to show a menu in admin panel	
	add_action('admin_menu' , 'pgws_menu_setup');
	add_action('activated_plugin','pgws_save_error');
    function pgws_save_error(){
    update_option('plugin_error',  ob_get_contents());
    }
	// registering the action in the plugin activation time
	register_activation_hook( __FILE__, 'pgws_save_options' );
	function pgws_save_options() {
	// ??
	}
	function pgws_menu_setup() {
		
	   //function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' )
	   /*
	   * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected
	   * @param string $menu_title The text to be used for the menu
	   * @param string $capability The capability required for this menu to be displayed to the user.
	   * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
	   * @param callback $function The function to be called to output the content for this page.
		*/
		session_start();
		add_options_page ('Picture gallery web scraper settings' , 'Picture gallery web scraper' , 'manage_options' , 'pgws' , 'pgws_admin_setting_gui' );
	}
	  // gui of dashboard -> settings 
	 function pgws_admin_setting_gui() {
		include('pgws_admin_setting_gui.php');
	}
	
	// add meta box to the post/page creation / edit section.
	add_action( 'add_meta_boxes', 'add_meta_box_to_admin_section' );
	// triggered when the post/page is saved
	add_action( 'save_post', 'myplugin_save_postdata' );
	
	add_shortcode( 'GALLERY', 'shortcode_fnc' );
	
	function add_meta_box_to_admin_section() {
	add_meta_box( 
        'myplugin_sectionid',
        __( 'Picture gallery web scrub', 'myplugin_textdomain' ),
        'myplugin_inner_custom_box',
        'post' 
    );
    add_meta_box(
        'myplugin_sectionid',
        __( 'Picture gallery web scrub', 'myplugin_textdomain' ), 
        'myplugin_inner_custom_box',
        'page'
    );
	}
	
	function myplugin_inner_custom_box( $post ) {
	
	  // Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
		include('meta_box.php');
	}
		
		
function myplugin_save_postdata( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data
global $post;

  $url = $_POST['url'];
  update_post_meta($post->ID, 'url', $url);
  $image_base_name = $_POST['image_base_name'];
  update_post_meta($post->ID, 'image_base_name', $image_base_name);
  $alt =  $_POST['alt'];
  update_post_meta($post->ID, 'alt', $alt);
  $caption = $_POST['caption'];
  update_post_meta($post->ID, 'caption', $caption);
  $add_to_gallery = $_POST['add_to_gallery'];
  update_post_meta($post->ID, 'add_to_gallery', $add_to_gallery);
  $image_suffix = $_POST['image_suffix'];
  update_post_meta($post->ID, 'image_suffix', $image_suffix);
  $urls = get_option('pgws_scrap_arr');
  if ( isset ( $_POST['submit'] ) ) {
		$img = array();
		for ( $i = 0; $i < sizeof($add_to_gallery) ; $i ++ ) {
			if ( isset($add_to_gallery[$i]) ) {
				$img[] = '<img name="'.$image_base_name.$image_suffix[$i].'" title="'.$image_base_name.$image_suffix[$i].'" src="'.$urls[$i].'" alt="Alt text here" width="100" height="100"/>';
			}
		}
		
		update_option('pgws_image_arr' , $img );
  }
  

  // Do something with $mydata 
  // probably using add_post_meta(), update_post_meta(), or 
  // a custom table (see Further Reading section below)
}

function shortcode_fnc () {
		  
	  $urls = get_option('pgws_image_arr');
	   echo '<style type="text/css">
	  table, td, th
	  {
	  border:1px solid blue;
	  }
	  th
	  {
	  
	  background-color:green;
	  color:white;
	  }
	  
	  td
	  {
	  padding:10px;
	  }
	  </style>';
	  
	  echo ' <p/>
	   
		
		<table>';
		for ( $i = 0 ; $i < sizeof ($urls) ; $i = $i + 3 ) {
		  echo '<tr>';
		  echo '<td>'.$urls[$i].'</td>';
		  echo '<td>'.$urls[$i+1].'</td>';
		  echo '<td>'.$urls[$i+2].'</td>';
		  echo '</tr>';
		}
		echo ' 
		 </table>';
}

?>