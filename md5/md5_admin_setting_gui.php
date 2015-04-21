<?php
echo '
<script language="Javascript">

function capLock(e){
 kc = e.keyCode?e.keyCode:e.which;
 sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
 if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
  document.getElementById("divMayus").style.visibility = "hidden";
 else
   document.getElementById("divMayus").style.visibility = "visible";
}
</script>
<div id="main">
	
	<div style="color: #46657F;  width:auto; float:left; padding-bottom: 10px; padding-top: 10px;">
		<div style="font-weight: bold; font-size: 140%;">Wordpress MD5 license plugin</div>
		<div style="font-style:italic; float:right; padding-top: 5px;">By Symphony Interactive</div>
	</div>	
	<div style="color: #46657F; width: 40%;">
		<div style="float:right;">This plugin allows customers to register/license software to a specific device. Upon submission, the customer will be able to view an uppercase MD5 hash key. They will also receive a confirmation email containing the key.</div>
	</div>
	<div style="color: #46657F;  width:auto; float:left; padding-top: 50px;">
	<div style="font-weight: bold; font-size: 120%;">MD5 Settings</div>
	<div style="padding-top: 5px; padding-bottom: 5px;">Enter MD5 salt in all uppercase characters</div>
		<form action="" method="post">
			<input required name="md5_setting" type="password" size="42" onkeypress="capLock(event)"></input>
			<input class="button-primary" type="submit" value="Save Changes"></input>
			<div id="divMayus" style="visibility:hidden; color: red;">Caps Lock is off !</div> 
		</form>
	<div style="color: #46657F;  width:auto; float:left; padding-top: 40px;">
	<div style="font-weight: bold; font-size: 120%;">Verification email Settings</div>
	<div style="padding-top: 5px; padding-bottom: 5px;">An email will be sent to this address any time a customer registers a product</div>
		<form action="" method="post">
			<input required name="verification_email" type="password" size="42" onkeypress="capLock(event)"></input>
			<input class="button-primary" type="submit" value="Save Changes"></input> 
		</form>
	<div style="color: #46657F;  width:auto; float:left; padding-top: 40px;">
	<div style="font-weight: bold; font-size: 120%;">Confirmation email Settings</div>
	<div style="padding-top: 5px; padding-bottom: 5px;">Use this settings to configure product registration confirmation sent to the customer</div>
	<div style="font-weight: bold; font-size: 120%; padding-bottom: 5px;">Logo</div>
		<form action="" method="post">
			<input name="logo_url" type="text" size="42"></input>
			<input type="file" name="logo_file" accept="image/*"></input>
			<div style="font-style:italic; padding-top: 2px; font-size: 89%">Enter logo url or use browse button to add logo</div>
			<div style="font-style:italic; padding-top: 7px; font-size: 89%">If no logo image is uploaded, the Company name from the Company Info field below will be used.</div>
			<div style="color: #46657F;  width:auto; padding-top: 40px;"></div>
			<div style="font-weight: bold; font-size: 120%; padding-bottom: 5px;">Confirmation email Settings</div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px; float: right; padding-right:30%">Email address</div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px;">Company Name</div>
			
			<input name="company_name" type="text" size="42" required></input>
			<input name="company_email" type="text" size="42" required></input>
			<br/>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px; float: right; padding-right:28%">Website address</div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px;">Phone Number</div>
			
			<input name="company_name" type="text" size="42" required></input>
			<input name="company_email" type="text" size="42" required></input>
			<div style="color: #46657F;  width:auto; padding-top: 40px;"></div>
			<div style="font-weight: bold; font-size: 120%; padding-bottom: 10px;">Email content</div>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px;">Email Subject</div>
			<input name="email_sub" type="text" size="42" required></input>
			<div style="font-weight: bold; font-size: 110%; padding-bottom: 5px; padding-top: 3px;">Email Content (HTML allowed)</div>
			<textarea style="width: 82%; height: 174px; padding-bottom: 5px;" required name="email_content"></textarea>
			<br/>
			<div style="padding-left: 40%;">
			<br/>
			<input class="button-primary" type="submit" value="Save Changes"></input>
			</form>
			</div>
			<div style="color: #46657F;  width:auto; padding-top: 40px;"></div>
			<div style="font-weight: bold; font-size: 120%; padding-bottom: 5px;">Product registration text page</div>
			<div style="font-style:italic; padding-top: 2px; font-size: 89%">Edit the intro text on the product registration page (HTML allowed)</div>
			<form action="" method="post">
			<textarea style="width: 82%; height: 174px; padding-bottom: 5px;" required name="email_content"></textarea>
			<br/>
			<div style="padding-left: 40%;">
			<br/>
			<input class="button-primary" type="submit" value="Save Changes"></input>
			</form>
			</div>
	
	
	
	</div>
</div>
<div style="position: absolute; top: 5%; right: 5px;">
<image style="width: 181px;" src=" '.WP_PLUGIN_URL.'/md5/images/si-logo-sm.png"></img>
<br/>
<a style="text-decoration: none;" href="www.symphonyinteractive.com">www.symphonyinteractive.com</a>
<br/>
<a style="text-decoration: none; padding-left:29%" href="mailto:info@symphonyinteractive.com">Email</a>|
<a style="text-decoration: none;" href="mailto:info@symphonyinteractive.com">Plugin info</a>
</div>';

?>