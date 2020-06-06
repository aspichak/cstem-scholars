<?php

$title = 'Applications';
$layout = 'admin/_layout.php';

helper('application_status_label');
?>

<h1>Applications</h1>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Status</th>
    </thead>

    <?php
    foreach ($applications as $a) {
        if ($a->periodID == $period->id) { ?>
            <tr>
                <td><?= e($a->name) ?></td>
                <td><?= HTML::link("../advisors/applications.php?id={$a->id}", e($a->title)) ?></td>
                <td><?= applicationStatus($a) ?></td>
            </tr>
            <?php
        }
    } ?>
</table>
