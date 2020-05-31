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
<p>Coming soon!</p>
