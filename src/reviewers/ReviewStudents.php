<?php
require_once '../includes/init.php';

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

authorize('reviewer');
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

//get most recent table for ApplicationsTest
$deadline = DB::selectSingle( "Settings")['Deadline'];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$appTable = 'applications' . $month . $year; #Applications022020
$revTable = 'reviewedapps' . $month . $year; #ReviewedApps022020
$email = $_SESSION["email"];

$rows = DB::select($revTable, 'REmail = ?', $email);
#will be used later to label # of application
$ctr = 0;

?>

<div class="form">
    <h1>Students for Review<span>Grant applications</span></h1>
    <div class = "button-section">
        <?php
        $i = 0;
        while ($i < count($rows)  ) {
            $row = $rows[$i];
            echo '<form role="form" method="post">';
            $appNum = $row['ApplicationNum'];
            $student = DB::selectSingle($appTable, 'SID = ?', $appNum);
            $ctr++;
            //only display applications that have not been reviewed
            if ($row['Submitted'] != 1) {
                $name = 'btn[' . $row['ApplicationNum'] . ']';
                $appNum = $row['ApplicationNum'];
                #TODO: FIX BUDGETFILEPATH STUFF
                #$fileTemp = $student['BudgetFilePath'];
                #$filePath = "../" . $fileTemp;
                echo '<div class="inner-wrap">';
                echo '<input type="checkbox" id="' . $row['ApplicationNum'] . '" style="display:none;">';
                echo '<div id="hidden">';
                echo '<label>Title <textarea placeholder="' . $student['PTitle'] . '" style="resize: none" ></textarea></label>';
                echo '<label>Objective: <textarea placeholder="' . $student['Objective'] . '" style="resize: none" ></textarea></label>';
                echo '<label>Anticipated Results: <textarea placeholder="' . $student['Anticipatedresults'] . '" style="resize: none"></textarea></label>';
                echo '<label>Estimated timeline: <textarea placeholder="' . $student['Timeline'] . '" style="resize: none"></textarea></label>';
                echo '<label>Budget and planned spending: <textarea placeholder="' . $student['Justification'] . '"style="resize: none"></textarea></label>';
                echo '<label>Total budget amount:<input type="text" placeholder="' . $student['Budget'] . '"/></label>';
                echo '<label>Requested budget amount from EWU:<input type="text" placeholder="' . $student['RequestedBudget'] . '"/></label>';
                ?>
                <!--<p>
                    <a href='<?php echo $filePath ?>' download>Budget Spreedsheet</a>
                </p> -->
                <?php
                echo '<label>Other funding sources available: <input type="text" placeholder="' . $student['FundingSources'] . '"/></label>';
                echo '</div>';
                echo '<div class="section" for="my_checkbox"><span>' . $ctr . '</span>' . $student['PTitle'] . '</div>';
                echo '<label for="' . $row['ApplicationNum'] . '">Show/Hide Details</label>';
                echo '<input type="hidden" value=' . $row['ApplicationNum'] . ' name="appNum" id="appNum"/>';
                echo '<button type="submit" name="' . $name . '" formaction=\'../reviewers/formPage1.php\'"> Review Application: ' . $row['ApplicationNum'] . '</button>';
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
