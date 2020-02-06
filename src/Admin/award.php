<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    font-family: "Lato", sans-serif;
}
legend {
    background-color: #000;
    color: #fff;
    padding: 3px 6px;
}

.output {
    font: 1rem 'Fira Sans', sans-serif;
}

input {
    margin: .4rem;
}

label {
    display: inline-block;
    text-align: right;
    width: 20%;
}

.sidenav {
    height: 100%;
    width: 200px;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: lightgray;
    overflow-x: hidden;
    padding-top: 20px;
}

.sidenav a {
    padding: 6px 6px 6px 32px;
    text-decoration: none;
    font-size: 25px;
    color: black;
    display: block;
}

.sidenav a:hover {
    color: #f1f1f1;
}

.main {
    margin-left: 200px; /* Same as the width of the sidenav */
}
select.search{
	margin-right:15px;
	}
	input{
		width :300px;}
	
@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
.stats-container {
    float: left;
    width: 33.33%;
    padding: 5px;
}

.clearfix::after {
    content: "";
    clear: both;
    display: table;
}
.w3-text-white, .w3-hover-text-white:hover {
    color: #fff!important;
    }
.w3-row-padding, .w3-row-padding>.w3-half, .w3-row-padding>.w3-third, .w3-row-padding>.w3-twothird, .w3-row-padding>.w3-threequarter, .w3-row-padding>.w3-quarter, .w3-row-padding>.w3-col {
    padding: 0 8px;}
    
    .w3-left {
    float: left!important;
}
.w3-right {
    float: right!important;
}
.w3-orange, .w3-hover-orange:hover {
    color: #000!important;
    background-color: #ff9800!important;
}
.w3-container, .w3-panel {
    padding: 0.01em 16px;
}
.w3-margin-bottom {
    margin-bottom: 16px!important;
}
</style></style>
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

$app = $_GET['id'];
$sth = $pdo->prepare("SELECT Deadline FROM Settings");
$sth->execute();
$date_array = $sth->fetch();
$deadline = $date_array['Deadline'];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$tableName = 'Applications'.$month.$year;
$sql = $pdo -> prepare("SELECT SUM(AmountGranted) FROM ".$tableName." ;");
$sql ->execute();
$result = $sql->fetch();
$totalGranted = $result[0];
$sql = $pdo -> prepare("SELECT Budget FROM Settings");
$sql ->execute();
$result = $sql->fetch();
$remaining = $result[0] - $totalGranted;
$sth = $pdo -> prepare("Select AmountGranted from ". $tableName." WHERE ApplicationNum =".$app);
$sth->execute();
$result = $sth->fetch();
$currentAward = $result[0];


?>
</head>
<form action="awardSuccessful.php" method="post">
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
<h1>Award</h1>
<h4> To award this student, select an amount and click confirm.</h4>
<fieldset>
    <legend>Award</legend>

    <div>
        <label for="amountRemaining">Remaining Amount</label>
        <input type = "number" id = "amountRemaining" name = "amountRemaining" disabled
		value= <?php echo $remaining; ?>
		/>
                
    </div>

    <div>
        <label for="amount">Award Amount</label>
        <input type="number" id="amount" name="amount"
               value= <?php echo $currentAward ?>
               />
        <input type = "hidden" id= "app" name ="app" 
            value =<?php echo $app ?>
            />
    </div>
</fieldset>
<br><br>
<button type = "submit">Confirm</button>
<button type = "submit" formaction = "results.php">Cancel</button>

<br>