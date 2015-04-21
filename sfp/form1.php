
<?php 
echo  '
<form action="' .  WP_PLUGIN_URL . '/sfp/print1.php" method="post" name="form1" target="new" class="form_style" id="form1">
  <p>&nbsp;</p>
  <p>
    <label for="name">Name : 
    <input type="text" name="name" id="name" /></label>
  </p>
    
    <label for="ssn">SSN# : </label>
    <input type="text" name="ssn" id="ssn" />
  <script type="text/javascript"> space(28)</script>
    <label for="case_ref"><br />
      <br />
    Case Ref# : </label>
    <input type="text" name="case_ref" id="case_ref" />
  </p>
  <p>
    <label>
       New Client : <script type="text/javascript"> space(15)</script>
       <input type="radio" name="new_client_0" value="radio" id="new_client_0" />
    yes</label>
  
    <label>
      <input type="radio" name="new_client_1" value="radio" id="new_client_1" />
      no</label>
  </p>
  <p>
    <label>Address change : <script type="text/javascript"> space(7)</script>
      <input type="radio" name="address_change_0" value="radio" id="address_change_0" />
      yes</label>
    <label>
      <input type="radio" name="address_change_1" value="radio" id="address_change_1" />
      no</label>
</p>
  <p>
    <label for="old_address">Old Address : 
    <textarea name="old_address" id="old_address" rows="5" cols="40" ></textarea></label>
  </p>
  <p>
    <label for="new_address">Old Address : 
    <textarea name="new_address" id="new_address" rows="5" cols="40" ></textarea></label>
    
  </p>
  <p>
    <label for="telephone">Telephone :</label>
    <script type="text/javascript"> space(3)</script>
    <input type="text" name="telephone" id="telephone" />
</p>
  <p>
    <label>
      <input type="radio" name="type_of_accomodation" value="radio" id="type_of_accomodation_0" />
      Board & Care</label>
    
    <label>
      <input type="radio" name="type_of_accomodation" value="radio" id="type_of_accomodation_1" />
      Independent Living</label>
   
    <label>
      <input type="radio" name="type_of_accomodation" value="radio" id="type_of_accomodation_2" />
      Other ( Specify ) :</label>
    <label for="other_specify"></label>
    <input type="text" name="other_specify" id="other_specify" />
    <br />
  </p>
  <p>
    <label for="telephone">B&amp;C license :</label>
    <script type="text/javascript"> space(3)</script>
    <input type="text" name="license" id="telephone" />
  </p>
  <p>
    <label for="landlord_manager">Landlord / Manager</label>
:    
<input type="text" name="landlord_manager" id="landlord_manager" />  
  <p>
    
    
    <label for="l_tel">Telephone :</label>
    <input type="text" name="l_tel" id="l_tel" />
    
  <p>
    <label for="pay_to">Rent Payable to :</label>
  <input type="text" name="pay_to" id="pay_to" />    
  <p>
    <label for="m_r_a">Monthly Rental Amout :</label>
    <input type="text" name="m_r_a" id="m_r_a" />
  </p>
  <p>
    <label for="l_sd">Security Deposit : </label>
    <input type="text" name="l_sd" id="l_sd" />
  </p>
  <p>
    <label for="l_a">Address :</label>
    <textarea name="l_a" id="l_a" cols="45" rows="5"></textarea>
  </p>
  <p>
    <label for="l_cp">Contact Person : </label>
    <input type="text" name="l_cp" id="l_cp" />
    <label for="c_t">Telephone : </label>
    <input type="text" name="c_t" id="c_t" />
  </p>
  <p>
    <label for="sw">Social Worker : </label>
    <input type="text" name="sw" id="sw" />
  </p>
  <p>
    <label for="ao">Other : </label>
    <input type="text" name="ao" id="ao" />
  </p>
  <p>&nbsp;</p>
  <p>
  <input name="page" type="hidden" value="2" />
    <input type="submit" name="print" id="print" value="Print First Page" />
    </p>
    </form>';
?>

