<?php

require_once '../includes/init.php';

User::authorize('admin');

$c = new ModelController(Application::class);

// TODO: Filter applications by period, student name, email, status, etc.
$c->index('admin/applications.php', ['applications' => Application::all()]);
$c->read();

if ($c->action() == 'update') {
    // TODO: award/reject application
}

echo HTML::template('admin/application.php', ['application' => $c->model()]);
