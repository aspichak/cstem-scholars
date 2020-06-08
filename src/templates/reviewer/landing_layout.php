<?php

require_once '../../init.php';

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

User::authorize('reviewer');
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


#$rows = DB::select($revTable, 'REmail = ?', $email);
#will be used later to label # of application
$ctr = 0;
?>

<div class="form">
    <h1>Students for Review<span>Grant applications</span></h1>
    <div class="button-section">
        <?php
        $i = 0;
        while ($i < count($rows)) {
            $row = $rows[$i];
            echo '<form role="form" method="post">';
            $appNum = $row->studentID;

            $ctr++;

            //only display applications that have not been reviewed
            // WILL NEED TO REIMPLEMENT THIS ONCE THE ACTUAL REVIEW PAGE IS FINISHED
            #if ($row->status != 'submitted') {
                $name = 'btn[' . $row->id . ']';
                $appNum = $row->id;

                echo '<div class="inner-wrap">';
                echo '<div class="section" for="my_checkbox"><span>' . $ctr . '</span>' . $row->title . '</div>';
                echo '<label for="' . $appNum . '" class="details">Show/Hide Details</label>';
                echo '<input type="checkbox" id="' . $appNum . '" style="display:none;">';
                echo '<div id="hidden">';
                echo '<label>Title <textarea placeholder="' . $row->title . '" style="resize: none" ></textarea></label>';
                echo '<label>Objective & Results: <textarea placeholder="' . $row->description. '" style="resize: none" ></textarea></label>';
                echo '<label>Estimated timeline: <textarea placeholder="' . $row->timeline . '" style="resize: none"></textarea></label>';
                echo '<label>Budget and planned spending: <textarea placeholder="' . $row->justification . '"style="resize: none"></textarea></label>';
                echo '<label>Total budget amount:<input type="text" placeholder="' . $row->totalBudget. '"/></label>';
                echo '<label>Requested budget amount from EWU:<input type="text" placeholder="' . $row->requestedBudget . '"/></label>';
                ?>
                <!--   TODO: NEED TO REPLACE WITH TABLE TO LOAD JQUERY INTO
                <p>
                    <a href="<?= url('download.php?file=' . $student['BudgetFilePath']) ?>">Budget Spreadsheet</a>
                </p>
                -->
                <?php
                echo '<label>Other funding sources available: <input type="text" placeholder="' . $row->fundingSources . '"/></label>';
                echo '</div>';
                echo '<input type="hidden" value=' . $appNum . ' name="appNum" id="appNum"/>';
                echo '<button type="submit" name="' . $name . '" formaction=\'../reviewers/formPage1.php\'"> Review Application: ' . $appNum . '</button>';
                echo '</div>';
            #}
            $i++;
            echo '</form>';
        }
        ?>
    </div>
</div>
</body>
</html>