<?php

require_once '../../init.php';

User::authorize('reviewer');


echo HTML::template('reviewer/index_layout.php');


