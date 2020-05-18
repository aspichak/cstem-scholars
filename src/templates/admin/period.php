<?php

$title = 'Period Form';
$layout = 'admin/_layout.php';
?>

<h1>Period</h1>

<?php
if ($form->errors()) {
    echo $form->errors();
}
?>

<form method="POST">
    <?= $form->csrf() ?>

    <label>Start Date: <?= $form->date('beginDate', ['required']) ?></label>
    <label>Student Deadline: <?= $form->date('deadline', ['required']) ?></label>
    <label>Review Deadline: <?= $form->date('advisorDeadline', ['required']) ?></label>
    <label>Total Budget: <?= $form->money('budget', ['required']) ?></label>

    <button type="submit">Submit</button>
</form>
