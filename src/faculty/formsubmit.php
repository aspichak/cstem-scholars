<?php
$end = false;
$database = parse_ini_file("../config.ini");
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
$sth = $pdo->prepare("SELECT Deadline FROM Settings");
$sth->execute();
$date_array = $sth->fetch();
$deadline = $date_array[0];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$tableName = 'Applications' . $month . $year;
$id = intval($_GET['id']);
if (isset($_POST['submit'])) {
    $advisorComments = $_POST['comments'];

    switch ($_POST["group"]) {
        case "Approved":
            $query = $pdo->prepare("UPDATE `$tableName` SET AdvisorApproved='1', AdvisorComments='$advisorComments'  WHERE ApplicationNum=$id");
            $query->execute();
            $end = true;
            break;
        case "Not Approved":
            $query = $pdo->prepare("UPDATE `$tableName` SET AdvisorApproved='3', AdvisorComments='$advisorComments'  WHERE ApplicationNum=$id");
            $query->execute();
            $end = true;
            break;
        case "Update":
            $query = $pdo->prepare("UPDATE `$tableName` SET AdvisorApproved='2', AdvisorComments='$advisorComments'  WHERE ApplicationNum=$id");
            $query->execute();
            $end = true;
            break;
    }
}
if ($end == true) {
    header("location:facultylandingpage.php");
}
?>
	

