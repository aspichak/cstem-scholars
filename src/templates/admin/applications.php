<?php

$title = 'Applications';
$layout = 'admin/_layout.php';

helper('message_flash');
helper('application_status_label');
helper('money');
?>

<h1>Applications</h1>

<?= messageFlash() ?>

<section class="filter">
    <label for="periodID">Choose a period:</label>
    <form id="periods" method="get">
        <select name="periodID" id="periodID">
            <?php
            foreach (Period::all('1 ORDER BY beginDate DESC') as $p) {
                $attrs = ['value' => $p->id];

                if ($p->id == $selectedPeriodID) {
                    $attrs[] = 'selected';
                }

                echo tag(
                    'option',
                    date("M j, Y", strtotime($p->beginDate)) . ' - ' . date("M j, Y", strtotime($p->deadline)),
                    $attrs
                );
            }
            ?>
        </select>
        <input type="submit" value="Change Period">
    </form>
</section>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Status</th>
    <th>Award</th>
    </thead>

    <?php foreach ($applications as $a) { ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../admin/applications.php?id={$a->id}", e($a->title)) ?></td>
            <td><?= applicationStatus($a) ?></td>
            <td><?= $a->amountAwarded ? usd($a->amountAwarded) : '<span class="na">N/A</span>' ?></td>
        </tr>
    <?php } ?>
</table>
