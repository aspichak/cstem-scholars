<?php

$title = 'Applications';
$layout = 'admin/_layout.php';

helper('message_flash');
helper('application_status_label');
helper('money');
?>

<h1>Applications</h1>

<?= messageFlash() ?>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Status</th>
    <th>Award</th>
    </thead>

    <?php
    foreach ($applications as $a) { ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../admin/applications.php?id={$a->id}", e($a->title)) ?></td>
            <td><?= applicationStatus($a) ?></td>
            <td><?= $a->amountAwarded ? usd($a->amountAwarded) : '<span class="na">N/A</span>' ?></td>
        </tr>
        <?php
    } ?>
</table>
