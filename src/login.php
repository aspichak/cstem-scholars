<?php

require_once "includes/init.php";
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/vendor/apereo/phpcas/CAS.php";

function whitelist($value, $allowedValues, $default = null)
{
    return in_array($value, $allowedValues) ? $value : $default;
}

if (isset($_REQUEST['logout'])) {
    phpCAS::logout();
    $_SESSION = array();
    session_destroy();
    redirect('/');
}

phpCAS::client(CAS_VERSION_3_0, $cas_host, $cas_port, $cas_context);
phpCAS::setNoCasServerValidation(); // change for production
//phpCAS::setCasServerCACert($cas_server_ca_cert_path, false); // change for production
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

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>CSTEM Grants Dev Login</title>
        <style type="text/css">
            body {
                font-family: sans-serif;
                line-height: 1.5em;
            }

            li {
                display: inline-block;
                margin-right: 1.5em;
            }
        </style>
    </head>
    <body>
    <h1>Developer Login</h1>
    <p>
        Logging in as a <strong><?= $role ?></strong> named

        <a href="login.php?as=alice&id=<?= $role ?>">Alice</a>,
        <a href="login.php?as=bob&id=<?= $role ?>">Bob</a>,
        <a href="login.php?as=carol&id=<?= $role ?>">Carol</a>,
        <a href="login.php?as=dave&id=<?= $role ?>">Dave</a>,
        <a href="login.php?as=eve&id=<?= $role ?>">Eve</a>,
        <a href="login.php?as=faythe&id=<?= $role ?>">Faythe</a>;

        or log in as a

        <a href="login.php?id=student">student</a>,
        <a href="login.php?id=advisor">advisor</a>,
        <a href="login.php?id=reviewer">reviewer</a>, or
        <a href="login.php?id=admin">admininstrator</a>.
    </p>
    </body>
    </html>

    <?php
} else { // Production code block. uses CAS
    phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
    //phpCAS::setCasServerCACert($cas_server_ca_cert_path);
    phpCAS::setNoCasServerValidation();
    phpCAS::handleLogoutRequests();
    phpCAS::forceAuthentication();

    if (isset($_SESSION["user"])) {
        $user = User::current();
    } else {
        error(
            'Admin',
            'You are not an admin for the CSTEM Undergraduate Research Grant.',
            401
        );
    }
} elseif ($type == 'student') {
    // STUDENT PAGE LOGIN
    if (!$user->isStudent()) {
        error(
            'Student Application',
            'You are not an EWU student and are not eligible to apply for the CSTEM Research Grant.',
            401
        );;
    } elseif (Period::current() != null) {
        header("location:/students");
    } else {
        error(
            'Student Application',
            'The CSTEM Research Grant application has been closed. Please check back at a later date.',
            403
        );
    }
} elseif ($type == 'advisor') {
    // ADVISOR PAGE LOGIN
    if ($user->isStudent()) {
        error(
            'Advisor Approval',
            'You are not a faculty advisor for Eastern\'s CSTEM research grant.',
            401
        );
    } elseif (Period::currentForAdvisors() != null) {
        if ($user->isAdvisor()) {
            header("location:faculty/facultylandingpage.php");
        } else {
            error(
                'Advisor Approval',
                'Currently there are no applications needing approval. Please check back later.',
                204
            );
        }
    } else {
        error(
            'Advisor Approval',
            'The deadline has passed for approving students applications.',
            204
        );
    }
} elseif ($type == "reviewer") {
    // REVIEWER PAGE LOGIN
    if ($user->isStudent()) {
        error(
            'Application Reviewal',
            'You are not a reviewer for Eastern\'s CSTEM Research Grant application.',
            401
        );
    }
    if ($user->isReviewer()) {
        if (Period::currentForAdvisors() != null) {
            header("location:/reviewers/ReviewStudents.php");
        } else {
            header("location:infoPages/closedReviewer.php");
        }
    } else {
        error(
            'Application Reviewal',
            'You are not a reviewer for Eastern\'s CSTEM Research Grant application.',
            401
        );
    }
}