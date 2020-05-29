<?php

$is_login = true;
require_once __DIR__ . "/../init.php";
require_once __DIR__ . "/../vendor/apereo/phpcas/CAS.php";

function whitelist($value, $allowedValues, $default = null)
{
    return in_array($value, $allowedValues) ? $value : $default;
}

if (isset($_REQUEST['logout'])) {
    phpCAS::logout();
    $_SESSION = array();
    session_destroy();
    HTTP::redirect('/');
}

// Production code block. uses CAS
phpCAS::client(CAS_VERSION, CAS_HOSTNAME, CAS_PORT, CAS_URI);
if (DEBUG) {
    phpCAS::setDebug();
    phpCAS::setNoCasServerValidation();
    phpCAS::setVerbose(true);
} else {
    phpCAS::setCasServerCACert(CAS_CA_CERT);
}
phpCAS::handleLogoutRequests();
phpCAS::forceAuthentication();

if (isset($_SESSION["user"])) {
    $user = User::current();
} else {
    $userName = phpCAS::getUser();
    $attributes = phpCAS::getAttributes();
    $_SESSION['name'] = "{$attributes['FirstName']} {$attributes['LastName']}";
    $userType = $attributes["UserType"];
    $email = $attributes["Email"];
    $_SESSION["id"] = $attributes["Ewuid"];
    $_SESSION["email"] = $email;
    $_SESSION["user"] = $userName;
    $user = User::current();
    if (User::get($_SESSION['email']) == null) {
        $user->save();
    }
}

$type = $_GET["id"];

if ($type == "admin") {
    // ADMIN PAGE LOGIN
    if ($user->isAdmin()) {
        HTTP::redirect('admin/');
    } else {
        HTTP::error(
            'You are not an admin for the CSTEM Undergraduate Research Grant.',
            401,
            'Admin'
        );
    }
} elseif ($type == 'student') {
    // STUDENT PAGE LOGIN
    if (!$user->isStudent()) {
        HTTP::error(
            'You are not an EWU student and are not eligible to apply for the CSTEM Research Grant.',
            401,
            'Student Application'
        );
    } elseif (Period::current() != null) {
        HTTP::redirect('students/');
    } else {
        HTTP::error(
            'The CSTEM Research Grant application has been closed. Please check back at a later date.',
            403,
            'Student Application'
        );
    }
} elseif ($type == 'advisor') {
    // ADVISOR PAGE LOGIN
    if ($user->isStudent()) {
        HTTP::error(
            'You are not a faculty advisor for Eastern\'s CSTEM research grant.',
            401,
            'Advisor Approval'
        );
    } elseif (Period::currentForAdvisors() != null) {
        if ($user->isAdvisor()) {
            header("location:advisor/index.php");
        } else {
            HTTP::error(
                'Currently there are no applications needing approval. Please check back later.',
                204,
                'Advisor Approval'
            );
        }
    } else {
        HTTP::error(
            'The deadline has passed for approving students applications.',
            204,
            'Advisor Approval'
        );
    }
} elseif ($type == "reviewer") {
    // REVIEWER PAGE LOGIN
    if ($user->isStudent()) {
        HTTP::error(
            'You are not a reviewer for Eastern\'s CSTEM Research Grant application.',
            401,
            'Application Reviewal'
        );
    }
    if ($user->isReviewer()) {
        if (Period::currentForAdvisors() != null) {
            header("location:/reviewers/ReviewStudents.php");
        } else {
            header("location:infoPages/closedReviewer.php");
        }
    } else {
        HTTP::error(
            'You are not a reviewer for Eastern\'s CSTEM Research Grant application.',
            401,
            'Application Reviewal'
        );
    }
}