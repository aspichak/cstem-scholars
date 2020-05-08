<?php

require_once '../includes/init.php';
User::authorize('admin');

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

$email = $_POST['email'];

switch ($_POST['emailType']) {
    case "ApplicationSubmission":
        $query = $pdo->prepare("UPDATE Settings SET ApplicationSubmission = :email");
        $query->bindValue(':email', $email);
        $query->execute();
        break;
    case "ChangesRequested":
        $query = $pdo->prepare("UPDATE Settings SET ChangesRequested=:email");
        $query->bindValue(':email', $email);
        $query->execute();
        break;
    case "ApplicationApproval":
        $query = $pdo->prepare("UPDATE Settings SET ApplicationApproval=:email");
        $query->bindValue(':email', $email);
        $query->execute();
        break;
    case "ApplicationAward":
        $query = $pdo->prepare("UPDATE Settings SET ApplicationAward=:email");
        $query->bindValue(':email', $email);
        $query->execute();
        break;
}
header("location:edit.php");

?>