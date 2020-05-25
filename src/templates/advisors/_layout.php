<?php

$user = User::current();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title ?> - CSTEM Research Grant Advisor</title>
    <link rel="stylesheet" href="https://newcss.net/lite.css">
    <style>
        button.delete {
            background-color: #b30f23;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            border-color: #ccc;
            display: block;
        }

        form.delete {
            display: inline-block;
        }

        a.edit {
            padding-right: 0.5em;
        }

        pre {
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
<header>
    <!-- Header content -->
</header>

<nav class="sidebar">
    <?php if ($user->isAdvisor()) { ?>
        <p>Advisor</p>
        <ul>
            <li><a href="../advisors/">Dashboard</a></li>
        </ul>
    <?php } ?>

    <?php if ($user->isReviewer()) { ?>
        <p>Reviewer</p>
        <ul>
            <li><a href="../reviewers/">Dashboard</a></li>
        </ul>
    <?php } ?>

    <?php if ($user->isAdmin()) { ?>
        <p>Admin</p>
        <ul>
            <li><a href="../admin/">Dashboard</a></li>
            <li><a href="../admin/periods.php">Periods</a></li>
            <li><a href="../admin/applications.php">Applications</a></li>
            <li><a href="../admin/users.php">Users</a></li>
        </ul>
    <?php } ?>

    <ul>
        <li><a href="../logout.php">Log out</a></li>
    </ul>
</nav>

<section class="content">
    <?= $content ?>
</section>

<footer>
    <!-- Footer content -->
</footer>
</body>
</html>