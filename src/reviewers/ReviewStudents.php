<?php
require_once '../includes/init.php';

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

authorize('reviewer');
?>
<!DOCTYPE HTML>
<html>
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
#include_once 'creds.php';

//get most recent table for ApplicationsTest
$deadline = DB::selectSingle( "Settings")['Deadline'];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$appTable = 'applications' . $month . $year; #Applications022020
$revTable = 'reviewedapps' . $month . $year; #ReviewedApps022020
$email = $_SESSION["email"]; #name@ewu.edu, name is whichever we selected at log in
//get applications assigned to this RID
#$stmt = $pdo->query("SELECT * FROM `$revTable` WHERE REmail='$email'");
#$stmt->execute();
$rows = DB::query2( 'SELECT * FROM ',$revTable,' WHERE REmail = ?', $email );
#var_dump($row);
/* 		if($_SESSION['id'] == NULL)
		{
			
			$stmt->execute([1001]);
		}
		else{
		  $stmt->execute([$_SESSION['id']]);
		} */
//get applications assigned to reviewer
#$stmt2 = DB::query2( $query, $appTable,' WHERE ApplicationNum=?',#$pdo->prepare("SELECT * FROM `$appTable` WHERE ApplicationNum=?");

$ctr = 0;
?>


<div class="form">
    <h1>Students for Review<span>Grant applications</span></h1>
    <div class = "button-section">
        <?php
        $i = 0;
        #var_dump($rows);
        #echo(count($rows));
        while ($i < count($rows)  ) {
            $row = $rows[$i];
            echo '<form role="form" method="post">';
            #$stmt2->execute([$row['ApplicationNum']]);
            $appNum = $row['ApplicationNum'];
            #NOTE: Changing WHERE ApplicationNum to SID
            $row2 = DB::query2( 'SELECT * FROM ', $appTable, ' WHERE SID = ?', $appNum );
            #var_dump($row2);
            $student = $row2[0];
            $ctr++;
            //only display applications that have not been reviewed
            if ($row['Submitted'] != 1) {
                $name = 'btn[' . $row['ApplicationNum'] . ']';
                $appNum = $row['ApplicationNum'];
                #$fileTemp = $student['BudgetFilePath'];
                #$filePath = "../" . $fileTemp;
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
                <!--<p>
                    <a href='<?php echo $filePath ?>' download>Budget Spreedsheet</a>
                </p>-->
                <?php
                echo '<label>Other funding sources available: <input type="text" placeholder="' . $student['FundingSources'] . '"/></label>';
                echo '</div>';
                echo '<div class="section" for="my_checkbox"><span>' . $ctr . '</span>' . $student['PTitle'] . '</div>';
                //TODO: figure out transfering applicationNum to formpage.php
                echo '<label for="' . $row['ApplicationNum'] . '">Show/Hide Details</label>';
                #echo  '<a href="http://localhost:8080/formpage.php"><button type="button" name="'.$name.'"> Review Application: '.$row['ApplicationNum'].'</button></a>';
                echo '<input type="hidden" value=' . $row['ApplicationNum'] . ' name="appNum" id="appNum"/>';
                echo '<button type="submit" name="' . $name . '" formaction=\'../reviewers/formPage1.php\'"> Review Application: ' . $row['ApplicationNum'] . '</button>';
                #. '" formaction="formPage1.php?id="' .
                echo '</div>';
            }
            $i++;
            echo '</form>';
        }
        ?>
        </span>
    </div>
    </div>
</div>
</body>
</html>
