<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="index.css">

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
	$sth = $pdo->prepare("SELECT RName, Major, REmail FROM Reviewers WHERE Active = 1");
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
		<h4>Evaluators</h4>
		<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
			<tr>
				<th>Name</th>
				<th>Department</th>
				<th>E-mail </th>
				<th></th>
			</tr>

			<?php $sth->execute();
			foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
		
			<tr>
			<td><?php echo $row[RName]; ?></td>
			<td><?php echo $row[Major]; ?></td>
			<td><?php echo $row[REmail];?></td>
			<form method = "POST">
			<input type = "hidden" value = <?php echo $row[REmail];?> name = "id" id = "id"/>
			<td><button type = "submit" formaction = "reviewerRemoved.php">Remove</button></td>
			</form>
			</tr>

			<?php endforeach;?>

			<tr><td></td><td></td><td></td><td></td></tr>

			<tr>
				<form method = "POST">
				<td><input id = 'Name' name = 'Name' type = 'Name' placeholder="Full Name"></td>
				<td><select name='Department' id='Department' required>
				  <option disabled selected value>Department</option>
					<option value="Biology">Biology</option>
					<option value="Chemistry">Chemistry or Biochemistry</option>
					<option value="Computer Science">Computer Science</option>
					<option value="Design">Design</option>
					<option value="Engineering">Enginerring</option>
					<option value="Environmental Science">Environmental Science</option>
					<option value="Geology">Geology</option>
					<option value="Mathematics">Mathematics</option>
					<option value="Natural Science">Natural Science</option>
					<option value="Physics">Physics</option>
				  </select></td>
				<td><input id = 'email' name = 'email' type = 'email' placeholder="EWU Email" pattern = "^([a-zA-Z0-9_\-\.]+)@ewu.edu)$"></td>
				<td><button type = "submit" formaction = "reviewerAdded.php">Add</button></td>
				</form>
			</tr>
		</table><br>

		<br>
		</div>
		<br>
	</div>
</body>
</html>