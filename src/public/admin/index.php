<?php

require_once '../../init.php';

User::authorize('admin');

echo HTML::template('admin/index.php');
