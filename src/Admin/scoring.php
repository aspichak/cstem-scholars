<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="results.css">
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
    $app = $_GET['id'];

    $sth = $pdo->prepare("SELECT Deadline FROM Settings");
    $sth->execute();
    $date_array = $sth->fetch();
    $deadline = $date_array['Deadline'];
    $temp = explode("-", $deadline);
    $year = $temp[0];
    $month = $temp[1];
    $appTableName = 'Applications' . $month . $year;
    $revTableName = 'ReviewedApps' . $month . $year;

    $sth = $pdo->prepare(
        "SELECT * FROM " . $revTableName . " r1, Reviewers WHERE  Reviewers.REmail = r1.REmail AND ApplicationNum =" . $app
    );
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
    <div class="w3-container">
        <h2>Scoring For Application <?php echo $app ?></h2>
        <?php $sth->execute();
        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
            <form>
                <fieldset>
                    <legend><h1>Reviewer: <?php echo $row['RName'] ?></h1></legend>
                    <b>Does the project demonstrate experiential learning in a CSTEM discipline?</b>
                    <p><?php echo $row['QA1'] ?></p>
                    <br>
                    <b>Is the budget justified in the project description, including realistic?</b>
                    <p><?php echo $row['QA2'] ?></p>
                    <br>
                    <b>Are the proposed methods appropriate to achieve the goals?</b>
                    <p><?php echo $row['QA3'] ?></p>
                    <br>
                    <b>Is the timeline proposed reasonable?(Too little? Too much?)</b>
                    <p><?php echo $row['QA4'] ?></p>

                    <b>Is the project well explained (including rationale) and justified?</b>
                    <p><?php echo $row['QA5'] ?></p>

                    <b>Does the budget only include eligible activities (supplies, equipment, field travel, conference
                        travel)?</b>
                    <p><?php echo $row['QA6'] ?></p>

                    <b>Would you recommend?</b>
                    <p><?php echo $row['FundRecommend'] ?></p>
                    <br>
                    <b>Additional Comments</b>
                    <p><?php echo $row['QAComments'] ?></p>
                    <br>
                </fieldset>
                <br>
            </form>
        <?php endforeach; ?>
        <a href="results.php">
            <button>Back to results</button>
    </div>
</div>
<br>
<br>
</body>
</html>

