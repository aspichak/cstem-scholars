<?php

require_once '../includes/init.php';

User::authorize('admin');

echo HTML::template('admin/index.php');
