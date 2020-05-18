<?php

helper('crud');

$title = 'Periods';
$layout = 'admin/_layout.php';
?>

<h1>Periods</h1>

<?php
if ($form->errors()) {
    echo $form->errors();
}
?>

<table>
    <tr>
        <th>Start Date</th>
        <th>Student Deadline</th>
        <th>Review Deadline</th>
        <th>Total Budget</th>
        <th></th>
    </tr>

    <tr>
        <form method="POST">
            <?= $form->csrf() ?>
            <td><?= $form->date('beginDate') ?></td>
            <td><?= $form->date('deadline') ?></td>
            <td><?= $form->date('advisorDeadline') ?></td>
            <td><?= $form->money('budget', ['required']) ?></td>
            <td><button type="submit">Add</button></td>
        </form>
    </tr>

    <?php foreach ($periods as $p) { ?>
        <tr>
            <td><?= date('M j, Y', strtotime($p->beginDate)) ?></td>
            <td><?= date('M j, Y', strtotime($p->deadline)) ?></td>
            <td><?= date('M j, Y', strtotime($p->advisorDeadline)) ?></td>
            <td>$<?= number_format($p->budget, 2) ?></td>
            <td><?= actionButtons('periods.php', $p->key()) ?></td>
        </tr>
    <?php } ?>
</table>
