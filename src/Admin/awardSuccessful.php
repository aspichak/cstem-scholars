<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/award.css">
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

    $app = $_POST['app'];
    $sql = $pdo->prepare("SELECT Budget FROM Settings");
    $sql->execute();
    $result = $sql->fetch();
    $remaining = $result[0] - $_POST['amount'];
    $amount = $_POST['amount'];
    $sth = $pdo->prepare("SELECT Deadline FROM Settings");
    $sth->execute();
    $date_array = $sth->fetch();
    $deadline = $date_array['Deadline'];
    $temp = explode("-", $deadline);
    $year = $temp[0];
    $month = $temp[1];
    $tableName = 'Applications' . $month . $year;
    $sth = $pdo->prepare(
        "UPDATE " . $tableName . " SET Awarded =1, AmountGranted = " . $amount . " WHERE ApplicationNum = " . $app
    );
    $sth->execute();
    ?>
</head>
<body>
<form action="results.php" method="post">
    <?php include_once 'sidenav.php'; ?>
    <div class="main">
        <div class="w3-container">
            <h1>Award</h1>
            <h4> To award this student, select an amount and click confirm.</h4>
            <fieldset>
                <legend>Award</legend>
                <div>
                    <label for="amountRemaining">Remaining Amount</label>
                    <input type="number" id="amountRemaining" name="amountRemaining" disabled
                           value= <?php echo $remaining; ?>/>
                </div>
                <div>
                    <label for="amount">Award Amount</label>
                    <input type="number" id="amount" name="amount"
                           value= <?php echo $amount ?>/>
                </div>
            </fieldset>
            <br><br>
            <h2>Award Sucessful</h2>
            <button disabled>Confirm</button>
            <button>Back to Results</button>
            <br>
        </div>
    </div>
</form>
</body>
</html>