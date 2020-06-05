<?php

require_once '../../init.php';

User::authorize('student');

$user = User::current();
$period = Period::current();
$application = Application::first('studentID=? AND periodID=?', $user->email, $period->id);

echo HTML::template('students/status.php', ['application' => $application, 'user' => $user, 'period' => $period]);
