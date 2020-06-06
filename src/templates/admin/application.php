<?php

$title = 'View application';
$layout = 'admin/_layout.php';
?>

<h1><?= e($application->title) ?></h1>

<?php if ($error) echo tag('div', $error, ['class' => 'message error']) ?>

<ul class="tabs">
    <li><a class="active" href="#award">Award</a></li>
    <li><a href="#reject">Reject</a></li>
</ul>

<div class="tab" id="award">
    <h2>Award</h2>

    <form method="POST">
        <?= input('hidden', 'csrfToken', Form::csrfToken()) ?>

        <div class="form-group">
            <label for="message">Message (optional):</label><br>
            <?= textarea('message', HTTP::post('message'), ['rows' => 10, 'style' => 'width: 100%']) ?>
        </div>

        <div class="form-group">
            <label for="amount">Amount awarded:</label><br>
            <?= input('number', 'amount', HTTP::post('amount'), ['min' => 0, 'step' => 0.01]) ?>
        </div>

        <button type="submit" name="action" value="award">Award</button>
    </form>
</div>

<div class="tab" id="reject">
    <h2>Reject</h2>

    <form method="POST">
        <?= input('hidden', 'csrfToken', Form::csrfToken()) ?>

        <div class="form-group">
            <label for="reason">Reason (required):</label><br>
            <?= textarea('reason', HTTP::post('reason'), ['rows' => 10, 'style' => 'width: 100%']) ?>
        </div>

        <button type="submit" name="action" value="reject" class="danger">Reject</button>
    </form>
</div>

<h2>Application</h2>

<?= HTML::template('application_details.php', $application) ?>

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
