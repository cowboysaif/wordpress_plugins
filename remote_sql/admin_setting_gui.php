<?php 
  //http://www.linuxjournal.com/article/9585
    echo isset($host,$dname,$user,$pass);
   //http://net.tutsplus.com/tutorials/wordpress/creating-a-custom-wordpress-plugin-from-scratch/

   if ($_POST["hiddenField"] == "Y" ) {
	   // value submitted
	   
	   $email = $_POST["email"];
	   $host = $_POST["host"];
	   $dname = $_POST["dname"];
	   $user = $_POST["user"];
	   $pass = $_POST["pass"];
	
	   
	   if ( filter_var($email , FILTER_VALIDATE_EMAIL) == false || $host == "" || $dname == "" || $user == "" || $pass == "" ) {
		   echo '<div class = "error" id = "meassage" ><p><strong>Not a valid email address / Empty field(s)</strong></p></div>';
	   }
	   else {
	   update_option('sql_email' , $email );
	   update_option('sql_host' , $host );
	   update_option('sql_dname'  , $dname );
	   update_option('sql_user' , $user );
	   update_option('sql_pass' , $pass );
	 
	   echo '<div class = "updated"><p><strong>Options Saved</p></div>'; 
	   $mc = mysql_connect($host , $user , $pass );
	   mysql_select_db($dname , $mc );
	   $sql = "SELECT l.id , cat.name ,  l.description\n"
    . " FROM adds_categories cat, links_rel_adds_categories rel, links l, members m\n"
    . " WHERE cat.active =1\n"
    . " AND rel.categoryid = cat.id\n"
    . " AND l.id = rel.linkid\n"
    . " AND m.email='". $email . "'\n"
    . " AND l.member = m.id\n"
	. " order by rel.linkid, rel.categoryid";
	
	// http://php.net/manual/en/function.mysql-query.php
	$result = mysql_query($sql);
	
	global $wpdb;
	$table_name = $wpdb->prefix. "multipl4_traffic";
	$sql = "TRUNCATE TABLE `wp_multipl4_traffic`";
	$wpdb->query($sql);
	$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . "(
	`id` INT NOT NULL, 
	`name` TEXT NOT NULL, 
	`description` TEXT NOT NULL) ENGINE = InnoDB;";
	
   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
   while ( $data = mysql_fetch_assoc($result) ) {
   $wpdb-> insert ( $table_name , array ( 'id' => $data['id'] , 'name' => $data['name'] , 'description' => $data['description'] ,  ) );
    
   }
     	
    

	   }
   }
   else {
	   $email = get_option('sql_email');
	   $host = get_option('sql_host');
	   $dname = get_option('sql_dname');
	   $user = get_option('sql_user');
	   $pass = get_option('sql_pass'); 
	   $selected_catagory = get_option('sql_catagory');
   }
if ( filter_var($email , FILTER_VALIDATE_EMAIL) == true || $host != "" || $dname != "" || $user != "" || $pass != "" ) {
    // gather names of catagories
	global $wpdb;
	$table_name = $wpdb->prefix. "multipl4_traffic";
	$sql = "SELECT name \n"
    . "FROM " . $table_name ;
 
	
	$cat_name = $wpdb->get_col($sql);	 
       $name;
	   $i = 0 ;
	   
	   while ( $i < sizeof($cat_name) ) {
	
	   if ( $i == 0 ) {
		 	  $name[] = $cat_name[$i];
			   
	   }
	   else if ( array_search($cat_name[$i] , $name  ) == false )           {
		      $name[] = $cat_name[$i];
			  
		   }
	   else {
	   }
         $i++;
	   
	   }
       $name[] = "All";
	   update_option('sql_cats' , $name );
		 }
		 
		 
		 
   echo '<div id="wrap">
  <form id="form1" name="form1" method="post" action="">
    <h2>Remote Database Plugin Settings :</h2>
  
    <p>
    <input name="hiddenField" type="hidden" id="hiddenField" value="Y" /> </p>
    <p>
      <label for="email">Enter the email address to use in sql table :</label></p>
      <p>
      <input type="text" name="email" id="email" value= "' . $email . '"></p>
      <p>
      <label>Remote Database Settings :</label></p>
      <p>
      <label>Host :</label>
      <input type="text" name="host" id="host" value= "'. $host .'"></p>
      </p>
      <p>
      <label>Database name :</label>
      <input type="text" name="dname" id="dname" value= "'.$dname.'"></p>
      </p>
      <p>
      <label>Username :</label>
      <input type="text" name="user" id="user" value= "'. $user .'"></p>
      </p>
      <p>
      <label>Password :</label>
      <input type="text" name="pass" id="pass" value= "' .$pass. '"></p>
    <p>
	<input type="submit" name="submit" id="submit" value="Update" />
      
    </p>
  </form>
</div>';
?>