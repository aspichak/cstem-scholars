<?php
session_start();

define('ROOT', __DIR__ . '/..');

function path($file)
{
    return ROOT . '/' . $file;
}

require_once path('config.php');
require_once path('vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

DB::configure(DB_CONNECTION_STRING, DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function authorize($role)
{
    if (!isset($_SESSION) || $_SESSION['role'] != $role) {
        http_response_code(401);
        require path('infoPages/unauthorized.php');
        exit();
    }
}

function redirect($url, $flash = null)
{
    if ($flash) {
        $_SESSION['flash'] = serialize($flash);
    }

    header("Location: $url");
    exit();
}

function getFlash()
{
    $flash = null;

    if (isset($_SESSION['flash'])) {
        $flash = unserialize($_SESSION['flash']);
        $_SESSION['flash'] = null;
    }

    return $flash;
}

function isPost()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function get($param, $default = NULL)
{
    return $_GET[$param] ?? $default;
}

function post($param, $default = NULL)
{
    return $_POST[$param] ?? $default;
}

function render($template, $v)
{
    ob_start();
    require path($template);
    return ob_get_clean();
}

function email($to, $subject, $body)
{
    $mail = new PHPMailer;

    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress($to);

    $mail->isSMTP();
    $mail->isHTML(true);
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Host = SMTP_HOST;
    $mail->Port = SMTP_PORT;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->Subject = $subject;
    $mail->Body = $body;

    return $mail;
}
