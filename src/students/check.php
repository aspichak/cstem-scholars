<?php
//PDO statement to connect to database or call PDOutil class
include_once 'creds.php';

//get most recent table for ApplicationsTest
$update = false;
$sth = $pdo->prepare("SELECT Deadline FROM Settings");
$sth->execute();
$date_array = $sth->fetch();
$deadline = $date_array['Deadline'];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$tableName = 'Applications'.$month.$year;

$targetdir = 'uploads/';

$userName = $_SESSION['user'];
$sid = $_SESSION['id'];
//$sid = 100;


//if sid already in database populate fields
$stmt = $pdo->prepare("SELECT * FROM Student WHERE SID =?");
$stmt->execute([$sid]);
$iStudent = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT * FROM `$tableName` WHERE SID =?");
$stmt2->execute([$sid]);
$studentApp = $stmt2->fetch();

if ($iStudent != NULL) {
  
  $name = $iStudent['SName'];
  $email = $iStudent['SEmail'];
  $gpa =$iStudent['GPA'];
  $department = $iStudent['Department'];
  $major = $iStudent['Major'];
  $gradDate =$iStudent['GraduationDate'];
  $_SESSION['hasStudent'] = true;

}
if($studentApp != NULL)
{
  $pTitle = $studentApp['PTitle'];
  $objective = $studentApp['Objective'];
  $timeline = $studentApp['Timeline'];
  $budget = $studentApp['Budget'];
  $requestedBudget = $studentApp['RequestedBudget'];
  $fundingSources = $studentApp['FundingSources'];
  $anticipatedResults = $studentApp['Anticipatedresults'];
  $justification = $studentApp['Justification'];
  $aemail = $studentApp['AEmail'];
  //$file = $studentApp['GraduationDate'];

  $query = $pdo->prepare("SELECT * FROM Advisor WHERE AEmail =?");
  $query->execute([$aemail]);
  $sAdvisor=$query->fetch();

  $advisor = $sAdvisor['AName'];
  $_SESSION['update'] = true;
}
?> 