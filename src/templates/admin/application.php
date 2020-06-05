<?php

$title = 'View application';
$layout = 'admin/_layout.php';
?>

<h1><?= e($application->title) ?></h1>

<p>Student:</p>
<blockquote>
    <?= e($application->name) ?>
    <<?= HTML::link('mailto:' . urlencode($application->email), e($application->email)) ?>>
</blockquote>

<p>Major:</p>
<blockquote><?= e($application->major) ?></blockquote>

<p>GPA:</p>
<blockquote><?= e($application->gpa) ?></blockquote>

<p>Expected Graduation Date:</p>
<blockquote><?= e($application->graduationDate) ?></blockquote>

<p>Advisor:</p>
<blockquote>
    <?= e($application->advisorName) ?>
    <<?= HTML::link('mailto:' . urlencode($application->advisorEmail), e($application->advisorEmail)) ?>>
</blockquote>

<p>Project description:</p>
<blockquote>
    <pre><?= e($application->description) ?></pre>
</blockquote>

<p>Project Timeline:</p>
<blockquote>
    <pre><?= e($application->timeline) ?></pre>
</blockquote>

<p>Budget Plan:</p>
<blockquote>
    <pre><?= e($application->justification) ?></pre>
</blockquote>

<p>Total budget amount:</p>
<blockquote>$<?= e($application->totalBudget) ?></blockquote>

<p>Requested budget amount:</p>
<blockquote>$<?= e($application->requestedBudget) ?></blockquote>

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
