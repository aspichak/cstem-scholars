<?php

define('DEBUG', true);

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'researchGrant');
define('DB_CONNECTION_STRING', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME);

define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM_EMAIL', 'noreply@ewu.edu');
define('SMTP_FROM_NAME', 'EWU CSTEM Scholars');

date_default_timezone_set('America/Los_Angeles');