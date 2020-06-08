<?php

$title = 'View application';
$layout = 'admin/_layout.php';
?>

<h1><?= e($application->title) ?></h1>
<?= HTML::template('application_details.php', $application) ?>

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
