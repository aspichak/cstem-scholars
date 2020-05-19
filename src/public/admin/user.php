<?php

require_once '../../init.php';

User::authorize('admin');

$c = new ModelController(User::class);

$c->create();
$c->read();
$c->update();
$c->delete();

if ($c->done()) {
    // TODO: Show success/error message
    HTTP::redirect('../admin/users.php');
}

echo HTML::template('admin/user.php', ['form' => $c->form()]);
