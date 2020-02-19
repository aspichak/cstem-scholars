<?php
session_start();

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/sso-example/SSO/CAS/CAS.php";
require_once __DIR__ . "/sso-example/SSO/config.php";

function get($field, $default = NULL)
{
    return isset($_GET[$field]) ? $_GET[$field] : $default;
}

function whitelist($value, $allowedValues, $default = NULL)
{
    return in_array($value, $allowedValues) ? $value : $default;
}

function redirect($url)
{
    header("Location: $url");
}

if (isset($_REQUEST['logout'])) {
    phpCAS::logout();
    $_SESSION = array();
    session_destroy();
    redirect('/');
}

if (defined('DEBUG') && DEBUG) {
    $roles = ['student', 'faculty', 'reviewer', 'admin'];
    $role = whitelist(get('id'), $roles, 'student');

    $identities = [
        'alice' => '00700001',
        'bob' => '00700002',
        'carol' => '00700003',
        'dave' => '00700004',
        'eve' => '00700005',
        'faythe' => '00700006'
    ];

    $locations = [
        'student' => '/students',
        'faculty' => '/faculty/facultylandingpage.php',
        'reviewer' => '/reviewers/ReviewStudents.php',
        'admin' => '/Admin/index.php'
    ];

    $user = whitelist(get('as'), array_keys($identities));

    if ($role == 'admin') {
        redirect($locations['admin']);
    }

    if ($user) {
        session_unset();
        $_SESSION["id"] = $identities[$user];
        $_SESSION["email"] = "$user@ewu.edu";
        $_SESSION["user"] = $user;
        $_SESSION["role"] = $role;
        redirect($locations[$role]);
    }
    ?>

    <!DOCTYPE html>
    <html>
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
        <a href="login.php?id=faculty">faculty</a>,
        <a href="login.php?id=reviewer">reviewer</a>, or
        <a href="login.php?id=admin">admininstrator</a>.
    </p>
    </body>
    </html>

    <?php
} else {
    phpCAS::client(SAML_VERSION_1_1, $cas_host, $cas_port, $cas_context);
    //phpCAS::setCasServerCACert($cas_server_ca_cert_path);
    phpCAS::setNoCasServerValidation();
    phpCAS::handleLogoutRequests();
    phpCAS::forceAuthentication();

    $user = phpCAS::getUser();
    $attributes = phpCAS::getAttributes();
    $userType = $attributes["UserType"];
    $email = $attributes["Email"];
    $_SESSION["id"] = $attributes["Ewuid"];
    $_SESSION["email"] = $email;
    $_SESSION["user"] = $user;
    $_SESSION["role"] = "student";

    if (DB::contains("Advisor", "AEmail = ?", $attributes["Email"]))
        $_SESSION["role"] = "faculty";

    if (DB::contains("Reviewers", "REmail = ? AND Active = 1", $attributes["Email"]))
        $_SESSION["role"] = "reviewer";

    if ($email == "lcornick@ewu.edu")
        $_SESSION["role"] = "admin";

    $database = parse_ini_file("config.ini");
    $host = $database['host'];
    $db = $database['db'];
    $user = $database['user'];
    $pass = $database['pass'];
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    $sth = $pdo->prepare("SELECT BeginDate, Deadline, AdvisorDeadline FROM Settings");
    $sth->execute();
    $dates = $sth->fetch();
    $beginDate = $dates["BeginDate"];
    $deadLine = $dates["Deadline"];
    $advisorDeadLine = $dates["AdvisorDeadline"];
    $today = date("Y-m-d");
    $sth = $pdo->prepare("SELECT AEmail FROM Advisor");
    $sth->execute();
    $advisorEmails = $sth->fetchAll();
    $sth = $pdo->prepare("SELECT REmail FROM Reviewers");
    $sth->execute();
    $reviewerEmails = $sth->fetchAll();

    $type = $_GET["id"];

    if ($type == "admin") {
        if ($email == "lcornick@ewu.edu") header("location:/Admin/index.php");
        else {
            header("location:infoPages/NotAdmin.php");
        }
    } elseif ($type == 'student') {
        if ($userType == 'Employee') header("location:infoPages/NotStudent.php");
        elseif ($today <= $deadLine && $today >= $beginDate) {
            header("location:/students");
        } else {
            header("location:infoPages/closedStudent.php");
        }
    } elseif ($type == 'faculty') {
        if ($userType == 'Student') header("location:infoPages/NotAdvisor.php");
        elseif ($today <= $advisorDeadLine && $today >= $beginDate) {

            foreach ($advisorEmails as $value) {

                if ($value["AEmail"] == $email) {
                    $isAdvisor = true;

                }
            }
            if ($isAdvisor) {
                header("location:faculty/facultylandingpage.php");
            } else {
                header("location:infoPages/emptyFaculty.php");
            }
        } else {
            header("location:infoPages/closedFaculty.php");
        }

    } elseif ($type == "reviewer") {
        if ($userType == 'Student') header("location:infoPages/notReviewer.php");
        foreach ($reviewerEmails as $value) {
            if ($value["REmail"] == $email) {
                $isReviewer = true;

            }
        }
        if ($isReviewer) {
            if ($today > $advisorDeadLine) {
                header("location:/reviewers/ReviewStudents.php");
            } else {
                header("location:infoPages/closedReviewer.php");
            }
        } else {
            header("location:infoPages/notReviewer.php");
        }
    }
}