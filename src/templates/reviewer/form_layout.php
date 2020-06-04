<?php

$title = 'Review Application';
$layout = 'admin/_layout.php';

$appNum = $review->id - 1;
$application = $application_list[$appNum];
?>

<h1><?= e($application->title) ?></h1>
<p>
    Student:
    <?= e($application->name) ?>
    <!--<?= HTML::link('mailto:' . urlencode($application->email), e($application->email)) ?>-->
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

<br>
<h2> REVIEW FORM </h2>

<form method="POST" enctype="multipart/form-data">
    <?= $form->csrf() ?>
    <?= 'merp' ?>
    <div class="form">
        <label>Does the project demonstrate experiential learning in a CSTEM discipline?</label>
        <br>
        <?= $form->radio('q1', 1, [] ) ?>
        <!--<p>
            <label class="radio-inline"><input type="radio" name="learn" value="0">0</label>
            <label class="radio-inline"><input type="radio" name="learn" value="1">1</label>
            <label class="radio-inline"><input type="radio" name="learn" value="2">2</label>
            <label class="radio-inline"><input type="radio" name="learn" value="3">3</label>
        </p>-->
        <label>Is the budget justified in the project description, including realistic?</label>
        <p>
            <label class="radio-inline"><input type="radio" name="justified" value="0">0</label>
            <label class="radio-inline"><input type="radio" name="justified" value="1">1</label>
            <label class="radio-inline"><input type="radio" name="justified" value="2">2</label>
            <label class="radio-inline"><input type="radio" name="justified" value="3">3</label>
        </p>
        <label>Are the proposed methods appropriate to achieve the goals?</label>
        <p>
            <label class="radio-inline"><input type="radio" name="method" value="0">0</label>
            <label class="radio-inline"><input type="radio" name="method" value="1">1</label>
            <label class="radio-inline"><input type="radio" name="method" value="2">2</label>
            <label class="radio-inline"><input type="radio" name="method" value="3">3</label>
        </p>
        <label>Is the timeline proposed reasonable?(Too little? Too much?)</label>
        <p>
            <label class="radio-inline"><input type="radio" name="time" value="0">0</label>
            <label class="radio-inline"><input type="radio" name="time" value="1">1</label>
            <label class="radio-inline"><input type="radio" name="time" value="2">2</label>
            <label class="radio-inline"><input type="radio" name="time" value="3">3</label>
        </p>
        <label>Is the project well explained (including rationale) and justified?</label>
        <p>
            <label class="radio-inline"><input type="radio" name="project" value="0">0</label>
            <label class="radio-inline"><input type="radio" name="project" value="1">1</label>
            <label class="radio-inline"><input type="radio" name="project" value="2">2</label>
            <label class="radio-inline"><input type="radio" name="project" value="3">3</label>
        </p>
        <label>Does the budget only include eligible activities (supplies, equipment, field travel,
            conference travel)?</label>
        <p>
            <label class="radio-inline"><input type="radio" name="budget" value="0">0</label>
            <label class="radio-inline"><input type="radio" name="budget" value="1">1</label>
            <label class="radio-inline"><input type="radio" name="budget" value="2">2</label>
            <label class="radio-inline"><input type="radio" name="budget" value="3">3</label>
        </p>
        <label>Based on eligibility and quality scores, RECOMMEND one of the following
            categories</label>
        <p>
            <label class="radio-inline"><input type="radio" name="fund" value="0">Do Not
                Fund</label>
            <label class="radio-inline"><input type="radio" name="fund" value="1">Fund if
                Possible</label>
            <label class="radio-inline"><input type="radio" name="fund" value="2">Fund</label>
        </p>
        <div class="row">
            <div class="col-sm-12 form-group">
                <label for="comments"> Quality Assessment Comments:</label>
                <br>
                <textarea class="form-control" type="textarea" name="qual_comments"
                          placeholder="Your Comments" maxlength="6000" rows="7" style="resize: none" ></textarea>
            </div>
        </div>
    </div>
</form>
<!--                            still need?
<div class="row">
    <div class="col-sm-12 form-group">
        <button type="submit" class="button" name="submitButton"
                onclick="return confirm('Are you sure you want to submit?')"
        >Submit
        </button>
    </div>
</div>
-->
