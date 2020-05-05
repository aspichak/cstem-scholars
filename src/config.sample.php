<?php

define('DEBUG', true);

define('BASE_URL', 'http://localhost');

define('UPLOAD_DIR', __DIR__ . '/students/uploads');
define('ALLOWED_UPLOAD_EXTENSIONS', ['pdf', 'xls', 'xlsx', 'doc', 'docx', 'txt', 'jpg', 'jpeg']);

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

/* CAS Protocol Configuration */
$cas_host = 'login.ewu.edu';
$cas_port = 443;
$cas_context = '/cas';
$cas_server_ca_cert_path = __DIR__ . '/certs/STAR_ewu.edu.ca';
$cas_real_hosts = array(
    'it-casauth01.eastern.ewu.edu',
    'it-casauth02.eastern.ewu.edu',
    'it-adfs01.eastern.ewu.edu',
    'it-adfs02.eastern.ewu.edu'
);

/**
 * DEBUGGING CAS AND THIS TOOL
 *
 * Two PHP Constants can be defined to enable debug logging
 * define('EWU_SSO_DEBUG', true); //This enables phpCAS debugging
 * define('EWU_SSO_DEBUG_LOG', <path to debug log file>); //This is where the log will be written
 *
 */
$cas_debug_log = '/tmp/phpCAS.log';
if (defined('EWU_SSO_DEBUG_LOG')) {
    $cas_debug_log = EWU_SSO_DEBUG_LOG;
}
