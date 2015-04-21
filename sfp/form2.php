<?php echo '
<form action="' . WP_PLUGIN_URL . '/sfp/print2.php" method="post" name="form1" target="new" class="form_style" id="form1">
  <p>
    <label for="dt">date : </label>
    <input type="text" name="dt" id="dt" />
  </p>
  <p>
    <label for="cf">Case reference : </label>
    <input type="text" name="cf" id="cf" />
  </p>
  <p>Name : </p>
  <p>
    <label for="nl">Last : </label>
    <input type="text" name="lname" id="nl" />
 
  
    <label for="fname">First : </label>
    <input type="text" name="fname" id="fname" />
  
 
    <label for="mname">Middle : </label>
    <input type="text" name="mname" id="mname" />
  </p>
  <p>Address : </p>
  <p>
    <label for="street">Street : </label>
    <input type="text" name="street" id="street" />

    <label for="city">City : </label>
    <input type="text" name="city" id="city" />

    <label for="state">State, Zip : </label>
    <input type="text" name="state" id="state" />
  </p>
  <p>
    <label for="ss">SS#</label>
    <input type="text" name="ss" id="ss" />
  </p>
  <p>
    <label for="cid">California ID# </label>
    <input type="text" name="cid" id="cid" />
  </p>
  <p>
    <label for="dob">Date of Birth : </label>
    <input type="text" name="dob" id="dob" />
  </p>
  <p>
    <label for="pob">Place of Birth : </label>
    <input type="text" name="pob" id="pob" />
  </p>
  <p>Marrital Status :</p>
  <p>
    <label>
      <input type="radio" name="MarritalStatus_0" value="radio" id="MarritalStatus_0" />
    Single</label>
    
    <label>
      <input type="radio" name="MarritalStatus_1" value="radio" id="MarritalStatus_1" />
      Married</label>
    
    <label>
      <input type="radio" name="MarritalStatus_2" value="radio" id="MarritalStatus_2" />
      Divorced</label>
  </p>
  <p>What is your national origin ?</p>
  <p>
    <label>
      <input type="radio" name="no_0" value="radio" id="no_0" />
    Hispanic</label>
   
    <label>
      <input type="radio" name="no_1" value="radio" id="no_1" />
      African American</label>
   
    <label>
      <input type="radio" name="no_2" value="radio" id="no_2" />
      Caucasian</label>

    <label>
      <input type="radio" name="no_3" value="radio" id="no_3" />
      Other</label>
    <label for="specify">( Specify ) : </label>
    <input type="text" name="specify" id="specify" />
  </p>
  <p>
    <label for="tc">Telephonce Contact# :</label>
    <input type="text" name="tc" id="tc" />
  </p>
  <p>
    <label for="hl">How long have you lived in San Diago : </label>
    <input type="text" name="hl" id="hl" />
  </p>
  <p>
    <label for="pt">Person to contact in case of emergency : </label>
    <input type="text" name="pt" id="pt" />

    <label for="t1">Telephone : </label>
    <input type="text" name="t1" id="t1" />
  </p>
  <p>Please list your close relative ie. Mother, Father , Borther , Sister , etc.</p>
  <p>
    <label for="crn">Name : </label>
    <input type="text" name="crn" id="crn" />

    <label for="crd">Address : Street , City, State, Zip </label>
    <textarea type="text" name="crd" id="crd"></textarea>
  </p>
  <p>
    <label for="crr">Relationship : </label>
    <input type="text" name="crr" id="crr" />
  </p>
  <p>Mother&lsquo;s maiden name : </p>
  <p>
    <label for="mml">Last : </label>
    <input type="text" name="mml" id="mml" />

    <label for="mmf">First : </label>
    <input type="text" name="mmf" id="mmf" />
  </p>
  <p>Name and Address of your Physician : </p>
  <p>
    <label for="pn">Name : </label>
    <input type="text" name="pn" id="pn" />
 
    <label for="pa">Address : Steet , City , State , Zip</label>
    <textarea name="pa" id="pa" cols="45" rows="5"></textarea>
  </p>
<p>    <input type="submit" name="print" id="print" value="Print Second Page" />


</form>';
?>