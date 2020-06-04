<?php

$layout = 'emails/layout.php';
?>

<p>Hello <?= e($application->advisorName) ?>, </p>
<p>
    A CSTEM Scholars application is available for your review. Go to
    <?= HTML::link(BASE_URL . '/advisors/', BASE_URL . '/advisors/') ?>
    to review it. Here are the details:
</p>

<div class="label">Student Name:</div>
<p><?= e($application->name) ?> &lt;<a href="mailto:<?= e($application->email) ?>"><?= e($application->email) ?></a>&gt;
</p>

<div class="label">Project Title:</div>
<p><?= e($application->title) ?></p>

<div class="label">Major:</div>
<p><?= e($application->major) ?></p>

<div class="label">GPA:</div>
<p><?= e($application->gpa) ?></p>

<div class="label">Expected Graduation Date:</div>
<p><?= e($application->graduationDate) ?></p>

<div class="label">Description:</div>
<pre><?= e($application->description) ?></pre>

<div class="label">Timeline:</div>
<pre><?= e($application->timeline) ?></pre>

<div class="label">Budget Description:</div>
<pre><?= e($application->justification) ?></pre>

<div class="label">Requested Budget:</div>
<p>$<?= e($application->requestedBudget) ?></p>
