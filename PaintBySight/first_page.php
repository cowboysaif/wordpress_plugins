<?php
echo '<form method="post" action="">
		<input type="hidden" name="create_event" value="1"></input>
		<input type="submit" value="Create Event"></input>
	</form>';
	
echo '<form method="post" action="">
		<input type="hidden" name="transaction" value="1"></input>
		<input type="submit" value="show booking/transaction"></input>
	</form>';
	
echo '<form method="post" action="">
		<input type="hidden" name="css" value="1"></input>
		<input type="submit" value="Edit css settings"></input>
	</form>';
	global $wpdb;
	$table_name = $wpdb->prefix. "calendar";
	$sql = 'SELECT * FROM ' . $table_name;
	$sql_for_post = $sql;
	$result = $wpdb->get_results($sql);

	echo '<table>
			<tr>
				<th>Event name  </th>
				<th>Event date  </th>
				<th>Event time  </th>
				<th>Number of Participants  </th>
				<th>Remaining Seats  </th> 
			</tr>
			';
	for ( $i = 0 ; $i< count($result); $i++ ) {
			echo '<tr>
					<td>' .$result[$i]->event_name. '</td>
					<td>' .$result[$i]->event_date. '</td>
					<td>' .$result[$i]->event_time. '</td>
					<td>' .$result[$i]->num_part. '</td>
					<td>' .($result[$i]->num_part - $result[$i]->registered). '</td>
				</tr>';			 
			
	}
	echo '</table>';

?>