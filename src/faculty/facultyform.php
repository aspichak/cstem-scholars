<!DOCTYPE html>
<html>
<head>
    <title>Faculty Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="facultyform.css">
	<?php
	$database = parse_ini_file("../config.ini");
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
    $id = intval($_GET['id']);
    /*
    * CHANGE SO THAT THE APPLICAITON TABLE NAME MATCHES
    */
    $sth = $pdo->prepare("SELECT Deadline FROM Settings");
    $sth->execute();
    $date_array = $sth->fetch();
    $deadline = $date_array[0];
    $temp = explode("-", $deadline);
    $year = $temp[0];
    $month = $temp[1];
    $tableName = 'Applications'.$month.$year;
	$sth = $pdo->prepare("SELECT * FROM `$tableName` WHERE ApplicationNum=$id");
	$sth->execute();
	$application = $sth->fetch();
	$sid = $application['SID'];
	$pTitle = $application['PTitle'];
	$pObjective = $application['Objective'];
	$timeline = $application['Timeline'];
	$budget = $application['Budget'];
	$requested = $application['RequestedBudget'];
	$sources = $application['FundingSources'];
	$results = $application['Anticipatedresults'];
	$justification = $application['Justification'];
    $filePathTemp = $application['BudgetFilePath'];
    $filePath = "../".$filePathTemp;

	$sth = $pdo->prepare("SELECT * FROM Student WHERE SID=$sid");
	$sth->execute();
	$student = $sth->fetch();
	$sName = $student[1];
	$sEmail = $student[2];
	$gpa = $student[3];
	$major = $student[4];
    $gradDate = $student[5];
    header("Content-type: application/pdf '");
    header("Content-Disposition: attachment; filename=$file");

	?>
</head>

<script>
function enable() {
    document.getElementById("submit").disabled = false;
}
function messageBoxGrant() {
    confirm("Press a button!");
}
</script>
<body>
    <div class="form">
		<form method = "POST">
		<input type = "hidden" value = "ApplicationAward" name = "emailType" id = "emailType"/>
		<button class="logout" type="submit" formaction="../index.php?logout=true">Logout</button>
		</form>
        <h1>Grant Fund Application<span>Faculty Approval</span></h1>
		<h4>Please review the student's application to confirm their project is relevant to the STEM program and that they meet the academic standard required to be considered for this grant.</h4>
        <form method="post" action="formsubmit.php?id=<?php echo $id?>">
        <a href='grantRequirements.php' target='_blank'>View Grant Requirements</a>    
        <div class="section"><br>Student's Name:</div>
            <div class="inner-section">
                <p><?php echo htmlSpecialChars($sName)?></p>
            </div>

            <div class="section"><br>Student's Email:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($sEmail)?></p>
            </div>

            <div class="section"><br>Project Title:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($pTitle); ?></p>
            </div>

            <div class="section"><br>Student's Major:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($major)?></p>
            </div>

            <div class="section"><br>Student's GPA:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($gpa)?></p>
            </div>

            <div class="section"><br>Student's Estimated Graduation Date:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($gradDate)?></p>
            </div>

            <div class="section"><br>Project Objective:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($pObjective); ?></p>
            </div>

            <div class="section"><br>Project Timeline:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($timeline); ?></p>
            </div>

            <div class="section"><br>Project Budget:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($budget); ?></p>
            </div>
			<div class="section"><br>Requested Budget:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($requested);?></p>
            </div>

            <a href='<?php echo $filePath ?>' download>Budget Spreedsheet</a>
			
			<div class="section"><br>Anticipated Results:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($results); ?></p>
            </div>
			<div class="section"><br>Project Justification:</div>

            <div class="inner-section">
                <p><?php echo htmlSpecialChars($justification); ?></p>
            </div>
			<h4><br>If the application requires changes, any comments will be sent to the student as feedback.</h4>
            <div class="comment-section">Comments:</div>

            <div class="inner-section">
                <textarea name="comments"></textarea>

            </div>
		<div class="section">Please select one:</div>
		<div class="inner-section">
		  <input type="radio" name="group" id="Approved" value="Approved" onclick="enable()">Approve Application<br>
		<input type="radio" name="group" id="Not Approved"value="Not Approved" onclick="enable()">Student does not meet grant requirements<br>
		<input type="radio" name="group" id="Update" value="Update"onclick="enable()">Student needs to make changes to the application<br>
	</div>
            <div class="button">
				 <input type="submit" id= "submit" name="submit" value="Submit" disabled />
				

	 </div>
        </form>

    </div>
</body>
</html>
