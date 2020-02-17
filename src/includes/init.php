<?php
session_start();

define('ROOT', __DIR__ . '/..');

function path($file) {
	return ROOT . '/' . $file;
}

require_once path('config.php');
require_once path('vendor/autoload.php');

DB::configure(DB_CONNECTION_STRING, DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function authorize($role) {
	if (!isset($_SESSION) || $_SESSION['role'] != $role) {
		http_response_code(401);
		require path('infoPages/unauthorized.php');
		exit();
	}
}

function redirect($url, $flash = null) {
	if ($flash) {
		$_SESSION['flash'] = serialize($flash);
	}

	header("Location: $url");
	exit();
}

function getFlash() {
	$flash = null;
	
	if (isset($_SESSION['flash'])) {
		$flash = unserialize($_SESSION['flash']);
		$_SESSION['flash'] = null;
	}

	return $flash;
}

function isPost() {
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function get($param, $default = NULL) {
	return $_GET[$param] ?? $default;
}

function post($param, $default = NULL) {
	return $_POST[$param] ?? $default;
}
