<?php echo '
</form>

<form action="' . WP_PLUGIN_URL . '/sfp/print3.php" method="post" name="form1" target="new" class="form_style" id="form1">
  <h1>Representative Payee Service</h1>
  <p>
    <label for="name">Name :</label>
    <input type="text" name="name" id="name" />
  </p>
  <p>
    <label for="ssn">SSN# : </label>
    <input type="text" name="ssn" id="ssn" />
  </p>
  <p>
    <label for="case_ref">Case Ref# : </label>
    <input type="text" name="case_ref" id="case_ref" />
  </p>
  <p><img src="'.WP_PLUGIN_URL .'/sfp/Binder1-3.jpg" width="607" height="823" /></p>
  <p>I 
    <label for="myname"></label>
    <input name="myname" type="text" id="myname" size="50" />
  Accept the above terms and conditions.</p>
<p>    <input type="submit" name="print" id="print" value="Print Third Page" />
  
  
</form>';
?>	