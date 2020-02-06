<?php session_start();

include_once 'creds.php';

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

$name = htmlentities($_POST['name'], ENT_QUOTES);
  $gpa = htmlentities($_POST['gpa'], ENT_QUOTES);
  $major = htmlentities($_POST['major'], ENT_QUOTES);
  $gradDate =htmlentities($_POST['egd'], ENT_QUOTES);
  $email = htmlentities($_POST['email'], ENT_QUOTES);
  $department = htmlentities($_POST['department'], ENT_QUOTES);

  $advisor = htmlentities($_POST['advisor'], ENT_QUOTES);
  $aemail = htmlentities($_POST['advisor_email'], ENT_QUOTES);

  //$appNum = get unique identifier generatator uuid
  $pTitle = htmlentities($_POST['project'], ENT_QUOTES);
  $objective = htmlentities($_POST['objective'], ENT_QUOTES);
  $timeline = htmlentities($_POST['timeline'], ENT_QUOTES);
  $budget = htmlentities($_POST['budget'], ENT_QUOTES);
  $budget = str_replace(",","", $budget);
  $requestedBudget = htmlentities($_POST['request'], ENT_QUOTES);
  $requestedBudget = str_replace(",","",$requestedBudget);
  $fundingSources = htmlentities($_POST['sources'], ENT_QUOTES);
  $anticipatedResults = htmlentities($_POST['results'], ENT_QUOTES);
  $justification = htmlentities($_POST['justification'], ENT_QUOTES);
  $fileName = $_FILES['file']['name'];

$fileExistsFlag = 0;
$update = $_SESSION['update'];

if($update)
{
  $query = $pdo->prepare("SELECT BudgetFilePath FROM `$tableName` WHERE SID=$sid;");
  $query->execute();
  $filePath = $query->fetch();
  if($fileName != $filePath["BudgetFilePath"])
  {
    $filePath = $filePath["BudgetFilePath"];
    $filePath = "../".$filePath;
    if(file_exists($filePath))
    { 
      unlink($filePath);
    }
    $fileTarget = $targetdir.$userName.$fileName;
    $tempFileName= $_FILES["file"]["tmp_name"];
    $result = move_uploaded_file($tempFileName, $fileTarget);
    $filePath = "students/".$fileTarget;
  }
  //get old file, delete and replace it with new file

  $query = $pdo->prepare("UPDATE `$tableName` SET PTitle='$pTitle', Objective='$objective', Timeline='$timeline', Budget=$budget, RequestedBudget=$requestedBudget, FundingSources='$fundingSources',AnticipatedResults='$anticipatedResults', Justification='$justification', Submitted='0', AdvisorApproved='0' WHERE SID=$sid");
  $query->execute();

  $stmt = $pdo->prepare("UPDATE Student SET SName= '$name', SEmail='$email', GPA=$gpa, Department='$department', Major='$major', GraduationDate='$gradDate' WHERE SID = $sid");
  $stmt->execute();
  $user = $stmt->fetch();
  $end = true;

}
else{
  $fileTarget = $targetdir.$userName.$fileName;
  $tempFileName= $_FILES["file"]["tmp_name"];
  $result = move_uploaded_file($tempFileName, $fileTarget);
  

  $filePath = "students/".$fileTarget;
  
  $query = $pdo->prepare("INSERT INTO `$tableName` (SID,PTitle,Objective,Timeline,Budget,RequestedBudget,FundingSources,Anticipatedresults,Justification,BudgetFilePath,Submitted,Awarded,AmountGranted,AdvisorApproved,AdvisorComments,AEmail)VALUES ($sid,'$pTitle','$objective','$timeline',$budget,$requestedBudget,'$fundingSources','$anticipatedResults','$justification','$filePath','0','0','0','0',' ','$aemail')");
  $query->execute();
  $query->fetch();

  
  $stmt = $pdo->prepare("INSERT INTO Student VALUES (?,?,?,?,?,?,?)");
  $stmt->execute([$sid,$name,$email,$gpa,$department,$major,$gradDate]);
  $user = $stmt->fetch();

  //store application -- appNum, sid, pTitle, objective, timeline, budget, requestedBudget, sources, results, justification
}
  //store advisor
  
  $a = $pdo->prepare("SELECT AEmail FROM Advisor WHERE AEmail=?");
  $a->execute([$aemail]);
  $advisorCheck = $a->fetch();

  if ($advisorCheck == NULL) {
    $stmt = $pdo->prepare('INSERT INTO Advisor VALUES (?,?)');
    $stmt->execute([$aemail,$advisor]);
    $user = $stmt->fetch();
  }
  $end = true;

  if($end == true)
  {
    header("location:savedPage.php");
  }

 ?>