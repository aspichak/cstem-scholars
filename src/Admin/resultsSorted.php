<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="resultsSorted.css">
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
$sort = $_POST['sort'];
$sth = $pdo->prepare("SELECT Deadline FROM Settings");
$sth->execute();
$date_array = $sth->fetch();
$deadline = $date_array['Deadline'];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$tableName = 'ReviewedApps'.$month.$year;
$appTabe = 'Applications'.$month.$year;

if($sort==Score){
	$sort="AVG(".$tableName.".QATotal)";
	}

$sth = $pdo->prepare("SELECT Student.SName,a1.ApplicationNum, Student.SID,Student.Major, Student.SEmail, a1.Objective,
a1.RequestedBudget, AVG(r1.QATotal), a1.Awarded, a1.AmountGranted FROM Student, ".$tableName.  " r1 ," .$appTabe. " a1 
WHERE a1.Submitted=1 and Student.SID=a1.SID and a1.ApplicationNum = r1.ApplicationNum 
GROUP BY a1.ApplicationNum
ORDER BY ". $sort. " ASC");
?>
</head>
<body>
<form  method="post">
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
			<h4>Current Results</h4>
			<select id="sort" name="sort">
				<option value="Score">Score</option>
				<option value="Major">Major</option>
				<option value="Budget">Requested Budget</option>
				<option value="Awarded">Awarded</option>
			</select>
			<button type = "submit" formaction = "resultsSorted.php">Apply</button>

			<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
			  <tr>
				<th>Name</th>
				<th>Major</th>
				<th>Email</th>
				<th>Objective </th>
				<th>Requested Budget</th>
				<th>Score Total</th>
				<th>Awarded</th>
				<th>Amount Granted </th>
				<th> View Scoring </th>
				<th> View Application </th>
				<th>Award</th>
			  </tr>
				<?php $sth->execute();
				//$result= $sth->fetch();
				foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
				<?php $app = $row['ApplicationNum'];?>
			  <tr>
				<td><?php echo $row['SName'];?></td>
				<td><?php echo $row['Major'];?></td>
				<td><?php echo $row['SEmail'];?></td>
				<td><?php echo $row['Objective'];?></td>
				<td><?php echo $row['RequestedBudget'];?></td>
				<td><?php echo number_format($row['AVG(r1.QATotal)'],2,".","");?></td>
				<td><?php echo $row['Awarded'];?></td>
				<td><?php echo $row['AmountGranted'];?></td>
				<td><button type = "submit" formaction= "scoring.php?id=<?php echo $app ?>" id= <?php echo $app;?>>Scoring</button></td>
				<td><button type = "submit" formaction = "appView.php?id=<?php echo $app?>">View </button></td>
				<td><button type = "submit" formaction= "award.php?id=<?php echo $row[ApplicationNum]?>">Select</button></td>
			  </tr>
				<?php endforeach;?>
			</table><br>
			<button formaction="export.php" name="Export">Export</button>
			<button class = "send_emails">Send Award Emails</button>
			<br>
		</div>
	</div>
<br>
<br>
</form>
</body>
</html>
