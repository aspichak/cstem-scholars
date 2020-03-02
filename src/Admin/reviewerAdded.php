<?php

require_once '../includes/init.php';
authorize('admin');

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
$name = $_POST['Name'];
$email = strtolower($_POST['email']);
$major = $_POST['Department'];

$stm = $pdo->prepare("SELECT * FROM Reviewers WHERE REmail = " . $email);
$stm->execute();
$iReviewer = $stm->fetch();

$stm = $pdo->prepare("SELECT COUNT(REmail) FROM Reviewers WHERE REmail = :email");
$stm->bindValue(':email', $email);
$stm->execute();
$count = $stm->fetch();

if ($count['COUNT(REmail)'] != 1) {
    $query = $pdo->prepare("INSERT INTO Reviewers (RName, REmail, Major, Active) VALUES (?, ?, ?, ?)");
    $query->execute([$name, $email, $major, '1']);
} else {
    $query = $pdo->prepare("UPDATE Reviewers SET RName = :rname, Major = :major, Active = 1 WHERE REmail = :email");
    $query->bindValue(':rname', $name);
    $query->bindValue(':major', $major);
    $query->bindValue(':email', $email);
    $query->execute();
}
header("location:edit.php");

?>