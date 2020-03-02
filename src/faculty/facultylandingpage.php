<?php

require_once '../includes/init.php';
authorize('faculty');
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <title>Faculty Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="css/grantRequirements.css">
    <?php
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
    $email = $_SESSION["email"];
    $sth = $pdo->prepare("SELECT Deadline FROM Settings");
    $sth->execute();
    $date_array = $sth->fetch();
    $deadline = $date_array[0];
    $temp = explode("-", $deadline);
    $year = $temp[0];
    $month = $temp[1];
    $tableName = 'Applications' . $month . $year;
    $sth = $pdo->prepare("SELECT ApplicationNum, AdvisorApproved FROM `$tableName` WHERE AEmail ='$email'");
    $sth->execute();
    $applications = $sth->fetchAll();
    $query = $pdo->prepare("SELECT Deadline FROM Settings");
    $query->execute();
    $deadline = $query->fetch();
    ?>

</head>
<body>

<div class="form">
    <form method="POST">
        <input type="hidden" value="ApplicationAward" name="emailType" id="emailType"/>
        <button class="logout" type="submit" formaction="../index.php?logout=true">Logout</button>
    </form>
    <h1>Grant Fund Application<span>Faculty Approval</span></h1>

    <form>
        <h2>All applications must be approved by <?php echo $deadline[0]; ?></h2>
        <h3>Applications Pending Aproval</h3>

        <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
            <tr>
                <th>Student Name</th>
                <th>Project Title</th>
                <th>Student Email</th>
                <th>Application Status</th>
                <th>View Application</th>
            </tr>
            <?php foreach ($applications as $value) {
                $num = $value["AdvisorApproved"];
                if ($num == 1) {
                    $status = "Approved";
                } elseif ($num == 0) {
                    $status = "Needs Approval";
                } elseif ($num == 2) {
                    $status = "Updates Requested";
                } elseif ($num == 3) {
                    $status = "Not Approved";
                } elseif ($num == 4) {
                    $status = "Updated";
                }
                $newValue = $value["ApplicationNum"];
                $temp = $pdo->prepare("SELECT * FROM `$tableName` WHERE ApplicationNum=$newValue");
                $temp->execute();
                $student = $temp->fetch();
                $sid = $student[1];
                $query = $pdo->prepare("SELECT * FROM Student WHERE SID=$sid");
                $query->execute();
                $results = $query->fetch();

                ?>
                <tr>
                    <td><?php echo $results[1]; ?></td>
                    <td><?php echo $student[2]; ?></td>
                    <td><?php echo $results[2]; ?></td>
                    <td><font color=#c70505><?php echo $status ?></font></td>
                    <?php if ($num == 1 || $num == 2 || $num == 3) { ?>
                        <td>
                            <button type="button" disabled>Submitted</button>
                        </td>
                    <?php } else { ?>
                        <td><a href=facultyform.php?id=<?php echo $newValue ?>>
                                <button type="button">View</button></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
        <br>
    </form>

</div>
</body>
</html>
