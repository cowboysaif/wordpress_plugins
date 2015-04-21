<?php //error_reporting(0); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Page 1</title>
<style type="text/css">
#name {
	position: absolute;
	left: 161px;
	top: 238px;
	width: 323px;
	height: 31px;
	z-index: 1;
		font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	text-overflow: clip
	font-size: 24px;
	overflow: hidden;
	clip: rect(auto,auto,auto,10);
}
#ssn {
	position: absolute;
	left: 585px;
	top: 240px;
	width: 253px;
	height: 30px;
	z-index: 2;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	clip: rect(auto,auto,auto,10);
}
#case_ref {
	position: absolute;
	left: 983px;
	top: 237px;
	width: 263px;
	height: 34px;
	z-index: 3;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	clip: rect(auto,auto,auto,10);
}
#old_address {
	position: absolute;
	left: 233px;
	top: 419px;
	width: 943px;
	height: 87px;
	z-index: 4;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	clip: rect(auto,auto,auto,10);
}
#new_address {
	position: absolute;
	left: 237px;
	top: 531px;
	width: 922px;
	height: 95px;
	z-index: 5;
	clip: rect(auto,auto,auto,10);
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#telephone {
	position: absolute;
	left: 226px;
	top: 640px;
	width: 402px;
	height: 34px;
	z-index: 6;
	clip: rect(auto,auto,auto,10);
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#license {
	position: absolute;
	left: 875px;
	top: 690px;
	width: 290px;
	height: 42px;
	z-index: 7;
	clip: rect(auto,auto,auto,10);
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#other_specify {
	position: absolute;
	left: 904px;
	top: 751px;
	width: 303px;
	height: 42px;
	z-index: 8;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}

#landlord_manager  {
	position: absolute;
	left: 300px;
	top: 867px;
	width: 411px;
	height: 41px;
	z-index: 9;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#l_tel {
	position: absolute;
	left: 872px;
	top: 868px;
	width: 340px;
	height: 39px;
	z-index: 10;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#pay_to {
	position: absolute;
	left: 266px;
	top: 933px;
	width: 630px;
	height: 33px;
	z-index: 11;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#m_r_a {
	position: absolute;
	left: 375px;
	top: 984px;
	width: 231px;
	height: 38px;
	z-index: 12;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#l_a {
	position: absolute;
	left: 184px;
	top: 1050px;
	width: 972px;
	height: 90px;
	z-index: 13;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#l_cp {
	position: absolute;
	left: 260px;
	top: 1157px;
	width: 396px;
	height: 35px;
	z-index: 14;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#l_sd {
	position: absolute;
	left: 891px;
	top: 985px;
	width: 317px;
	height: 35px;
	z-index: 15;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#c_t {
	position: absolute;
	left: 867px;
	top: 1156px;
	width: 330px;
	height: 37px;
	z-index: 16;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#sw {
	position: absolute;
	left: 248px;
	top: 1293px;
	width: 908px;
	height: 41px;
	z-index: 17;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#ao {
	position: absolute;
	left: 163px;
	top: 1355px;
	width: 995px;
	height: 39px;
	z-index: 18;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
}
#apDiv1 {
	position: absolute;
	left: 312px;
	top: 310px;
	width: 25px;
	height: 25px;
	z-index: 19;
}
#apDiv2 {
	position: absolute;
	left: 426px;
	top: 309px;
	width: 23px;
	height: 26px;
	z-index: 20;
}
#apDiv3 {
	position: absolute;
	left: 314px;
	top: 366px;
	width: 23px;
	height: 25px;
	z-index: 21;
}
#apDiv4 {
	position: absolute;
	left: 423px;
	top: 366px;
	width: 27px;
	height: 27px;
	z-index: 22;
}
#apDiv5 {
	position: absolute;
	left: 388px;
	top: 711px;
	width: 27px;
	height: 27px;
	z-index: 23;
}
#apDiv6 {
	position: absolute;
	left: 389px;
	top: 767px;
	width: 24px;
	height: 27px;
	z-index: 24;
}
#apDiv7 {
	position: absolute;
	left: 686px;
	top: 769px;
	width: 26px;
	height: 22px;
	z-index: 25;
}
</style>
</head>

<body>
<div id="name"><?php echo $_POST['name']; ?></div>
<div id="landlord_manager"><?php echo $_POST['landlord_manager']; ?></div>
<div id="l_tel"><?php echo $_POST['l_tel']; ?></div>
<div id="pay_to"><?php echo $_POST['pay_to']; ?></div>
<div id="m_r_a"><?php echo $_POST['m_r_a']; ?></div>
<div id="l_a"><?php echo nl2br($_POST['l_a']); ?></div>
<div id="l_cp"><?php echo $_POST['l_cp']; ?></div>
<div id="l_sd"><?php echo $_POST['l_sd']; ?></div>
<div id="c_t"><?php echo $_POST['c_t']; ?></div>
<div id="sw"><?php echo $_POST['sw']; ?></div>
<div id="ao"><?php echo $_POST['ao']; ?></div>
<div id="apDiv1">
  <input name="radio1" type="radio" id="ncy" value="ncy"  <?php if ( isset($_POST['new_client_0'])) echo 'checked="checked"' ; ?> />
  <label for="ncy"></label>
</div>
<div id="apDiv2">
  <input type="radio" name="radio1" id="ncn" value="ncn"<?php if ( isset($_POST['new_client_1'])) echo 'checked="checked"' ; ?>  />
  <label for="ncn"></label>
</div>
<div id="apDiv3">
  <input name="radio2" type="radio" id="ac" value="ac" <?php if ( isset($_POST['address_change_0'])) echo 'checked="checked"' ; ?>  />
  <label for="ac"></label>
</div>
<div id="apDiv4">
  <input type="radio" name="radio2" id="ac2" value="ac" <?php if ( isset($_POST['address_change_1'])) echo 'checked="checked"' ; ?>  />
  <label for="ac2"></label>
</div>
<div id="apDiv5">
  <input name="radio" type="radio" id="tabc" value="tabc" ?>
  <label for="tabc"></label>
</div>
<div id="apDiv6">
  <input type="radio" name="radio" id="tail" value="tail" />
  <label for="tail"></label>
</div>
<div id="apDiv7">
  <input type="radio" name="radio" id="taos" value="taos" />
  <label for="taos"></label>
</div>
<img src="Binder1.png" width="1275" height="1650" />
<div id="ssn"><?php echo $_POST['ssn']; ?></div>
<div id="case_ref"><?php echo $_POST['case_ref']; ?></div>
<div id="old_address"><?php echo nl2br($_POST['old_address']); ?></div>
<div id="new_address"><?php echo nl2br($_POST['new_address']); ?></div>
<div id="telephone"><?php echo $_POST['telephone']; ?></div>
<div id="license"><?php echo $_POST['license']; ?></div>
<div id="other_specify"><?php echo $_POST['other_specify']; ?></div>

</body>
<script type="text/javascript">
window.print();
</script>
</html>
