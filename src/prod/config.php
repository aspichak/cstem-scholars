<?php

define('DEBUG', false);

define('BASE_URL', 'http://localhost');

define('UPLOAD_DIR', __DIR__ . '/students/uploads');
define('ALLOWED_UPLOAD_EXTENSIONS', ['pdf', 'xls', 'xlsx', 'doc', 'docx', 'txt', 'jpg', 'jpeg']);

define('DB_HOST', 'database');
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
