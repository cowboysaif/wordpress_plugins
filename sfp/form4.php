<?php echo '
</form>

<form action="'. WP_PLUGIN_URL.'/sfp/print4.php" method="post" name="form1" target="new" class="form_style" id="form1">
  <p>Landlord information :  </p>
  <p>
    <label>
      <input type="radio" name="toa_0" value="radio" id="toa_0" />
      Board & Care</label>
  
    <label>
      <input type="radio" name="toa_1" value="radio" id="toa_1" />
      Independent Living</label>
   
    <label>
      <input type="radio" name="toa_2" value="radio" id="toa_2" />
      Private</label>
    <label for="private"></label>
    <input type="text" name="private" id="private" />
<label>
      <input type="radio" name="toa_3" value="radio" id="toa_3" />
      Other</label>
    (
    <label for="specify">Specify ) : </label>
    <input type="text" name="specify" id="specify" />
  </p>
  <p>
    <label for="bnc">B &amp; C License</label>
    <input type="text" name="bnc" id="bnc" />
  </p>
  <p>
    <label for="lname">Landlord Name : </label>
    <input type="text" name="lname" id="lname" />
  </p>
  <p>Address : </p>
  <p>
    <label for="street">Street : </label>
    <input type="text" name="street" id="street" />
    <label for="city">City : </label>
    <input type="text" name="city" id="city" />
    <label for="state">State : </label>
    <input type="text" name="state" id="state" />
</p>
  <p>
    <label for="zip">Zip code : </label>
    <input type="text" name="zip" id="zip" />
  </p>
  <p>
    <label for="telephone">Telephone : </label>
    <input type="text" name="telephone" id="telephone" />
    <label for="altTelephone">Alt Telephone #:</label>
    <input type="text" name="altTelephone" id="altTelephone" />
  </p>
  <p>
    <label for="rent">Rent payable to :</label>
    <input name="rent" type="text" id="rent" size="50" />
  </p>
  <p>
    <label for="mrent">Total monthly rental : </label>
    <input type="text" name="mrent" id="mrent" />
    <label for="utilities">Utilities : </label>
     $ 
     <input type="text" name="utilities" id="utilities" />
  </p>
  <p>
    <label for="jamaa">Monthly rental portion paid by Jamaa</label>
    <input type="text" name="jamaa" id="jamaa" />
  </p>
  <p>
    <label for="sd">Security deposit : </label>
    <input type="text" name="sd" id="sd" />
  </p>
  <h2>Tenant Information : </h2>
  <p>
    <label for="tname">Name : </label>
    <input name="tname" type="text" id="tname" size="50" />
    <label for="ttelephone">Telephone : </label>
    <input type="text" name="ttelephone" id="ttelephone" />
  </p>
  <p>
    <label for="street">Street : </label>
    <input type="text" name="street2" id="street" />
    <label for="city">City : </label>
    <input type="text" name="city2" id="city" />
    <label for="state">State : </label>
    <input type="text" name="state3" id="state" />
  </p>
  <p>
    <label for="state">State : </label>
    <input type="text" name="state3" id="state" />
    <label for="zip">Zip code : </label>
    <input type="text" name="zip2" id="zip" />
  </p>
  <p>
    <label for="date">Date Tenant entered facility : </label>
    <input type="text" name="date" id="date" />
  </p>
  <p>
    <label for="trtb">Tenant referred to by :</label>
    <input type="text" name="trtb" id="trtb" />
  </p>
  <p><br />
  </p>
<p>    <input type="submit" name="print" id="print" value="Print Fourth Page" />
  
  
</form>';
?>