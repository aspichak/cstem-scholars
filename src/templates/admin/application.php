<?php

$title = 'View application';
$layout = 'admin/_layout.php';
?>

<h1><?= e($application->title) ?></h1>

<?php if ($error) echo tag('div', $error, ['class' => 'error']) ?>

<ul class="tabs">
    <li><a class="active" href="#award">Award</a></li>
    <li><a href="#reject">Reject</a></li>
</ul>

<div class="tab" id="award">
    <h2>Award</h2>

    <form method="POST">
        <?= tag('input', null, ['type' => 'hidden', 'name' => 'csrfToken', 'value' => Form::csrfToken()]) ?>

        <div class="form-group">
            <label for="message">Message (optional):</label><br>
            <textarea name="message" id="message" rows="10" style="width: 100%"></textarea>
        </div>

        <div class="form-group">
            <label for="amount">Amount awarded:</label><br>
            <input type="number" name="amount" id="amount" min="0" step="0.01">
        </div>

        <button type="submit" name="action" value="award">Award</button>
    </form>
</div>

<div class="tab" id="reject">
    <h2>Reject</h2>

    <form method="POST">
        <?= tag('input', null, ['type' => 'hidden', 'name' => 'csrfToken', 'value' => Form::csrfToken()]) ?>

        <div class="form-group">
            <label for="reason">Reason (required):</label><br>
            <textarea name="reason" id="reason" rows="10" style="width: 100%"></textarea>
        </div>

        <button type="submit" name="action" value="reject" class="danger">Reject</button>
    </form>
</div>

<h2>Application</h2>

<p>Status: <?= $application->status ?></p>

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

<h2>Reviews</h2>

<?php foreach ($application->reviews() as $review) { ?>

    <p><strong><?= e($review->reviewer()->name) ?></strong>:</p>
    <section class="review">

        <?php
            if (!$review->submitted) {
                echo tag('p', 'This review is not yet submitted');
            } else {
        ?>

            <?php foreach (Review::QUESTIONS as $i => $q) { ?>
                <p><?= $q ?></p>
                <blockquote><?= e($review->{'q' . ($i + 1)}) ?> / 3</blockquote>
            <?php } ?>

            <p>Comments:</p>
            <blockquote>
                <pre><?= $review->comments ? e($review->comments) : 'No comment' ?></pre>
            </blockquote>

            <p>Recommend funding?</p>
            <blockquote><?= $review->fundingRecommended ? 'Yes' : 'No' ?></blockquote>

        <?php } ?>

    </section>

<?php } ?>
