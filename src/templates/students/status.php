<?php

$title = 'View application Status';
helper('application_status_label');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/student.css">
</head>
<body>
<main class="content">
    <h1>Application: <?= e($application->title) ?><br></h1>
    <?= HTML::template('application_details.php', $application) ?>
</main>
</body>
</html>
