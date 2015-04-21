<?php
echo '
<div id="main">
	<div id="emb_dashboard_header1">Mobile</div>
	<div id="emb_dashboard_header2">Builder</div>
	<div id="emb_dashboard_menu_bg">
		<div class="emb_dashboard_menu" style="margin-right: 545px;">
		<form id="dashboard" action="" method="post">
		<input type="hidden" name="page" value="dashboard"></input>
		<a href="javascript:{}" onclick="document.getElementById(\'dashboard\').submit();" style="text-decoration: none; color: rgb( 255, 254, 255 );">Dashboard</a>
		</form></div>	
		<div class="emb_dashboard_menu" style="margin-top: -23px; margin-left: 62px;">
		<form id="templates" action="" method="post">
		<input type="hidden" name="page" value="templates"></input>
		<a href="javascript:{}" onclick="document.getElementById(\'templates\').submit();" style="text-decoration: none; color: rgb( 255, 254, 255 );">Templates</a>
		</form></div>
		<div class="emb_dashboard_menu" style="margin-top: -23px; margin-left: 253px;">
		<form id="creation" action="" method="post">
		<input type="hidden" name="page" value="creation"></input>
		<a href="javascript:{}" onclick="document.getElementById(\'creation\').submit();" style="text-decoration: none; color: rgb( 255, 254, 255 );">Creation</a>
		</form></div>
	</div>
	<div id="emb_dashboard_main_layer">';
		if ( isset($_POST['page'])) {
			if ( $_POST['page'] == 'dashboard' ) {
				include('dashboard.php');
			}
			else if ( $_POST['page'] == 'templates') {
				include('templates.php');
			}
			else if ( $_POST['page'] == 'creation' ) {
				include('creation.php');
			}
		}
		else {
		echo '<div id="emb_dashboard_main_layer_text">Dashboard</div>
		<div id="emb_dashboard_theme_layer">
		</div>';
		}
	echo'</div>
</div>';
?>