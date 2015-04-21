<?php
echo '
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="'.WP_PLUGIN_URL.'/easy_mobile_builder/jscolor/jscolor.js"></script>
 <script>
$(function() {
$( "#accordion" ).accordion();
});
</script>
<div id="emb_dashboard_main_layer_text">Create splash</div>
				<div id="emb_dashboard_theme_layer">
					<img id="emb_creation_mobile_view" src="'.WP_PLUGIN_URL.'/easy_mobile_builder/templates/4.jpg"></img><div id="accordion">
<h3 id="emb_creation_div_header">Color settings</h3>
<div id="emb_creation_div">
<p>Choose Background Image</p>
<input type="file"></input>
<p>Choose Solid Background Color</p>
<input type="text" value="#"></input>
<input class="color">
</div>
</div>
</div>
';
?>