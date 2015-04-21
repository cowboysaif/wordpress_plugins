<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Page 3</title>
<style type="text/css">
#name {
	position: absolute;
	left: 197px;
	top: 284px;
	width: 272px;
	height: 35px;
	z-index: 1;
			font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	clip: rect(auto,auto,auto,10);
}
#ssn {
	position: absolute;
	left: 575px;
	top: 282px;
	width: 235px;
	height: 37px;
	z-index: 2;
			font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	clip: rect(auto,auto,auto,10);
}
#case_ref {
	position: absolute;
	left: 953px;
	top: 282px;
	width: 185px;
	height: 38px;
	z-index: 3;
			font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	clip: rect(auto,auto,auto,10);
}
#myname {
	position: absolute;
	left: 138px;
	top: 1322px;
	width: 437px;
	height: 45px;
	z-index: 4;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 24px;
	clip: rect(auto,auto,auto,10);
}
</style>
</head>

<body>
<div id="name"><?php echo $_POST['name']; ?></div>
<div id="ssn"><?php echo $_POST['ssn']; ?></div>
<div id="case_ref"><?php echo $_POST['case_ref']; ?></div>
<div id="myname"><?php echo $_POST['myname']; ?></div>
<img src="Binder3.png" width="1275" height="1650" />
<script type="text/javascript"> window.print();</script>
</body>
</html>
