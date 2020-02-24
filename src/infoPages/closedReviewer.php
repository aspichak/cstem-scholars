<!DOCTYPE html>
<html lang="en">

<title>Application Reviewal</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/infoPage.css">
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
$s = $pdo->query("SELECT Deadline FROM Settings");
$date_array = $s->fetch();
$deadline = $date_array['Deadline'];
$date = date("M j, Y", strtotime($deadline));
?>
<body>

<!-- Navbar (sit on top) -->

<!-- First Parallax Image with Logo Text -->
<form method="POST">
    <?php include_once 'infoHeader.php';?>
    <!-- Container (About Section) -->
    <div class="w3-content w3-container w3-padding-32" id="about">
        <h3 class="w3-center">The grant application is still open to students. Check back after <?php echo $date ?> for
            new applications to review.</h3>
        <p></p>
    </div>

    <div class="w3-content w3-container w3-padding-8" id="apply">
    </div>
    <div style="text-align: center;">
        <a href="../index.php" class="w3-button w3-grey w3-round w3-large" id="student">Back To Home Page</a>
        <br><br>
    </div>
    <!-- Footer -->
    <!--<footer class="w3-center w3-black w3-padding-64 w3-opacity ">
      <a href="#home" class="w3-button w3-light-grey"><i class="fa fa-arrow-up w3-margin-right"></i>To the top</a>
    </footer>-->
    <?php include_once 'infoFooter.php';?>
</form>
</body>
</html>
