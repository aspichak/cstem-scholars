<?php

define('DEBUG', true);
define('BASE_URL', 'http://localhost');
define('ADMIN_EMAIL', 'admin@example.edu');
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('ALLOWED_UPLOAD_EXTENSIONS', ['pdf', 'xls', 'xlsx', 'doc', 'docx', 'txt', 'jpg', 'jpeg']);

//define('CAS_VERSION', CAS_VERSION_2_0);
//define('CAS_HOSTNAME', 'localhost');
//define('CAS_PORT', 443);
//define('CAS_URI', 'cas.php?');
//define('CAS_CA_CERT', null);

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

// TODO: Delete this
/* CAS Protocol Configuration */
$cas_host = 'login.ewu.edu';
$cas_port = 443;
$cas_context = 'cas.php?';
$cas_server_ca_cert_path = '/etc/ssl/certs/ca-certificates.crt';
