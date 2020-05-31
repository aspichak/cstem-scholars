<?php

$user = User::current();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title ?> - CSTEM Research Grant Advisors</title>
    <link rel="stylesheet" href="../admin.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector("#menu-button").addEventListener("click", function () {
                document.querySelector("#menu").classList.toggle("shown");
            });
        });
    </script>
</head>
<body>

<button id="menu-button" title="Menu"><i class="icon menu-light"></i> Menu</button>

<nav class="sidebar" id="menu">
    <div class="logo">
        <img src="../images/logo.svg" alt="EWU Logo" width="128" height="128">
    </div>

    <p>Advisor</p>
    <ul>
        <li><a href="../advisors/"><i class="icon check-square-light"></i>Accept Applications</a></li>
    </ul>

    <ul>
        <li><a href="../logout.php"><i class="icon log-out-light"></i>Log out</a></li>
    </ul>
</nav>

<main class="content">
    <?= $content ?>
</main>
</body>
</html>