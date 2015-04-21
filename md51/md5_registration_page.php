<style type="text/css">
form {
  display: inline;
}
.myTable { width:400px;background-color:#eee;border-collapse:collapse; }
.myTable th { background-color:#000;color:white;width:50%; }
.myTable td, .myTable th { padding:5px;border:1px solid #000; }
</style>
<script type="text/javascript">
function get_order(id) {
	document.getElementById('customer_order_id').value = id;
}
</script>
<?php


	
	if ( isset($_POST['customer_email']) && filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL) && isset($_POST['customer_serial']) && isset($_POST['customer_order_id']) ) {
		$salt = base64_decode(get_option('md5_salt'));
		$serial = $_POST['customer_serial'];
		$order_id = $_POST['customer_order_id'];
		$hash = md5($order_id.$salt.$serial );
		update_option($order_id. '_SERIAL_TMP', $_POST['customer_serial']);
		update_option($order_id. '_MD5_TMP' , $hash);
		update_option($order_id. '_EMAIL_TMP' , $_POST['customer_email']);
		generate_table();
		form_page();
		// disable this for now
		
	}
	else if ( isset($_POST['email_and_save'])) {
		
			$order = fused_get_all_user_orders(get_current_user_id());
			$_pf = new WC_Product_Factory();
			
			foreach ($order as $id) {

   			 $_product = $_pf->get_product($id);

			 	$table = generate_table_output();
			 update_option($id. '_SERIAL', get_option($id. '_SERIAL_TMP'));
			 update_option($id. '_MD5' , get_option($id. '_MD5_TMP'));
			 update_option($id. '_EMAIL' , get_option($id. '_EMAIL_TMP'));
			 update_option($id. '_MD5_LOCKED' , true);
			 email_customer($table, $id);

			}
	}
	else if ( ((isset($_POST['login_username']) && isset($_POST['login_password'])) || is_user_logged_in()) && !isset($_POST['email_and_save'])   )  {
	$order = fused_get_all_user_orders(get_current_user_id());
			$_pf = new WC_Product_Factory();
			
			foreach ($order as $id) {
			//empty all the temp vars
   			 $_product = $_pf->get_product($id);	
			 update_option($id. '_SERIAL_TMP', "");
			 update_option($id. '_MD5_TMP' , "");
			 update_option($id. '_EMAIL_TMP' , "");
			}
	generate_table();
	form_page();
	
	}
	
	else if ( !isset($_POST['login_username']) && !isset($_POST['login_password']) && !is_user_logged_in()) {
		if ( isset($_GET['login']) ) {
			echo '<p style="color:red;">There was an error in username/password</p>';
		}
		
		echo '<p>You need to login first. Use your account that was setup on purchase.Enter your username/email address and password below.<p>';
		echo '
	<form action="" method="post">
	<div style="padding-top: 2px; font-size: 89%">Email address / username</div>
	<input name="login_username" type="text" size="42" required></input>
	<p/>
	<div style="padding-top: 2px; font-size: 89%">Password</div>
	<input name="login_password" type="text" size="42" required></input>
	<br/><br/>
	<input type="Submit" value="Submit"></input>
	</form>';
		
	
	}
	else {
		echo '<h1 align="center">There was an error</h1>
		<p align="center">Please review your email, product number and serial number</p>';
	}
	
	function email_customer($table , $id) {
		$to = get_option($id. '_EMAIL');
		
		$subject = get_option('md5_email_sub');
		$msg = stripcslashes(get_option('md5_email_content'));
		$logo = "";
		if ( get_option('md5_logo_url') != "" || get_option('md5_logo_file') != "" ) {
			if ( get_option('md5_logo_url') != "" ) {
				$logo = '<div style="position: absolute; top: 5%; left: 50%;">
<image style="width: 181px;" src=" '.WP_PLUGIN_URL.'/md51/images/'.get_option('md5_logo_url').'"></img></div>';
			}
			else if (get_option('md5_logo_file') != "") {
				$logo = '<div style="position: absolute; top: 5%; left: 50%;">
<image style="width: 181px;" src=" '.WP_PLUGIN_URL.'/md51/images/'.get_option('md5_logo_file').'"></img></div>';
			}
			$msg = str_replace('[Logo or company name]' , $logo , $msg );
		}
		else {
				$company_name = get_option('md5_company_name');
				$msg = str_replace('[Logo or company name]' , $company_name , $msg );		
		}
		$company_website = get_option('md5_company_website');
		if ( $company_website != "" ) {
		$msg = str_replace('[Web Site Address]', $company_website , $msg);
		}
		else {
		$msg = str_replace('[Web Site Address]&bull;', "" , $msg);	
		}
		$company_email = get_option('md5_company_email');
		if ( $company_email != "" ) {
		$msg = str_replace('[Email Address]', $company_email , $msg);
		}
		else {
		$msg = str_replace('[Email Address]&bull;', "" , $msg);	
		}
		$company_phone = get_option('md5_company_phone');
		if ( $company_phone != "" ) {
		$msg = str_replace('[Phone Number]', $company_phone , $msg);
		}
		else {
		$msg = str_replace('[Phone Number]&bull;', "" , $msg);	
		}
		
		$msg = str_replace('[Company Name]', get_option('md5_company_name') , $msg);
		$msg = str_replace('[MD5 Key]', $table , $msg);
		$msg = preparehtmlmail($msg , get_option('md5_company_email'));
		
		wp_mail($to, $subject, $msg['multipart'] ,$msg['headers']);
		$subject = "A customer has registered a product using your website";
		$msg = '<p><h1 align="center">'.$company_name.'</h1></p>
<p align="center">'.$company_website.'•'.$company_email.'•'.$company_phone.'</p>
<p>A customer has registered a product using your website.</p>
';	
		$msg = preparehtmlmail($msg , get_option('md5_company_email'));
		wp_mail(get_option('md5_verification_email'), $subject, $msg['multipart'] ,$msg['headers']);

	}
	
function preparehtmlmail($html , $from ) {

  preg_match_all('~<img.*?src=.([\/.a-z0-9:_-]+).*?>~si',$html,$matches);
  $i = 0;
  $paths = array();

  foreach ($matches[1] as $img) {
    $img_old = $img;

    if(strpos($img, "http://") == false) {
      $uri = parse_url($img);
      $paths[$i]['path'] = $_SERVER['DOCUMENT_ROOT'].$uri['path'];
      $content_id = md5($img);
      $html = str_replace($img_old,'cid:'.$content_id,$html);
      $paths[$i++]['cid'] = $content_id;
    }
  }

  $boundary = "--".md5(uniqid(time()));
  $headers .= "MIME-Version: 1.0\n";
  $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
  $headers .= "From: ".$from."\r\n";
  $multipart = '';
  $multipart .= "--$boundary\n";
  $kod = 'utf-8';
  $multipart .= "Content-Type: text/html; charset=$kod\n";
  $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
  $multipart .= "$html\n\n";

  foreach ($paths as $path) {
    if(file_exists($path['path']))
      $fp = fopen($path['path'],"r");
      if (!$fp)  {
        return false;
      }

    $imagetype = substr(strrchr($path['path'], '.' ),1);
    $file = fread($fp, filesize($path['path']));
    fclose($fp);

    $message_part = "";

    switch ($imagetype) {
      case 'png':
      case 'PNG':
            $message_part .= "Content-Type: image/png";
            break;
      case 'jpg':
      case 'jpeg':
      case 'JPG':
      case 'JPEG':
            $message_part .= "Content-Type: image/jpeg";
            break;
      case 'gif':
      case 'GIF':
            $message_part .= "Content-Type: image/gif";
            break;
    }

    $message_part .= "; file_name = \"$path\"\n";
    $message_part .= 'Content-ID: <'.$path['cid'].">\n";
    $message_part .= "Content-Transfer-Encoding: base64\n";
    $message_part .= "Content-Disposition: inline; filename = \"".basename($path['path'])."\"\n\n";
    $message_part .= chunk_split(base64_encode($file))."\n";
    $multipart .= "--$boundary\n".$message_part."\n";

  }

  $multipart .= "--$boundary--\n";
  return array('multipart' => $multipart, 'headers' => $headers);  
}


/**
 * Returns all the orders made by the user
 *
 * @param int $user_id
 * @param string $status (completed|processing|canceled|on-hold etc)
 * @return array of order ids
 */
function fused_get_all_user_orders($user_id,$status='completed'){
    if(!$user_id)
        return false;
    
    $orders=array();//order ids
     
    $args = array(
        'numberposts'     => -1,
        'meta_key'        => '_customer_user',
        'meta_value'      => $user_id,
        'post_type'       => 'shop_order',
        'post_status'     => 'publish',
        'tax_query'=>array(
                array(
                    'taxonomy'  =>'shop_order_status',
                    'field'     => 'slug',
                    'terms'     =>$status
                    )
        )  
    );
    
    $posts=get_posts($args);
    //get the post ids as order ids
    $orders=wp_list_pluck( $posts, 'ID' );
    
    return $orders;
 
}

function generate_table_output() {
	$order = fused_get_all_user_orders(get_current_user_id());
	
	$table =  '<table class="myTable">
			<tr>
				<th>Order ID #</th><th>Device Serial Number</th><th>Registration Key</th>
			</tr> ';
			
			foreach ($order as $id) {

			 if ( get_option($id. '_SERIAL') == false ) {
			 $table = $table . '<tr>
				<td>'.$id.'</td><td>'.get_option($id. '_SERIAL_TMP').'</td><td>'.get_option($id. '_MD5_TMP').'</td>
			</tr>';
			 }
			 else {
			 $table = $table . '<tr>
				<td>'.$id.'</td><td>'.get_option($id. '_SERIAL').'</td><td>'.get_option($id. '_MD5').'</td>
			</tr>';
			 }
			
		
	}
			
			
		$table = $table . '</table>';
		
		return $table;

}

function generate_table() {
	$order = fused_get_all_user_orders(get_current_user_id());
	
	$table =  '<table class="myTable">
			<tr>
				<th>Order ID #</th><th>Device Serial Number</th><th>Registration Key</th>
			</tr> ';
			
			foreach ($order as $id) {

			 if ( get_option($id. '_SERIAL') == false ) {
			 $table = $table . '<tr>
				<td><a onClick="get_order('.$id.')" style="cursor: pointer;">'.$id.'</a></td><td>'.get_option($id. '_SERIAL_TMP').'</td><td>'.get_option($id. '_MD5_TMP').'</td>
			</tr>';
			 }
			 else {
			 $table = $table . '<tr>
				<td><a onClick="get_order('.$id.')" style="cursor: pointer;">'.$id.'</a></td><td>'.get_option($id. '_SERIAL').'</td><td>'.get_option($id. '_MD5').'</td>
			</tr>';
			 }
			
		
	}
			
			
		$table = $table . '</table>';
		echo $table;
		

}



	function form_page() { 
	echo stripcslashes(get_option('md5_product_registration'));
	echo '
	
	<form action="" method="post">
	<div style="padding-top: 2px; font-size: 89%">Email address</div>
	<input name="customer_email" type="text" size="42" required></input>
	<p/>
	<div style="padding-top: 2px; font-size: 89%">Order ID</div>
	<input id="customer_order_id" name="customer_order_id" type="text" size="42" required></input>
	
	<div style="padding-top: 2px; font-size: 89%">Device serial number</div>
	<input name="customer_serial" type="text" size="42" required></input>
	<p/>
	<div style="float: left; width: 150px;">
	<input type="Submit" value="Generate Key"></input>
	</div>
	</form>
	<form action="" method="post">
	<input type="hidden" name="email_and_save" value="yes"></input>
	<div style="float: left; width: 180px;">
	<input type="Submit" value="Save and Email Keys"></input>
	</div>
	</form>
	';
	}


	
ob_end_flush();
?>
