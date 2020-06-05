<?php

$title = 'View application Status';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/student.css">
</head>
<body>
<main class="content">
<h1>Application: <?= e($application->title) ?><br> Current Status: <?= e($application->status) ?></h1>
<p>
    Student:
    <?= e($application->name) ?>
    <<?= HTML::link('mailto:' . urlencode($application->email), e($application->email)) ?>>
</p>

<p>Major: <?= e($application->major) ?></p>
<p>GPA: <?= e($application->gpa) ?></p>
<p>Expected Graduation Date: <?= e($application->graduationDate) ?></p>
<p>
    Advisor:
    <?= e($application->advisorName) ?>
    <<?= HTML::link('mailto:' . urlencode($application->advisorEmail), e($application->advisorEmail)) ?>>
</p>

<p>Project description:</p>
<pre><?= e($application->description) ?></pre>

<p>Project Timeline:</p>
<pre><?= e($application->timeline) ?></pre>

<p>Budget Plan:</p>
<pre><?= e($application->justification) ?></pre>

<p>Total budget amount: <?= e($application->totalBudget) ?></p>
<p>Requested budget amount: <?= e($application->requestedBudget) ?></p>

<p>Budget Table:</p>
<table id="budget-table">
    <tr>
        <th style="width: 30%">Item</th>
        <th style="width: 50%">Description</th>
        <th style="width: 15%">Cost</th>
    </tr>

    <?php
    foreach ($application->budgetTable() as $row) { ?>
        <tr>
            <td><?= e($row->item) ?></td>
            <td><?= e($row->itemDesc) ?></td>
            <td>$<?= e($row->itemCost) ?></td>
        </tr>
        <?php
    } ?>
</table>
</main>
</body>
</html>
