<?php
echo get_option('plugin_error');
global $wpdb;
$table_name = $wpdb->prefix. "multipl4_traffic";
$cat_name = get_option("sql_catagory");
if ( $cat_name == "All" ) {
$sql = "SELECT description, id  FROM `" . $table_name . "` group by description";
	$data = $wpdb->get_results($sql , ARRAY_N );

	
}
else {
$sql = "SELECT DISTINCT description, id  FROM `" . $table_name . "` group by description\n"
    . "WHERE name = \"". $cat_name . "\"";
			
    $data = $wpdb->get_results($sql , ARRAY_N );
    echo $sql;
	
}
	$i = 0 ;
	$j = 0;
	$link_num = get_option("link_num");
   
	
	while ( $i < $link_num ) {
		
	echo '<p><a href="http://www.multiplymytraffic.com/links/' . $data[$j][1] . '">' . $data[$j][0] . '</a></p>';
	$i++;
	$j++;

	if ( $j == sizeof($data) ) {
      $j = 0 ;
	}
	}
?>