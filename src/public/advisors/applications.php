<?php

require_once '../../init.php';

User::authorize('advisor');

$c = new ModelController(Application::class);

// TODO: Filter applications by period, student name, email, status, etc.
$c->index('advisors/applications.php', ['applications' => Application::all()]);
$c->read();

if ($c->action() == 'update') {
    // TODO: award/reject application
    $m = $c->model();
    $m->status = 'submitted';
    $m->save();
}

echo HTML::template('advisor/applications.php', ['application' => $c->model()]);
