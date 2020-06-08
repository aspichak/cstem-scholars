<?php

$title = 'Applications';
$layout = 'admin/_layout.php';

helper('message_flash');
helper('application_status_label');
?>

<h1>Applications for Period: <?= Period::get($period)->beginDate ?></h1>

<?= messageFlash() ?>

<script language="JavaScript">
    function SelectRedirect() {
        window.location = string.concat(window.location.href, "?id=" , document.getElementById('periodSelect').value);
    }
</script>

<label for="periods">Choose a period:</label>
<select id="periodSelect" onchange="SelectRedirect">
    <option value="1">Period 1</option>
    <option value="2">Period 2</option>
</select>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Status</th>
    </thead>

    <?php
    foreach ($applications as $a) {
        if ($a->periodID == $period) { ?>
            <tr>
                <td><?= e($a->name) ?></td>
                <td><?= HTML::link("../admin/applications.php?id={$a->id}", e($a->title)) ?></td>
                <td><?= applicationStatus($a) ?></td>
            </tr>
            <?php
        }
    } ?>
</table>
