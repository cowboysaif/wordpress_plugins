<?php
echo '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';
echo '<link rel="Stylesheet" media="screen" href="'.WP_PLUGIN_URL.'/PaintBySight/calendar_stylesheet.css" />';
 //This gets today's date
 $date =time () ;

 //This puts the day, month, and year in seperate variables

 $day = date('d', $date) ;

 $month = date('m', $date) ;

 $year = date('Y', $date) ;



 //Here we generate the first day of the month

 $first_day = mktime(0,0,0,$month, 1, $year) ;



 //This gets us the month name

 $title = date('F', $first_day) ;

 //Here we find out what day of the week the first day of the month falls on
 $day_of_week = date('D', $first_day) ;



 //Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero

 switch($day_of_week){

 case "Sun": $blank = 0; break;

 case "Mon": $blank = 1; break;

 case "Tue": $blank = 2; break;

 case "Wed": $blank = 3; break;

 case "Thu": $blank = 4; break;

 case "Fri": $blank = 5; break;

 case "Sat": $blank = 6; break;

 }



 //We then determine how many days are in the current month

 $days_in_month = cal_days_in_month(0, $month, $year) ;

 //Here we start building the table heads

 echo "<table class='calendar' border=1 width=294>";

 echo "<tr><th colspan=7> $title $year </th></tr>";

 echo "<tr><td width=42>S</td><td width=42>M</td><td
width=42>T</td><td width=42>W</td><td width=42>T</td><td
width=42>F</td><td width=42>S</td></tr>";



 //This counts the days in the week, up to 7

 $day_count = 1;



 echo "<tr>";

 //first we take care of those blank days

 while ( $blank > 0 )

 {

 echo "<td></td>";

 $blank = $blank-1;

 $day_count++;

 }

 //sets the first day of the month to 1

 $day_num = 1;



 //count up the days, untill we've done all of them in the month

 while ( $day_num <= $days_in_month )

 {

//enough code copy. now we start our operation.
// get their date 
if ( $day_num < 10 ) $day_num = '0' . $day_num;
$sql_date = $day_num .'-'. strtoupper(substr($title,0,3)) .'-'. $year;
	$table_name = $wpdb->prefix. "calendar";
	$sql = 'SELECT * FROM ' . $table_name . ' WHERE event_date = "'.$sql_date.'"';
	//echo $sql;
	$result = $wpdb->get_results($sql);
	//var_dump($result);
 echo '<td> <p>'.$day_num.'</p>';
 				for ( $i=0; $i < count($result); $i++ ) { 
 			echo '
			<div class="event_box">
			<img class="event_box_image" src="'.WP_PLUGIN_URL.'/PaintBySight/uploads/'.$result[$i]->event_image.'"></img>
			<h1 class="event_box_time">'.$result[$i]->event_time.'</h1>
			<h1 class="event_box_name">'.$result[$i]->event_name.'</h1>
			</div>
			<div class="event_popup" style="">
			<h1 class="event_popup_name">'.$result[$i]->event_name.'</h1>
			<h1 class="event_popup_time">'.$day_of_week.' '.$title. ' ' . $day_num. ', '. $year. ' at '.$result[$i]->event_time.'</h1>
				<img class="event_popup_image" src="'.WP_PLUGIN_URL.'/PaintBySight/uploads/'.$result[$i]->event_image.'"></img>
			</div>
			<div class="more" style="position: absolute; border:1px solid #000; width: 1035px; top:0; bottom: 0; left: 0; right: 0; margin: auto; padding: 5px; background: black;" >
			<h1 style="text-align: center; font-size: 350%; cursor=pointer; color:#E54F8F ">'.$result[$i]->event_name.'</h1>
			<hr>
			<img  style="max-height: 300px; max-widht: 300px display: block; padding-left: 400px;" src="'.WP_PLUGIN_URL.'/PaintBySight/uploads/'.$result[$i]->event_image.'"></img>
			<div style="text-align: center; font-size: 200%; cursor=pointer; color: white">'.$result[$i]->event_description.'</div>
			<div style="text-align: left; font-size: 200%; cursor=pointer; color:#E54F8F">'.$day_of_week.' '.$title. ' ' . $day_num. ', '. $year. ' at '.$result[$i]->event_time.'</div>
			<div style="text-align: left; font-size: 200%; cursor=pointer; color:#E54F8F"> Total Seats :'.$result[$i]->num_part.'</div>
			<div style="text-align: left; font-size: 200%; cursor=pointer; color:#E54F8F"> Avaiable Seats :'.($result[$i]->num_part - $result[$i]->registered ).'</div>
			<div style="text-align: left; font-size: 200%; cursor=pointer; color:#E54F8F"> Material :'.$result[$i]->material .'</div>
			</div>
			</td>';
			echo '<script type="text/javascript">
			$(".more").hide();
			$(".event_popup").hide();
			$(".event_popup_image").click(function() {
    		$(".event_popup").fadeIn();
			});
			$(".more").click(function() {
    		$(".more").fadeOut();
			});
			$(".event_box_image").click(function() {
    		$(".more").fadeIn();
			});
    		
			$(".event_box_image").mouseover(function() {
    		$(".event_popup").fadeIn();
			}).mouseout(function() {
    		$(".event_popup").fadeOut();
			});
			</script>';
				
				}

 $day_num++;

 $day_count++;



 //Make sure we start a new row every week

 if ($day_count > 7)

 {

 echo "</tr><tr>";

 $day_count = 1;

 }

 }

 //Finaly we finish out the table with some blank details if needed

 while ( $day_count >1 && $day_count <=7 )

 {

 echo "<td> </td>";

 $day_count++;

 }

 
 echo "</tr></table>";
?>