<?php

$title = 'Advisor Application Review Dashboard';
$layout = 'advisors/_layout.php';
?>

<h1>Advisor Dashboard</h1>

<p>This page will display applications</p>


<h1>Applications</h1>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Status</th>
    </thead>

    <?php foreach ($applications as $a) { ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../advisors/applications.php?id={$a->id}", e($a->title)) ?></td>
            <td><?= e($a->status) ?></td>
        </tr>
    <?php } ?>
</table>
