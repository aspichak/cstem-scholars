<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Thank you!</title>
<link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/savedPage.css">
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
$date = date("M j, Y", strtotime($deadline));

?>
<div class="form">
<h1>Your application has been saved!</h1>
<form>
    <div class="section"></div>
    <div class="inner-wrap">
        <label>You can come back any time before the deadline to update and submit your application. Be sure to have your application submitted and approved by your advisor before <b><?php echo $date; ?></b>.</label>
    </div>

 </span>

</form>
</div>

</body>
</html>

