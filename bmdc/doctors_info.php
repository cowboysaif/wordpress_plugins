<?php
if ( isset($_POST['submit'] )  != false ) {
echo '
<div>
  <form id="form1" name="form1" method="post" action="">
    <label for="registration1_no">Please enter your registration number : </label>
    <input type="text" name="registration1_no" id="registration1_no" />
    <input type="submit" name="submit" id="submit" value="Submit" />
  </form>
</div>';
}

if ( isset($_POST['submit']) ) {
 $registration1_no = $_POST['submit'];
 echo $registration1_no;
}
?>
