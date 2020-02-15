<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="editEmail.css">

	<?php 
	$database = parse_ini_file("config.ini");
	$host = $database['host'];
	$db = $database['db'];
	$user = $database['user'];
	$pass = $database['pass'];

	$charset = 'utf8mb4';
	$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	try {
		$pdo = new PDO($dsn, $user, $pass, $options);
	} catch (\PDOException $e) {
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
	$email = $_POST['emailType'];
	
	
	$sth = $pdo->prepare("SELECT ". $email ." FROM Settings");
	$sth->execute();
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	?>

</head>
<body>
	<div class="sidenav">
	<img src ="img/ewueagle.png" height=125px; width = 185px;>
		<br>
		<br>
		<a href="index.php">Home</a>
		<br>
		<a href="edit.php">Edit</a>
		<br>
		<a href ="results.php">Results</a>
		<br>
		<a href="prior.php">Prior Awards</a>
		<br>
		<a href="search.php">Search</a>
		<br>
		<a href="new.php">New</a>
		<br>
		<br>
		<br>
		<a href ="../index.php?logout=true">Logout</a>
	</div>

	<div class="main">
		<div class="w3-container">
			<br>
			<h3>Edit Email</h3>
			<form method = "POST">
				<input type = "hidden" value = <?php echo $email;?> name = "emailType" id = "emailType"/>
				<textarea name = "email" id = "email" rows="8" cols="100"><?php echo $row[$email];?></textarea>
				<br>
				<button type = "submit" formaction = "saveEmail.php">Save</button>
			</form>
		</div>
	</div>
</body>
</html>
