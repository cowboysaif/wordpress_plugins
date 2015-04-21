<?php
// all about MD5 salt
if ( get_option('md5_salt') == "" && get_option('md5_verification_email') == "" &&  get_option('md5_company_name') && get_option('md5_company_email') && get_option('md5_company_phone') && get_option('md5_company_website') && get_option('md5_email_sub') && get_option('md5_email_content')  ) {
	echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">Plugin detected a new installation.Please fill the informations below immediately.</div>';	
}
else if ( get_option('md5_salt') == "" ) {
	echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">No MD5 salt found. Insert it immediately.</div>';	
}
else if ( get_option('md5_verification_email') == "" ) {
	echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">No verification email found. Insert it immediately.</div>';	
}

if ( isset($_POST['md5_salt'])) {
	update_option('md5_salt',base64_encode($_POST['md5_salt']));
	echo '<div class="updated" style="font-weight: bold; color: green; font-size: 120%; padding-bottom: 5px;">MD5 salt updated !</div>';
}

if ( isset($_POST['verification_email'])) {
	if (filter_var($_POST['verification_email'], FILTER_VALIDATE_EMAIL)) {
		update_option('md5_verification_email',$_POST['verification_email']);
		echo '<div class="updated" style="font-weight: bold; color: green; font-size: 120%; padding-bottom: 5px;">Verification email updated !</div>';	
	}
	else {
		echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">There was an error in email.</div>';	
		
	}
}

if ( isset($_POST['logo_url']) && $_POST['logo_url'] != "" && @getimagesize($_POST['logo_url'])  && $_FILES["logo_file"]["tmp_name"] != "") {
	echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">Both logo upload option detected. Upload either from url or locally !</div>';
}

else if ( isset($_POST['logo_url']) && $_POST['logo_url'] != ""  ) {
	if ( @getimagesize($_POST['logo_url'])) {
    	file_put_contents( WP_PLUGIN_DIR . '/md51/images/'. basename($_POST['logo_url']), file_get_contents($_POST['logo_url']));
		//remember to make this empty when file upload available
		update_option('md5_logo_url', basename($_POST['logo_url']) );
		update_option('md5_logo_file', "" );
		echo '<div class="updated" style="font-weight: bold; color: green; font-size: 120%; padding-bottom: 5px;">Logo  updated !</div>';		
	}
	else if (@getimagesize($_POST['logo_url']) == false &&  $_POST['logo_url'] != get_option('md5_logo_url') && $_POST['logo_url'] != get_option('md5_logo_file')) {
		echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">There was an error in the url.</div>';	
	}
}

if ( isset($_POST['logo_url']) && $_POST['logo_url'] == "" && $_FILES["logo_file"]["tmp_name"] == "") {
	update_option('md5_logo_file', "" );
	update_option('md5_logo_url', "" );
	echo '<div class="updated" style="font-weight: bold; color: green; font-size: 120%; padding-bottom: 5px;">Logo removed.Company name will be used.</div>';	
}

// local file part
if ( $_FILES["logo_file"]["tmp_name"] != "") {
$check = @getimagesize($_FILES["logo_file"]["tmp_name"]);
if($check !== false) {
	if (move_uploaded_file($_FILES["logo_file"]["tmp_name"], WP_PLUGIN_DIR . '/md51/images/'. basename( $_FILES["logo_file"]["name"]))) {
		update_option('md5_logo_file', basename($_FILES["logo_file"]["name"]) );
		update_option('md5_logo_url', "" );
		echo '<div class="updated" style="font-weight: bold; color: green; font-size: 120%; padding-bottom: 5px;">Logo  updated !</div>';	
    } 
}

else {
	echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">There was an error uploading file.</div>';
}
}

// now other part of submission
if ( isset($_POST['company_name']) ){
update_option('md5_company_name', $_POST['company_name']);
}
if ( isset($_POST['company_email']) ){
if (filter_var($_POST['company_email'], FILTER_VALIDATE_EMAIL)) {
	update_option('md5_company_email', $_POST['company_email']);
}
else {
	echo '<div class="error" style="font-weight: bold; color: red; font-size: 120%; padding-bottom: 5px;">There was an error in company email.</div>';
}
}
if ( isset($_POST['company_phone']) ){
	update_option('md5_company_phone', $_POST['company_phone']);
}

if ( isset($_POST['company_website']) ){
	update_option('md5_company_website', $_POST['company_website']);
}
if ( isset($_POST['email_sub']) ){
	update_option('md5_email_sub', $_POST['email_sub']);
}
if ( isset($_POST['email_content']) ){
	update_option('md5_email_content', htmlspecialchars_decode($_POST['email_content']));
}
if ( isset($_POST['product_registration']) ){
	update_option('md5_product_registration', htmlspecialchars_decode($_POST['product_registration']));
}
?>