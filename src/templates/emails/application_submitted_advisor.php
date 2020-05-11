<?php

$layout = 'emails/layout.php';
?>

<p>Hello <?= $application->advisorName ?>, </p>
<p>
    A CSTEM Scholars application is available for your review. Go to
    <?= HTML::link(BASE_URL . '/reviewers/ReviewStudents.php', BASE_URL . '/reviewers/ReviewStudents.php') ?>
    to review it. Here are the details:
</p>

<div class="label">Student Name:</div>
<p><?= $application->name ?> &lt;<a href="mailto:<?= $application->email ?>"><?= $application->email ?></a>&gt;</p>

<div class="label">Project Title:</div>
<p><?= $application->title ?></p>

<div class="label">Major:</div>
<p><?= $application->major ?></p>

<div class="label">GPA:</div>
<p><?= $application->gpa ?></p>

<div class="label">Expected Graduation Date:</div>
<p><?= $application->graduationDate ?></p>

<div class="label">Description:</div>
<pre><?= $application->description ?></pre>

<div class="label">Timeline:</div>
<pre><?= $application->timeline ?></pre>

<div class="label">Budget Description:</div>
<pre><?= $application->justification ?></pre>

<div class="label">Requested Budget:</div>
<p>$<?= $application->requestedBudget ?></p>
