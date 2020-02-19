<?php

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

$sth = $pdo->prepare("SELECT Deadline, DistributedApps FROM Settings");
$sth->execute();
$query = $sth->fetch();
$date1 = $query['Deadline'];
$date2 = date('Y-m-d');

//if($date1 < $date2 && $query['DistributedApps'] == 0){
$sth = $pdo->prepare("SELECT Deadline FROM Settings");
$sth->execute();
$date_array = $sth->fetch();
$deadline = $date_array['Deadline'];
$temp = explode("-", $deadline);
$year = $temp[0];
$month = $temp[1];
$appTableName = 'Applications' . $month . $year;
$revTableName = 'ReviewedApps' . $month . $year;

$appsQuery = $pdo->prepare(
    "SELECT ApplicationNum, Department, AEmail FROM `$appTableName` A1, Student S1 WHERE A1.SID = S1.SID AND A1.Submitted = 1 AND A1.AdvisorApproved = 1"
);
$appsQuery->execute();

foreach ($appsQuery->fetchAll(PDO::FETCH_ASSOC) as $student) {
    $appNum = $student["ApplicationNum"];
    $advisorEmail = $student["AEmail"];
    $studentMajor = $student["Department"];
    $curEvals = "";

    for ($x = 0; $x < 3; $x++) {
        $evaluatorsQuery = $pdo->prepare(
            "SELECT COUNT(REmail) FROM Reviewers WHERE Active = 1 AND Major != :studMajor AND REmail != :aEmail" . $curEvals
        );
        $evaluatorsQuery->bindValue(":studMajor", $studentMajor);
        $evaluatorsQuery->bindValue(":aEmail", $advisorEmail);
        $evaluatorsQuery->execute();
        $evalCount = $evaluatorsQuery->fetch(PDO::FETCH_ASSOC);
        if ($evalCount['COUNT(REmail)'] > 0) {
            $evaluatorsQuery = $pdo->prepare(
                "SELECT REmail FROM Reviewers WHERE Active = 1 AND Major != :studMajor AND REmail != :aEmail" . $curEvals
            );
            $evaluatorsQuery->bindValue(':studMajor', $studentMajor);
            $evaluatorsQuery->bindValue(':aEmail', $advisorEmail);
            $evaluatorsQuery->execute();
        } else {
            $evaluatorsQuery = $pdo->prepare(
                "SELECT COUNT(REmail) FROM Reviewers WHERE Active = 1 AND REmail != :aEmail" . $curEvals
            );
            $evaluatorsQuery->bindValue(':aEmail', $advisorEmail);
            $evaluatorsQuery->execute();
            $evalCount = $evaluatorsQuery->fetch(PDO::FETCH_ASSOC);

            $evaluatorsQuery = $pdo->prepare(
                "SELECT REmail FROM Reviewers WHERE Active = 1 AND REmail != :aEmail" . $curEvals
            );
            $evaluatorsQuery->bindValue(':aEmail', $advisorEmail);
            $evaluatorsQuery->execute();
        }

        $num = rand(0, (int)$evalCount['COUNT(REmail)'] - 1);

        $i = 0;
        $evaluator = $evaluatorsQuery->fetch(PDO::FETCH_ASSOC);
        while ($i < $num) {
            $evaluator = $evaluatorsQuery->fetch(PDO::FETCH_ASSOC);
            $i++;
        }
        $curEvals = $curEvals . " AND REmail != '" . $evaluator['REmail'] . "'";
        $reviewerTableQ = $pdo->prepare(
            "INSERT INTO `$revTableName` (ApplicationNum, REmail, QATotal, Submitted) VALUES(:appNum, :REmail, 0, 0)"
        );
        $reviewerTableQ->bindValue(':appNum', $appNum);
        $reviewerTableQ->bindValue(':REmail', $evaluator['REmail']);
        $reviewerTableQ->execute();
    }
}
//$sth = $pdo->prepare("UPDATE Settings SET DistributedApps = 1 WHERE 1");
//$sth->execute();
//}
header("location:index.php");

?>
