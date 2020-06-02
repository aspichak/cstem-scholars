<?php

$title = 'Applications';
$layout = 'admin/_layout.php';
?>

<h1>Applications</h1>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Advisor</th>
    <th>Status</th>
    </thead>

    <?php foreach ($applications as $a) { ?>
        <?php if( $a->status == 'pending_review'){ ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../reviewers/applications.php?id={$a->id}", e($a->title)) ?></td>
            <td><?= e($a->advisorName) ?></td>
            <td><?= e('Pending Review') ?></td>
        </tr>
        <!-- MIGHT NEED ANOTHER CONDITIONAL FOR STATUS AFTER REVIEWER SUBMITS -->
        <?php } ?>
    <?php } ?>
</table>