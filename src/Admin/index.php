<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
//		throw new \PDOException($e->getMessage(), (int)$e->getCode());
        throw $e; // should be a cleaner rethrow
    }
    $sth = $pdo->prepare("SELECT Deadline FROM Settings");
    $sth->execute();
    $date_array = $sth->fetch();
    $deadline = $date_array['Deadline'];
    $temp = explode("-", $deadline);
    $year = $temp[0];
    $month = $temp[1];
    $tableName = 'Applications' . $month . $year;
    $sth = $pdo->prepare("SELECT COUNT(ApplicationNum) FROM " . $tableName . " ");
    $sth->execute();
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    ?>
</head>
<body>
<div class="sidenav">
    <img src="img/ewueagle.png" height=125px; width=185px;>
    <br><br>
    <a href="index.php">Home</a>
    <br>
    <a href="edit.php">Edit</a>
    <br>
    <a href="results.php">Results</a>
    <br>
    <a href="prior.php">Prior Awards</a>
    <br>
    <a href="search.php">Search</a>
    <br>
    <a href="new.php">New</a>
    <br><br><br>
    <a href="../index.php?logout=true">Logout</a>
</div>
<div class="main">
    <br>
    <br>
    <div class='w3-row-padding w3-margin-bottom'>
        <div class='w3-third'>
            <div class='w3-container w3-red w3-padding-16'>
                <div class='w3-left'><i class='fa fa-comment w3-xxxlarge'></i></div>
                <div class='w3-right'>
                    <h3>
                        <?php echo $row['COUNT(ApplicationNum)']; ?>
                    </h3>
                </div>
                <div class='w3-clear'></div>
                <h4>Applications In Progress</h4>
            </div>
        </div>
        <div class='w3-third'>
            <div class='w3-container w3-dark-grey w3-padding-16'>
                <div class='w3-left'><i class='fa fa-eye w3-xxxlarge'></i></div>
                <div class='w3-right'>
                    <h3>
                        <?php
                        $sth = $pdo->prepare(
                            "SELECT COUNT(ApplicationNum) FROM " . $tableName . " WHERE Submitted = 1"
                        );
                        $sth->execute();
                        $row = $sth->fetch(PDO::FETCH_ASSOC);
                        echo $row['COUNT(ApplicationNum)'];
                        ?>
                    </h3>
                </div>
                <div class='w3-clear'></div>
                <h4>Applications Submitted</h4>
            </div>
        </div>
        <div class='w3-third'>
            <div class='w3-container w3-light-grey w3-padding-16'>
                <div class='w3-left'><i class='fa fa-share-alt w3-xxxlarge'></i></div>
                <div class='w3-right'>
                    <h3>
                        <?php
                        $sth = $pdo->prepare(
                            "SELECT COUNT(A1.ApplicationNum) FROM " . $tableName . " A1 WHERE Submitted = 1 AND AdvisorApproved = 1"
                        );
                        $sth->execute();
                        $row = $sth->fetch(PDO::FETCH_ASSOC);
                        echo $row['COUNT(A1.ApplicationNum)'];
                        ?>
                    </h3>
                </div>
                <div class='w3-clear'></div>
                <h4>Applications Completed</h4>
            </div>
        </div>
    </div>
    <br>
    <div class='w3-container'>
        <h5>Current Completed Applications</h5>
        <table class='w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white'>
            <tr>
                <td>
                    <b>Student Name</b>
                </td>
                <td>
                    <b>Project Title</b>
                </td>
                <td>
                    <b>Major</b>
                </td>
            </tr>
            <?php
            $sth = $pdo->prepare(
                "SELECT A3.SName, A2.PTitle, A3.Major FROM " . $tableName . " A2, Student A3 WHERE AdvisorApproved = 1 AND A3.SID = A2.SID AND A2.Submitted = 1"
            );
            $sth->execute();
            foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row):?>
                <tr>
                    <td><?php echo $row['SName']; ?></td>
                    <td><?php echo $row['PTitle']; ?></td>
                    <td><?php echo $row['Major']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
    </div>
</div>
<br>
</body>
</html> 