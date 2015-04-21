<?php

echo '

<div id="main">
	
	<div style="color: #46657F;  width:auto; float:left; padding-bottom: 10px; padding-top: 10px;">
		<div style="font-weight: bold; font-size: 140%;">Wordpress MD5 License Plugin</div>
		<div style="font-style:italic; float:right; padding-top: 5px;">By Symphony Interactive</div>
	</div>	
	<div style="color: #46657F; width: 40%;">
		<div style="float:right;">This plugin allows customers to register/license software to a specific device. Upon submission, the customer will be able to view an uppercase MD5 hash key. They will also receive a confirmation email containing the key.</div>
		<div style="float:right; padding-top: 4px; font-weight: bold;">To add the customer product registration form to a page on your site, add the following shortcode to the page [md5_registration]</div>
	</div>
	<div style="color: #46657F;  width:auto; float:left; padding-top: 50px;">
	<div style="font-weight: bold; font-size: 120%;">MD5 Settings</div>
	<div style="padding-top: 5px; padding-bottom: 5px;">Enter MD5 salt.</div>
		<form action="" method="post">
			<input required name="md5_salt" type="password" size="42" value="'.base64_decode(get_option('md5_salt')).'"></input>
			<input class="button-primary" type="submit" value="Save Changes"></input>
			<div id="divMayus" style="visibility:hidden; color: red;">Caps Lock is off !</div> 
		</form>
	<div style="color: #46657F;  width:auto; float:left; padding-top: 40px;">
	<div style="font-weight: bold; font-size: 120%;">Verification Email Settings</div>
	<div style="padding-top: 5px; padding-bottom: 5px;">An email will be sent to this address any time a customer registers a product.</div>
		<form action="" method="post">
			<input required name="verification_email" type="text" size="42" value="'.get_option('md5_verification_email').'"></input>
			<input class="button-primary" type="submit" value="Save Changes"></input> 
		</form>
	<div style="color: #46657F;  width:auto; float:left; padding-top: 40px;">
	<div style="font-weight: bold; font-size: 120%;">Confirmation Email Settings</div>
	<div style="padding-top: 5px; padding-bottom: 12px;">Use this settings to configure the product registration confirmation sent to the customer.</div>
	<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px;">Logo</div>
		<form action="" method="post" enctype="multipart/form-data">';
		if ( get_option('md5_logo_url') != "" ) {
		   echo '<input name="logo_url" type="text" size="42" value="'.get_option('md5_logo_url').'"></input>';
		}
		else if  ( get_option('md5_logo_file') != "" ) {
		   echo '<input name="logo_url" type="text" size="42" value="'.get_option('md5_logo_file').'"></input>';
		}
		
		else {
			echo '<input name="logo_url" type="text" size="42"></input>';
		}
		
			echo'<input name="logo_file" type="file" accept="image/*"></input>
			<div style="font-style:italic; padding-top: 2px; font-size: 89%">Enter logo url or use browse button to add logo</div>
			<div style="font-style:italic; padding-top: 7px; font-size: 89%">If no logo image is uploaded, the Company name from the Company Info field below will be used.</div>
			<div style="color: #46657F;  width:auto; padding-top: 10px;"></div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px; float: right; padding-right:30%">Email address</div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px;">Company Name</div>
			
			<input name="company_name" type="text" size="42" value="'.get_option('md5_company_name').'" required></input>
			<input name="company_email" type="text" size="42" value="'.get_option('md5_company_email').'" required></input>
			<br/>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px; float: right; padding-right:28%; padding-top: 5px;">Website address</div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px; padding-top: 5px;">Phone Number</div>
			
			<input name="company_phone" type="text" size="42" value="'.get_option('md5_company_phone').'" ></input>
			<input name="company_website" type="text" size="42" value="'.get_option('md5_company_website').'"></input>
			<div style="color: #46657F;  width:auto; padding-top: 40px;"></div>
			<div style="font-weight: bold; font-size: 120%; padding-bottom: 10px;">Confirmation Email Content</div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px;">Email Subject</div>
			<input name="email_sub" type="text" size="42" value="'.get_option('md5_email_sub').'"></input>	
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px; padding-top: 6px;">Email Content (HTML allowed)</div>';
			if ( get_option('md5_company_name') == '' && get_option('md5_email_sub') == '' && get_option('md5_email_content') == "" ) {
				$text = '<html><body>
<p><h1 align="center">[Logo or company name]</h1></p>
<p align="center">[Web Site Address]&bull;[Email Address]&bull;[Phone Number]</p>
<p>You have successfully registered your [Company Name] product.Use the md5 key below to complete the activation of your product. Use the md5 key below to complete the activation of your product.</p>
<p align="center">[MD5 Key]</p>
<body></html>';
			echo '<textarea style="resize: none; width: 82%; height: 174px; padding-bottom: 5px;" required name="email_content">'.htmlspecialchars($text).'</textarea>';
			}
			else {
				echo '<textarea style="resize: none; width: 82%; height: 174px; padding-bottom: 5px;" required name="email_content">'.stripcslashes(get_option('md5_email_content')).'</textarea>';
			}
			echo'
			<div style="padding-left: 40%;">
			<br/>
			<input class="button-primary" type="submit" value="Save Changes"></input>
			</form>
			</div>
			<div style="color: #46657F;  width:auto; padding-top: 40px;"></div>
			<div style="font-weight: bold; font-size: 120%; padding-bottom: 5px;">Product Registration Text Page</div>
			<div style="font-style:italic; padding-top: 2px; font-size: 89%">Edit the intro text on the product registration page (HTML allowed)</div>
			<form action="" method="post">';
			if ( get_option('md5_product_registration') == "" ) {
				$text = '<p>Enter your product information below. After submitting the information, you will be able to view an MD5 hash key required to complete your product activation.You will also receive a confirmation email containing the key.</p>';	
				echo'<textarea  style="resize: none; width: 82%; height: 174px; padding-bottom: 5px;" required name="product_registration">'.htmlspecialchars($text).'</textarea>';
			}
			else {
			echo'<textarea  style="resize: none; width: 82%; height: 174px; padding-bottom: 5px;" required name="product_registration">'.stripcslashes(get_option('md5_product_registration')).'</textarea>';
			}
			echo'
			<br/>
			<div style="padding-left: 40%;">
			<br/>
			<input class="button-primary" type="submit" value="Save Changes"></input>
			</form>
			</div>
	
	</div>
</div>
<div style="position: absolute; top: 5%; right: 5px;">
<image style="width: 181px;" src=" '.WP_PLUGIN_URL.'/md51/images/si-logo-sm.png"></img>
<br/>
<a style="text-decoration: none;" href="http://www.symphonyinteractive.com">www.symphonyinteractive.com</a>
<br/>
<a style="text-decoration: none; padding-left:29%" href="mailto:info@symphonyinteractive.com">Email</a>|
<a style="text-decoration: none;" href="mailto:info@symphonyinteractive.com">Plugin info</a>
</div>';

?>