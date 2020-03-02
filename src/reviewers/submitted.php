<?php
//PDO statement to connect to database or call PDOutil class
include_once 'creds.php';
require_once '../includes/init.php';
authorize('reviewer');

$email = $_SESSION["email"];
var_dump($email);

$app_id = intval($_GET['id']);
$Q1 = $_POST['learn'];
$Q2 = $_POST['justified'];
$Q3 = $_POST['method'];
$Q4 = $_POST['time'];
$Q5 = $_POST['project'];
$Q6 = $_POST['budget'];
$QATotal = $Q1 + $Q2 + $Q3 + $Q4 + $Q5 + $Q6;
$fund = $_POST['fund'];
$comments = $_POST['qual_comments'];

$sth = $pdo->prepare("SELECT Deadline FROM Settings");
$sth->execute();
$date_array = $sth->fetch();
$deadline = $date_array["Deadline"];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$revTable = 'ReviewedApps' . $month . $year;
echo $app_id;

$stmt = $pdo->prepare(
    "UPDATE  `$revTable` SET QAComments='$comments',QA1=$Q1,QA2=$Q2,QA3=$Q3,QA4=$Q4,QA5=$Q5,QA6=$Q6,QATotal=$QATotal,FundRecommend='$fund',submitted='1' WHERE ApplicationNum=$app_id AND REmail='$email'"
);
$stmt->execute();
$user = $stmt->fetch();

header("location:ReviewStudents.php");

?>

