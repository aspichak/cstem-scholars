<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>

    <title>Faculty Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<style>
        body {
            background: #8B0000;
        }
        .form {
            max-width: 1000px;
            padding: 30px;
            margin: 40px auto;
            background: #FFF;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
            -moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
            -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
        }
            .form .inner-section {
                padding:10px;
                background: #F8F8F8;
                border-radius: 6px; 
                margin-bottom: 5px;
            }
            .form h1 {
                background: #808080;
                padding: 20px 30px 15px 30px;
                margin: -30px -30px 30px -30px;
                border-radius: 10px 10px 0 0;
                -webkit-border-radius: 10px 10px 0 0;
                -moz-border-radius: 10px 10px 0 0;
                color: #fff;
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
                font: normal 30px 'Bitter', serif;
                -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                border: 1px solid #000000;
            }
            .form h1 > span {
                display: block;
                margin-top: 2px;
                font: 13px Arial, Helvetica, sans-serif;
            }

			.form h2 {
				color : #c70505;
				
			}

</style>
 <?php
		$database = parse_ini_file("config.ini");
		$host = $database['host'];
		$db = $database['db'];
		$user = $database['user'];
		$pass = $database['pass'];
        $charset = 'utf8mb4';
        $dsn  = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try{
                 $pdo = new PDO($dsn, $user, $pass, $options);
        }catch(\PDOException $e)
        {
                 throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
		$email = $_SESSION["email"];
		$sth = $pdo->prepare("SELECT ApplicationNum, AdvisorApproved  FROM Advisor WHERE AEmail='ssteiner@ewu.edu'");
		$sth->execute();
		$applications = $sth->fetchAll();
		$query = $pdo->prepare("SELECT Deadline FROM Settings");
		$query->execute();
		$deadline = $query->fetch();
?>

</head>
	<body>


    <div class="form">
        <h1>Grant Fund Application<span>Faculty Approval</span> </h1>
		<button onclick="window.location.href='logout.php'">Logout</button>
		<form>
		<h2>All applications must be approved by <?php echo $deadline[0];?></h2>
		<h3>Applications Pending Aproval</h3>
		 
		<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
		<tr>
			<th>Student Name</th>
			<th>Project Title</th>
			<th>Student Email</th>
			<th>Application Status</th>
			<th>View Application</th>
		</tr>
		<?php foreach($applications as $value){
			$num = $value["AdvisorApproved"];
			if($num == 1)
			{
				$status = "Approved";
			}
			elseif($num == 0)
			{
				$status = "Needs Approval";
			}
			elseif($num == 2)
			{
				$status = "Updates Requested";
			}
			elseif($num == 3)
			{
				$status = "Not Approved";
			}
			elseif($num == 4)
			{
				$status = "Updated";
			}
			$newValue = $value["ApplicationNum"];
			$temp = $pdo->prepare("SELECT * FROM ApplicationsTest WHERE ApplicationNum=$newValue");
			$temp->execute();
			$student = $temp->fetch();
			$sid = $student[1];
			$query = $pdo->prepare("SELECT * FROM Student WHERE SID=$sid");
			$query->execute();
			$results = $query->fetch();
		?>
		<tr>
    		<td><?php echo $results[1];?></td>
			<td><?php echo $student[2];?></td>
			<td><?php echo $results[2];?></td>
			<td><font color= #c70505><?php echo $status ?></font></td>
			<?php if($num == 1 || $num == 2 || $num == 3) { ?>
			<td><button type="button" disabled>Submitted</button></td>
			<?php } 
			else {?>
			<td><a href=facultyform.php?id=<?php echo $newValue?>><button type="button">View</button></td>
			<?php } ?>
		</tr>
		<?php  }  ?>
		</table><br>
		</form>
    </div>
</body>
</html>
