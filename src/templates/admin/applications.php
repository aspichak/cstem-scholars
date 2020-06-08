<?php

$title = 'Applications';
$layout = 'admin/_layout.php';

helper('message_flash');
helper('application_status_label');
helper('money');
?>

<h1>Applications for Period: <?= Period::get($period)->beginDate ?></h1>

<?= messageFlash() ?>

<label for="periodID">Choose a period:</label>
<form id="periods" method="get">
    <select name="periodID" id="periodID">
        <?php
        foreach (Period::all() as $p) {
            echo '<option value="' . $p->id . '">' . $p->beginDate . '</option>';
        }
        ?>
    </select>
    <input type="submit" name="periodIDbutton" value="Change Period">
</form>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Status</th>
    <th>Award</th>
    </thead>

    <?php
    foreach ($applications as $a) {
        if ($a->periodID == $period) { ?>
            <tr>
                <td><?= e($a->name) ?></td>
                <td><?= HTML::link("../admin/applications.php?id={$a->id}", e($a->title)) ?></td>
                <td><?= applicationStatus($a) ?></td>
                <td><?= $a->amountAwarded ? usd($a->amountAwarded) : '<span class="na">N/A</span>' ?></td>
            </tr>
            <?php
        }
    } ?>
</table>
