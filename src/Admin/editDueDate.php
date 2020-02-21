<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/award.css">
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
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    $sth = $pdo->prepare("SELECT * FROM Settings");
    $sth->execute();
    $result = $sth->fetch();
    $begin = $result[3];
    $end = $result[1];
    $budget = $result[4];
    $deadline = $_REQUEST['newDue'];
    $year = date("Y-m-d", strtotime($deadline . "+ 7 day"));

    $sth = $pdo->prepare("UPDATE Settings SET Deadline = '$deadline', AdvisorDeadline='$year' WHERE 1");
    $sth->execute();
    $temp = explode("-", $deadline);
    $year = $temp[0];
    $month = $temp[1];
    $appName = 'Applications' . $month . $year;
    $reviewedName = 'ReviewedApps' . $month . $year;
    $temp = explode("-", $end);
    $oldYear = $temp[0];
    $oldMonth = $temp[1];
    $oldApp = 'Applications' . $oldMonth . $oldYear;
    $oldReviewed = 'ReviewedApps' . $oldMonth . $oldYear;
    $sth = $pdo->prepare(
        "RENAME TABLE  " . $oldApp . " TO " . $appName . ", " . $oldReviewed . " TO " . $reviewedName . ";"
    );
    $sth->execute();
    date_default_timezone_set('America/Boise');
    $today = date('Y-m-d');
    $end = ($result[1]);

    ?>
</head>
<body>
<form method="post">
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
            <h1>Begin a new Scholarship Process</h1>
            <h4> By beginning a new Scholarship process you'll make all data currently stored historical and can no
                longer select any awardee's</h4>
            <fieldset>
                <legend>New Scholarship Dates</legend>
                <div>
                    <label for="start">Begin Date</label>
                    <input type="date" min=<?php echo $today ?>id="start" name="start"
                           value= <?php echo htmlspecialchars($begin); ?>/>
                </div>
                <div>
                    <label for="end">Deadline</label>
                    <input type="date" min=<?php echo $today ?> id="end" name="end"
                           value= <?php echo htmlspecialchars($deadline); ?>/>
                </div>
                <button type="submit" <?php if ($today < $end) {
                    echo "disabled";
                } ?> formaction="NewSuccessful.php">Begin New Process
                </button>
            </fieldset>
            <br>
        </div>
        <br>
        <div class="w3-container">
            <h2>Edit Current </h2>
            <br>
            <fieldset>
                <legend>Total Budget</legend>
                <div>
                    <label for="budget">Budget</label>
                    <input type="number" min="0" id="budget" name="budget"
                           value= <?php echo $budget; ?>/>
                </div>
                <button type="submit" formaction="editBudget.php">Submit</button>

            </fieldset>
        </div>
        <br>
        <div class="w3-container">
            <br>
            <fieldset>
                <legend>Extend Due Date</legend>
                <div>
                    <label for="newDue">New Deadline</label>
                    <input type="date" min=<?php echo $today ?> id="newDue" name="newDue"
                           value= <?php echo htmlspecialchars($deadline); ?>/>
                    <h5> New due date set </h5>
                </div>
                <br>
                <button type="submit" formaction="editDueDate.php?newDue="<?php echo $_REQUEST['newDue'] ?>>Submit
                </button>
            </fieldset>
        </div>
    </div>
</form>
</body>
</html>
