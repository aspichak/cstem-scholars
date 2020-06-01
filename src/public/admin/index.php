<?php

require_once '../../init.php';

User::authorize('admin');

echo HTML::template('reviewer/index_layout.php');
