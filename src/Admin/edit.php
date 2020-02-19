<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/index.css">

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
    $sth = $pdo->prepare("SELECT RName, Major FROM Reviewers WHERE Active = 1");
    ?>

</head>
<body>

<div class="sidenav">
    <img src="img/ewueagle.png" height=125px; width=185px;>
    <br>
    <br>
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
    <br>
    <br>
    <br>
    <a href="../index.php?logout=true">Logout</a>
</div>

<div class='main'>
    <div class='w3-container'>
        <br>
        <h4>Evaluators</h4>
        <table class='w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white'>
            <?php $sth->execute();
            foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
                <tr>
                    <td><?php echo $row["RName"]; ?></td>
                    <td><?php echo $row["Major"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <button onclick="window.location.href='addreviewer.php'">Edit</button>
        <br>
    </div>
    <br>

    <?php
    $sth = $pdo->prepare(
        "SELECT ApplicationSubmission, ChangesRequested, ApplicationApproval, ApplicationAward FROM Settings"
    );
    $sth->execute();
    $rows = $sth->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class='w3-container'>
        <h4>Auto-generated Emails</h4>
        <table class='w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white'>
            <?php ?>
            <tr>
                <td>Application Submission</td>
                <td><?php echo $rows[ApplicationSubmission]; ?></td>
                <form method="POST">
                    <input type="hidden" value="ApplicationSubmission" name="emailType" id="emailType"/>
                    <td>
                        <button type="submit" formaction="editEmail.php">Edit</button>
                    </td>
                </form>
            </tr>
            <tr>
                <td>Changes Requested</td>
                <td><?php echo $rows[ChangesRequested]; ?></td>
                <form method="POST">
                    <input type="hidden" value="ChangesRequested" name="emailType" id="emailType"/>
                    <td>
                        <button type="submit" formaction="editEmail.php">Edit</button>
                    </td>
                </form>
            </tr>
            <tr>
                <td>Application Approval</td>
                <td><?php echo $rows[ApplicationApproval]; ?></td>
                <form method="POST">
                    <input type="hidden" value="ApplicationApproval" name="emailType" id="emailType"/>
                    <td>
                        <button type="submit" formaction="editEmail.php">Edit</button>
                    </td>
                </form>
            </tr>
            <tr>
                <td>Application Award</td>
                <td><?php echo $rows[ApplicationAward]; ?></td>
                <form method="POST">
                    <input type="hidden" value="ApplicationAward" name="emailType" id="emailType"/>
                    <td>
                        <button type="submit" formaction="editEmail.php">Edit</button>
                    </td>
                </form>
            </tr>
        </table>
    </div>
    <div class='w3-container'>
        <form>
            <button type="submit" formaction="distributeApps.php">Set Evaluators</button>
        </form>
    </div>
</div>
</body>
</html>
  