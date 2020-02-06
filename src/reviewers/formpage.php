<?php session_start();
//PDO statement to connect to database or call PDOutil class
include_once 'creds.php';

function renderForm($app_id = '', $reviewer = '', $Q1 = '', $Q2 = '', $Q3 = '',
$Q4 = '', $Q5 = '', $Q6 = '', $fund = '',$error = ''){
  //get application number from ReviewStudents (set button values to application number)
	$app_id = intval($_GET['id']);
  if ($error != '') {
      echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error
      . "</div>";
  }
  include 'formpage.html';

}

$submitted = (isset($_POST['submitButton'])) ? true : false;
//echo $app_id;
if($submitted)
{

    //get reviewer id from sso?
  $reviewer = $_SESSION['id'];


    $app_id = htmlentities($_POST['app'], ENT_QUOTES);
    $Q1 = htmlentities($_POST['learn'], ENT_QUOTES);
    $Q2 = htmlentities($_POST['justified'], ENT_QUOTES);
    $Q3 = htmlentities($_POST['method'], ENT_QUOTES);
    $Q4 = htmlentities($_POST['time'], ENT_QUOTES);
    $Q5 = htmlentities($_POST['project'], ENT_QUOTES);
    $Q6 = htmlentities($_POST['budget'], ENT_QUOTES);
	$QATotal = $Q1 + $Q2 + $Q3 + $Q4 + $Q5 + $Q6;
    $fund = htmlentities($_POST['fund'], ENT_QUOTES);
    $comments = htmlentities($_POST['qual_comments'], ENT_QUOTES);


    //check if any fields are empty, if so renderform again
    if($Q1 == '' || $Q2 == '' || $Q3 == '' ||
    $Q4 == '' || $Q5 == '' || $Q6 == '' || $fund == ''){
      $error = 'ERROR: Please fill in all required fields!';
      renderForm($app_id, $reviewer, $Q1, $Q2, $Q3, $Q4, $Q5, $Q6, $fund, $error);
    }
    //store application id, reviewer id, comments, questions 1-6 and fund recommendation

    $stmt = $pdo->prepare('UPDATE ReviewedApps SET QAComments=?,QA1=?,QA2=?,QA3=?,QA4=?,QA5=?,QA6=?,QATotal=?,FundRecommend=?,submitted=? WHERE ApplicationNum=? AND RID=?');
    $stmt->execute([$comments, $Q1, $Q2, $Q3, $Q4, $Q5, $Q6, $QATotal, $fund, 1, $app_id, 1001]);
    $user = $stmt->fetch();
    include 'ReviewStudents.php';
  

}
// if the form hasn't been submitted yet, show the form
else {
  renderForm();
}

?>

