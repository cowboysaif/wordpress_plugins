<?php
echo '<form method="post" action="" enctype="multipart/form-data">
		<p>Event / Class Name:
		<input type="text" name="event_name" required></input>
		</p>
		<p>Date of Event:
		<input type="text" id="event_date" name="event_date" required></input>
		</p>
		<p>Number of Participants:
		<input type="text" id="num_part" name="num_part" required></input>
		</p>
		<p style="margin-bottom: 60px;">Time of Event:
		<input type="text" id="event_time" name="event_time" value="09:00" required></input>
		</p>
	
		<p>Description of Events:</p>
		<textarea type="text" name="event_description" cols="90" rows="20"></textarea>
		<p>Featured Image:
		<input type="file" name="event_image" accept="image/*" required></input>
		</p>
		<p>Type of Event / Class:<br/>
		<label>
		  <input type="radio" name="event_type" id="event_type_0" value="art" required>
		  Art Class</input></label>
		<br>
		<label>
		  <input type="radio" name="event_type" id="event_type_1" value="paint" required>Paint Class</input>
		  </label>
		<br>
		<label>
		  <input type="radio" name="event_type" id="event_type_2" value="kids" required>Kids class</input>
		  </label>
		<br>
		<label>
		  <input type="radio" name="event_type" id="event_type_3" value="adult" required>Adult class</input>
		  </label>
		<br>
		<label>
		  <input type="radio" name="event_type" id="event_type_4" value="special" required>Special Event</input>
		  </label>
		<br>
		<label>
		  <input type="radio" name="event_type" id="event_type_5" value="other" required>Other</input>
		  </label>
	  </p>
	  <p>Required Materials Needed:<br/>
		<label>
		  <input type="radio" name="material" id="material_type_0" value="supplied" required>Materials Supplied</input></label>
		<br>
		<label>
		  <input type="radio" name="material" id="material_type_1" value="own" required>Bring Your Own Materials</input></label>
	  </p>
		<p>Address:</p>
		<textarea cols ="30" rows ="6" id="event_address" name="event_address">'.$default_address.'</textarea><br/>
		<input type="hidden" name="event_done" value="1"></input>
		<input type="checkbox" name="publish" value="true">Publish this event immediately<br>
		<input type="submit" value="Submit" onClick="SubmitForm();"></input>		
	</form>';	
?>