<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Thank you!</title>
<link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="css/students.css">
</head>

<body>
	<form role="form" method="post" align="right" >
		<div class = logout >
		<div class="button-section">
		 <button type="submit" class="button" name = "logout" formaction="../index.php?logout=true">Logout</button>
	 </div>
	 </div>
	 </form>
<?php
include_once 'creds.php';
$s = $pdo->query("SELECT Deadline FROM Settings");
$date_array = $s->fetch();
$deadline = $date_array['Deadline'];
/*
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$day = $temp[2];
$deadline2 = date('Y-m-d', mktime($mo,$yr,$d));
$year =  date('Y', strtotime($time));
$month = date('F', $time);
$numberDay = date('j', $time);

$date = $month." ".$numberDay.", ".$year;*/
$date = date("M j, Y", strtotime($deadline));
?>
<div class="form">
<h1>Thank you for your submission!</h1>
<form>
    <div class="section"></div>
    <div class="inner-wrap">
        <label>Be sure to check your email and follow up with your advisor. Your application must be approved by your advisor before <b><?php echo $date; ?></b>.</label>
    </div>

 </span>

</form>
</div>

</body>
</html>

