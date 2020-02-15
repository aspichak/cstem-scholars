<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="search.css">
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
	$name = $_REQUEST['name'];
	 $sth = $pdo->prepare("SELECT SID FROM Student WHERE SName like '%$name%' ");
	 $sth->execute();
	$result = $sth->fetch();
	$id = $result[0];
	$sth = $pdo ->prepare("SELECT TABLE_NAME
	FROM INFORMATION_SCHEMA.TABLES 
	WHERE TABLE_NAME LIKE 'Applications%'");
	$sth->execute();
	$result = $sth->fetchAll();
	reset($result);
	$count = 0;
	foreach($result as $value)
	{
		$result[$count] = "SELECT *, '$value[0]'as 'tablename' FROM ".$value[0]." where Submitted =1 and SID=$id";
		$count ++;
	}
	$queryStatement;
	foreach($result as $value)
	{
		$queryStatement .= $value." UNION ";
	}
	$queryStatement = substr($queryStatement, 0, -6);
	$queryStatement .= ";";
	?>
</head>
<body>
<form method = "post">
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
			<h4>Search by name:</h4>
			<input>
			<button type='submit' disabled formaction ='searchsorted.php' >Go</button>
			<button formaction ='search.php'>Back</button>
		</div>
		<div class="w3-container">
			<br>
			<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
	 
				<tr>
					<th>Name</th>
					<th>SID</th>
					<th>Major</th>
					<th>Project Title </th>
					<th>Awarded</th>
					<th>Amount Awarded</th>
					<th>View</th>
				</tr>
				<?php
			 		$sth = $pdo->prepare($queryStatement);
					$sth->execute();
				 foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row){ 
				 $sid = $row['SID'];
				 $query = $pdo->prepare("SELECT SName, Major FROM Student WHERE SID = $sid");
				 $query->execute();
				 $student = $query->fetch();
				 ?>
				<tr>
					<td><?php echo $student['SName'] ;?></td>
					<td><?php echo $row['SID'] ;?></td>
					<td><?php echo $student['Major'];?></td>
					<td><?php echo $row['PTitle'] ;?></td>
					<td><?php echo $row['Awarded'] ;?></td>
					<td><?php echo $row['AmountGranted'] ;?></td>
					<td><button type='submit' formaction="view.php?id=<?php echo $row['ApplicationNum'];?>&table=<?php echo $row['tablename']?>">View</button>
				</tr>
				<?php }?>
			</table>
		</div>
	</div>
</form>
</body>
</html>
