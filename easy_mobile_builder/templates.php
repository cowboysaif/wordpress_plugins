<?php
$folder = WP_PLUGIN_DIR . '/easy_mobile_builder/templates/';
$folder_url = WP_PLUGIN_URL . '/easy_mobile_builder/templates/';
$filetype = '*.*';
$files = glob($folder.$filetype);
$count = count($files);
 
$sortedArray = array();
for ($i = 0; $i < $count; $i++) {
    $sortedArray[date ('YmdHis', filemtime($files[$i]))] = $files[$i];
}
 
ksort($sortedArray);
echo '<div id="emb_dashboard_main_layer_text">Templates</div>';
echo '<div id="emb_dashboard_theme_layer">';
echo '<table>';
$i = 0;
foreach ($sortedArray as &$filename) {
	
    if ( $i % 3 == 0 &&  $i != sizeof($sortedArray) -1 ) echo '<tr>';
	echo '<td>';
    echo '<a name="'.basename($filename).'" href="#'.basename($filename).'"><img src="'.$folder_url. basename($filename).'"  style="height: 267px; width: 150px;"/></a>';
    echo '</td>';
	if ( ($i % 3 == 0 &&  $i != 0) || $i == sizeof($sortedArray) -1 ) echo '</tr>';
	
	$i++;
}
echo '</table>';
echo '</div>';
?>