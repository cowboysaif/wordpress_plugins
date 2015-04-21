<?php
ob_start();
if( $_POST['stage'] != 1 ) {
$output = '
<form method="post">
<input type="hidden" name= "stage" value="1">
<div style="width: 451px; height: 322px; background-color: #f6f6f6;">
<div style="padding-left: 23px; padding-top: 23px; width: 111px; height: 24px; font-family: Tahoma, Geneva, sans-serif; font-size: 14px; color: #689e23; font-weight: bold;">Club Name :</div>
<input type="text" style="
    font-size: 14px;
    float:  right;
    width: 267px;
    margin-right: 27px;
    margin-top: -24px;
" name="club_name" >

<div style="padding-left: 23px; padding-top: 23px; width: 113px; height: 24px; font-family: Tahoma, Geneva, sans-serif; font-size: 14px; color: #689e23; font-weight: bold;">Course Type :</div>


<select style="
    font-size: 14px;
    width: 99px;
    float:  right;
    margin-right: 197px;
    margin-top: -24px;
" name= "course_type" > <option value= "--Any--">--Any--</option>';
global $wpdb;

$sql = "SELECT DISTINCT `Club Membership` FROM wp_golf_clubs LIMIT 0, 30 ";
$result = $wpdb->get_col($sql);

for ( $i = 0 ; $i < sizeof($result) - 1 ; $i++ ) {
$output = $output . '<option value="' . $result[$i] . '">'. $result[$i] . '</option>';
}


$output = $output . '</select>
<div style="padding-left: 23px; padding-top: 23px; width: 111px; height: 24px; font-family: Tahoma, Geneva, sans-serif; font-size: 14px; color: #689e23; font-weight: bold;">City :</div>
<input type="text" style="
    font-size: 14px;
    float:  right;
    width: 267px;
    margin-right: 27px;
    margin-top: -24px;
" name = "city">

<div style="padding-left: 23px; padding-top: 23px; width: 111px; height: 24px; font-family: Tahoma, Geneva, sans-serif; font-size: 14px; color: #689e23; font-weight: bold;">State :</div>
<select style="
    font-size: 14px;
    width: 99px;
    float:  right;
    margin-right: 197px;
    margin-top: -24px;
" name="state"> <option value= "--Any--">--Any--</option>';
global $wpdb;

$sql = "SELECT DISTINCT State FROM `wp_golf_clubs` LIMIT 0, 30 ";
$result = $wpdb->get_col($sql);

for ( $i = 0 ; $i < sizeof($result) ; $i++ ) {
$output = $output . '<option value="' . $result[$i] . '">'. $result[$i] . '</option>';
}


$output = $output . '</select>

<div style="padding-left: 23px; padding-top: 23px; width: 111px; height: 24px; font-family: Tahoma, Geneva, sans-serif; font-size: 14px; color: #689e23; font-weight: bold;">Postal Code :</div>
<input type="text" style="
    font-size: 14px;
    float:  right;
    width: 267px;
    margin-right: 27px;
    margin-top: -24px;
" name="postal_code">
<div style="margin-top: 15px;background-color: #def3c8;width: 451px; height: 72px;">
<input name="submit" type="submit" style="
    margin-top: 19px;
    margin-left: 186px;
    background-color: #669342;
" />
</div>
</div>
</form>';

}

else if ( $_POST['stage'] == 1 )  {
	
	$output = '<form method="post">
	<input type="hidden" name= "stage" value="2">
	<div>
	<img src="' . WP_PLUGIN_URL . '/golf_db/back.png" style="
    width: 22px;
    height: 19px;
" />
	<a href="'.site_url().'">Search again</a>
	</div>
	<div>
	<table border="1">
	<tr>
	<td> Club Name </td>
	<td> Address </td>
	<td> City </td>
	<td> State </td>
	<td> Postal Code </td>
	<td> Phone </td>
	<td> Fax </td>
	<td> </td>	
	</tr>';
	
	$sql = "SELECT DISTINCT `wp_golf_clubs`.`Club Name`,`wp_golf_clubs`.`Address` , `wp_golf_clubs`.`City` , `wp_golf_clubs`.`State` , `wp_golf_clubs`.`Postal Code` , `wp_golf_clubs`.`Phone` , `wp_golf_clubs`.`Fax` FROM `wp_golf_clubs` , `wp_golf_courses` WHERE `wp_golf_clubs`.`Club ID` = `wp_golf_courses`.`Club ID` AND ";
	
	if ($_POST['club_name'] != '' ) {
		$sql = $sql . "`wp_golf_clubs`.`Club Name` LIKE '%".$_POST['club_name']."%'";
	}
        if ($_POST['club_name'] != '' && ($_POST['course_type'] != '' || $_POST['course_type'] != '--Any--') ) {
                $sql = $sql . ' AND ';
        }
	if ( $_POST['course_type'] != '' && $_POST['course_type'] != '--Any--' ) {
	$sql = $sql . " `wp_golf_clubs`.`Club Membership` LIKE '%".$_POST['course_type']."%'"; 
	}
        
	if ($_POST['city'] != '' ) {
      
	$sql = $sql . " AND `wp_golf_clubs`.`City` LIKE '%".$_POST['city']."%'";
	}       

	if ($_POST['state'] != '' && $_POST['state'] != '--Any--' ) {
	 $sql = $sql . " AND `wp_golf_clubs`.`State` LIKE '%".$_POST['state']."%'";
	}

	if ($_POST['postal_code'] != '' ) {
	$sql = $sql . " AND `wp_golf_clubs`.`Postal Code` LIKE '%". $_POST['postal_code']. "%' LIMIT 0, 30 ";
	}
	
	global $wpdb;
	$wpdb->show_errors();
	$wpdb->print_error();
	$result = $wpdb->get_results($sql, ARRAY_A);
	
		
		
        
	for ( $i = 0 ; $i <= 20 ; $i++ ) {
		$output = $output . '<tr>
			<td>'.$result[$i]["Club Name"].'</td>
		</tr>';
	}
        
        $output = $output . '</table>
	</div>
	</form> ';
	

}
