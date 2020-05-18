<?php

helper('crud');

$title = 'Users';
$layout = 'admin/_layout.php';
?>

<h1>Users</h1>
<p><a href="../admin/user.php">Add new user</a></p>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
        <th></th>
    </tr>

    <?php foreach ($users as $u) { ?>
        <tr>
            <td><?= e($u->name) ?></td>
            <td><?= e($u->email) ?></td>
            <td><?= implode(', ', $u->roles()) ?></td>
            <td><?= actionButtons('user.php', $u->key()) ?></td>
        </tr>
    <?php } ?>
</table>
