<?php
// Figure out how to move this into a environment setting that can be overridden
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
