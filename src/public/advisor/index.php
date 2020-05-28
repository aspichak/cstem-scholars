<?php

require_once '../../init.php';

User::authorize('advisor');

$c = new ModelController(Application::class);

// TODO: Filter applications by period, student name, email, status, etc.
$c->index('admin/applications.php', ['applications' => Application::all()]);
$c->read();

if ($c->action() == 'update') {
    // TODO: award/reject application
}

echo HTML::template('advisor/index.php', ['application' => $c->model()]);
