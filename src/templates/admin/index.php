<?php

$title = 'Administrator Dashboard';
$layout = 'admin/_layout.php';
?>

<h1>Administrator Dashboard</h1>

<p>This page will display <a href="https://www.pivotaltracker.com/story/show/172750322">various alerts</a>, such as:</p>
<ul>
    <li>Applications signed-off by an advisor but without assigned reviewers</li>
    <li>Applications with an invalid advisor email (should never happen)</li>
    <li>Email send errors (if mail queue is ever implemented)</li>
    <li>Applications that are ready for the final award/reject decision</li>
    <li>Applications not reviewed after advisor deadline</li>
</ul>
