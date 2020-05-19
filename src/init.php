<?php

session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

DB::configure(DB_CONNECTION_STRING, DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

define('ROOT', __DIR__ . '/..');

function path($file)
{
    return ROOT . '/' . $file;
}

/**
 * @deprecated Use User::authorize() instead
 */
function authorize($role)
{
    if (!isset($_SESSION) || !isset($_SESSION['role']) || $_SESSION['role'] != $role) {
        error('Unauthorized user', "Unauthorized user", 401);
        exit();
    }
}

/**
 * @deprecated Use HTTP::redirect() instead
 */
function redirect($url, $flash = null)
{
    if ($flash) {
        $_SESSION['flash'] = serialize($flash);
    }

    header("Location: $url");
    exit();
}

/**
 * @deprecated Use HTTP::error() instead
 */
function error($title = "fatal error", $body = "fatal error", $code=400)
{
    $_SESSION['errtitle'] = $title;
    $_SESSION['errbody'] = $body;
    $_SESSION['errcode'] = $code;
    redirect("/includes/errorPage/errorPage.php");
}

/**
 * @deprecated Use HTTP::flash() instead
 */
function getFlash()
{
    $flash = null;

    if (isset($_SESSION['flash'])) {
        $flash = unserialize($_SESSION['flash']);
        $_SESSION['flash'] = null;
    }

    return $flash;
}

/**
 * @deprecated Use HTTP::isPost() instead
 */
function isPost()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

/**
 * @deprecated Use HTTP::get() instead
 */
function get($param, $default = null)
{
    return $_GET[$param] ?? $default;
}

/**
 * @deprecated Use HTTP::post() instead
 */
function post($param, $default = null)
{
    return $_POST[$param] ?? $default;
}

/**
 * @deprecated Use HTML::template() instead
 */
function render($template, $v = null)
{
    ob_start();
    require path($template);
    return ob_get_clean();
}
