<?php

$default_address = "Paint By Sight Art Studio&#10;12374 KeyLime Blvd&#10;West Palm Beach FL 33412&#10;561-246-0432";
// jsDate junks

echo '<link rel="stylesheet" type="text/css" media="all" href="'.WP_PLUGIN_URL.'/PaintBySight/js/jsdatepick/jsDatePick_ltr.min.css" />';
echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/PaintBySight/js/jsdatepick/jsDatePick.min.1.3.js"></script>';
echo '<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"event_date",
			dateFormat:"%d-%M-%Y"
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
	};
</script>';

//here comes time date junk !

echo '<link rel="Stylesheet" media="screen" href="'.WP_PLUGIN_URL.'/PaintBySight/js/ui-timepickr/dist/themes/default/ui.core.css" />
        <link rel="Stylesheet" media="screen" href="'.WP_PLUGIN_URL.'/PaintBySight/js/ui-timepickr/src/css/ui.timepickr.css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.js"></script>
        <script type="text/javascript" src="'.WP_PLUGIN_URL.'/PaintBySight/js/ui-timepickr/page/jquery.utils.js"></script>
        <script type="text/javascript" src="'.WP_PLUGIN_URL.'/PaintBySight/js/ui-timepickr/page/jquery.strings.js"></script>
        <script type="text/javascript" src="'.WP_PLUGIN_URL.'/PaintBySight/js/ui-timepickr/page/jquery.anchorHandler.js"></script>
        <script type="text/javascript" src="'.WP_PLUGIN_URL.'/PaintBySight/js/ui-timepickr/page/jquery.ui.all.js"></script>
        <script type="text/javascript" src="'.WP_PLUGIN_URL.'/PaintBySight/js/ui-timepickr/src/ui.timepickr.js"></script>
		<script type="text/javascript" src="'.WP_PLUGIN_URL.'/PaintBySight/js/jscolor/jscolor.js"></script>';
		
		
echo"<script type='text/javascript'>
  $(function(){
      $('#event_time').timepickr();
  });
  </script>";
  
// tiny MCE junk

echo '<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<script>
        tinymce.init({selector:"textarea"});
		tinymce.activeEditor.setContent("'.$default_address.'");
		
		function SubmitForm() {
    	tinymce.get("event_description").save();
		tinymce.get("event_address").save();
		
}
</script>';


//jscolor event
echo '<script type="text/javascript">
	function updateInfo(color) {
			document.getElementById("event_box_time__color").value = color;
			
	}

	</script>';
  
// page actually starts here ! argh

echo '<h1>PaintBySight Calendar</h1>';
if ( isset($_POST['create_event']) == false && isset($_POST['transaction']) == false && isset($_POST['event_done']) == false&& isset($_POST['css']) == false && isset($_POST['css_done']) == false) {
	include('first_page.php');
}

else if ( isset($_POST['create_event'])) {
	include('create_event.php');
}
else if ($_POST['transaction'] ) {
	echo 'Transaction event fired';
}

else if ($_POST['event_done'] ) {
    include('event_done.php');
}

else if ($_POST['css'] ) {
    include('css_settings.php');
}

else if ($_POST['css_done'] ) {
    include('css_done.php');
}

else {
	echo 'something went wrong';
}
?>