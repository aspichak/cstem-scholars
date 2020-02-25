<?php

require_once '../includes/init.php';
authorize('reviewer');
session_start();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Student Reviews</title>
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="css/ReviewStudents.css">
</head>

<body>
<form role="form" method="post" align="right">
    <div class=logout>
        <div class="button-section">
            <button type="submit" class="button" name="logout" formaction="../index.php?logout=true">Logout</button>
        </div>
    </div>
</form>
<?php
include_once 'creds.php';

//get most recent table for ApplicationsTest
$sth = $pdo->prepare("SELECT Deadline FROM Settings");
$sth->execute();
$date_array = $sth->fetch();
$deadline = $date_array["Deadline"];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$appTable = 'Applications' . $month . $year;
$revTable = 'ReviewedApps' . $month . $year;

$email = $_SESSION["email"];
//get applications assigned to this RID
try {
    $stmt = $pdo->query("SELECT * FROM `$revTable` WHERE REmail='$email'");
} catch (exception $e) {
    //redirect("error.php");
    error('Database error in reviewers');
}
$stmt->execute();
/* 		if($_SESSION['id'] == NULL)
		{
			
			$stmt->execute([1001]);
		}
		else{
		  $stmt->execute([$_SESSION['id']]);
		} */
//get applications assigned to reviewer
// "SELECT * FROM `".$table."` WHERE ApplicationNum=?"
$stmt2 = $pdo->prepare("SELECT * FROM `$appTable` WHERE ApplicationNum=?");

$ctr = 0;

?>


<div class="form">
    <h1>Students for Review<span>Grant applications</span></h1>
    <div class="button-section">
        <?php
        while ($row = $stmt->fetch()) {
            echo '<form role="form" method="post">';
            $stmt2->execute([$row['ApplicationNum']]);
            $student = $stmt2->fetch();

            $ctr++;
            //only display applications that have not been reviewed
            if ($row['Submitted'] != 1) {
                $name = 'btn[' . $row['ApplicationNum'] . ']';
                $appNum = $row['ApplicationNum'];
                $fileTemp = $student['BudgetFilePath'];
                $filePath = "../" . $fileTemp;
                echo '<div class="inner-wrap">';
                echo '<input type="checkbox" id="' . $row['ApplicationNum'] . '" style="display:none;">';
                echo '<div id="hidden">';
                echo '<label>Title <textarea placeholder="' . $student['PTitle'] . '" ></textarea></label>';
                echo '<label>Objective: <textarea placeholder="' . $student['Objective'] . '" ></textarea></label>';
                echo '<label>Anticipated Results: <textarea placeholder="' . $student['Anticipatedresults'] . '" ></textarea></label>';
                echo '<label>Estimated timeline: <textarea placeholder="' . $student['Timeline'] . '" ></textarea></label>';
                echo '<label>Budget and planned spending: <textarea placeholder="' . $student['Justification'] . '"></textarea></label>';
                echo '<label>Total budget amount:<input type="text" placeholder="' . $student['Budget'] . '"/></label>';
                echo '<label>Requested budget amount from EWU:<input type="text" placeholder="' . $student['RequestedBudget'] . '" /></label>'; ?>
                <p><a href='<?php echo $filePath ?>' download>Budget Spreedsheet</a></p><?php
                echo '<label>Other funding sources available: <input type="text" placeholder="' . $student['FundingSources'] . '"/></label>';
                echo '</div>';
                echo '<div class="section" for="my_checkbox"><span>' . $ctr . '</span>' . $student['PTitle'] . '</div>';
                //TODO: figure out transfering applicationNum to formpage.php
                echo '<label for="' . $row['ApplicationNum'] . '">Show/Hide Details</label>';
                //echo  '<a href="http://localhost:8080/formpage.php"><button type="button" name="'.$name.'"> Review Application: '.$row['ApplicationNum'].'</button></a>';
                echo '<input type="hidden" value=' . $row['ApplicationNum'] . ' name="appNum" id="appNum"/>';
                echo '<button type="submit" name="' . $name . '" formaction="formPage1.php?id="' . $appNum . '"> Review Application: ' . $row['ApplicationNum'] . '</button>';
                echo '</div>';
            }
            echo '</form>';
        }
        ?>
        </span>
    </div>
</div>
</body>
</html>
