<?php

require_once '../includes/init.php';

User::authorize('admin');

// TODO: Filter users by name, email, roles, etc.
echo HTML::template('admin/users.php', ['users' => User::all()]);
