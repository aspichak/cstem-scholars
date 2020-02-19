<?php

require_once __DIR__ . "/SSO/CAS/CAS.php";
// Pulls the
require_once __DIR__ . "/SSO/config.php";
// /* CAS Protocol Configuration */

phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
//phpCAS::client(SAML_VERSION_1_1, $cas_host, $cas_port, $cas_context);

//* Don't validate the CAS Server
// phpCAS::setDebug();
// phpCAS::setNoCasServerValidation();

// Set the cert path
phpCAS::setCasServerCACert($cas_server_ca_cert_path);

// DO CAS Authentication
phpCAS::forceAuthentication();

// Normal Web Stuff
$headers = getallheaders();
ksort($headers);
print "<h2>Request Headers</h2>";
foreach ($headers as $name => $value) {
    echo '<li><strong>', $name, ': </strong>', $value, '</li>' . PHP_EOL;
}
$parameters = $GET;
print "<h2>Request Parameters</h2>";
foreach ($_GET as $key => $value) {
    echo $key . " : " . $value . "<br />\r\n";
}


// Get the User
$user = phpCAS::getUser();
print "<h2>NetID</h2>";
print $user;

// Attributes
print "<h2>Attributes</h2>";
$attributes = phpCAS::getAttributes();
ksort($attributes);
foreach ($attributes as $key => $value) {
    if (is_array($value)) {
        echo '<li><strong>', $key, ':<ul>';
        foreach ($value as $item) {
            echo '<li></strong>', $item, '</li>';
        }
        echo '</ul></li>';
    } else {
        echo '<li><strong>', $key, ': </strong>', $value, '</li>' . PHP_EOL;
    }
}
?>
