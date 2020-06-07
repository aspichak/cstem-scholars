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
        <p>
            <?= $form->radio('q1', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q1', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q1', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q1', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Is the budget justified in the project description, including realistic?</label>
        <p>
            <?= $form->radio('q2', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q2', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q2', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q2', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Are the proposed methods appropriate to achieve the goals?</label>
        <p>
            <?= $form->radio('q3', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q3', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q3', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q3', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Is the timeline proposed reasonable?(Too little? Too much?)</label>
        <p>
            <?= $form->radio('q4', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q4', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q4', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q4', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Is the project well explained (including rationale) and justified?</label>
        <p>
            <?= $form->radio('q5', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q5', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q5', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q5', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Does the budget only include eligible activities (supplies, equipment, field travel,
            conference travel)?</label>
        <p>
            <?= $form->radio('q6', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q6', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q6', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q6', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Based on eligibility and quality scores, RECOMMEND one of the following
            categories</label>
        <p>
            <?= $form->radio('fundingRecommended', 1, ['value = "1"'] ) ?>
            <label>Yes</label>
            <?= $form->radio('fundingRecommended', 1, ['value = "0"'] ) ?>
            <label>No</label>
            <br>
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
