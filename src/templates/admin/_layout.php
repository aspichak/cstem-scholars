<?php

$user = User::current();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title ?> - CSTEM Research Grant Admininstrator</title>
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="../app_status.css">
    <script src="/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(function () {
            var activeTab = $("ul.tabs a.active");

            $(".tab h2:first-child").hide();
            $(".tab").hide();
            $(activeTab.attr("href")).show();
        });

        $(document).on("click", "#menu-button", function (e) {
            $("#menu").toggleClass("shown");
        });

        $(document).on("click", "ul.tabs li a", function (e) {
            var activeTab = $(this).parents("ul.tabs").find(".active");

            activeTab.removeClass("active");
            $(activeTab.attr("href")).hide();

            $(this).addClass("active");
            $(this.hash).show();

            e.preventDefault();
        });
    </script>
</head>
<body>

<button id="menu-button" title="Menu"><i class="icon menu-light"></i> Menu</button>

<nav class="sidebar" id="menu">
    <div class="logo">
        <img src="../images/logo.svg" alt="EWU Logo" width="128" height="128">
    </div>

    <?php
    if ($user->isAdvisor()) { ?>
        <p>Advisor</p>
        <ul>
            <li><a href="../advisors/applications.php"><i class="icon check-square-light"></i>Accept Applications</a>
            </li>
        </ul>
        <?php
    } ?>

    <?php
    if ($user->isReviewer()) { ?>
        <p>Reviewer</p>
        <ul>
            <li><a href="../reviewers/"><i class="icon list-light"></i>Review Applications</a></li>
        </ul>
        <?php
    } ?>

    <?php
    if ($user->isAdmin()) { ?>
        <p>Administrator</p>
        <ul>
            <li><a href="../admin/"><i class="icon grid-light"></i>Dashboard</a></li>
            <li><a href="../admin/periods.php"><i class="icon calendar-light"></i>Periods</a></li>
            <li><a href="../admin/applications.php"><i class="icon file-text-light"></i>Applications</a></li>
            <li><a href="../admin/users.php"><i class="icon users-light"></i>Users</a></li>
        </ul>
        <?php
    } ?>

    <ul>
        <li><a href="../logout.php"><i class="icon log-out-light"></i>Log out</a></li>
    </ul>
</nav>

<main class="content">
    <?= $content ?>
</main>
</body>
</html>