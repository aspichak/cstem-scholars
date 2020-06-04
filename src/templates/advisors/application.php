<?php

$title = 'View application';
$layout = 'admin/_layout.php';
?>

<h1><?= e($application->title) ?></h1>
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

<label>Comments to be appended to emails. These are all optional.</label><br>
<form method="POST">
    <?= $form->csrf() ?>
    <label for=reviewerComment">Reviewer Comment (doesn't apply to reject)</label><br>
    <textarea id="reviewerComment" name="reviewerComment" style="width: 100%" rows="4" type="text"></textarea><br>
    <label for="studentComment">Student Comment</label><br>
    <textarea id="studentComment" name="studentComment" style="width: 100%" rows="4" type="text"></textarea><br>
    <button name="buttonName" type="submit" value="accept">Accept</button>
    <button name="buttonName" type="submit" value="reject">Reject</button>
</form>
