<?php

$title = 'User Form';
$layout = 'admin/_layout.php';
?>

<h1>User</h1>

<?php
if ($form->errors()) {
    echo $form->errors();
}
?>

<form method="POST">
    <?= $form->csrf() ?>

    <label>Name: <?= $form->text('name', ['required']) ?></label>
    <label>Email: <?= $form->email('email', ['required']) ?></label>

    <p>Roles:</p>
    <label><?= $form->checkbox('isAdvisor') ?> Advisor</label><br>
    <label><?= $form->checkbox('isReviewer') ?> Reviewer</label><br>
    <label><?= $form->checkbox('isAdmin') ?> Administrator</label><br>

    <button type="submit">Submit</button>
</form>
