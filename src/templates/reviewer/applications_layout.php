<?php

helper('application_status_label');

$title = 'Applications';
$layout = 'admin/_layout.php';
?>

<h1>Applications</h1>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Advisor</th>
    <th>Status</th>
    </thead>
    <!--
        $reviews: list of all reviews from 'Review::all('reviewerID = ?', User::current()->email)' in applications.php
        $apps:    list of all applications from 'Application::all()' in applications.php
    -->
    <?php foreach ($reviews as $r) { ?>
        <?php if( $r->submitted == 0 ){ ?>
            <?php
                $a = null;
                $appID = $r->applicationID ;
                foreach( $apps as $app ){
                    if( $app->id == $appID ){
                        $a = $app;
                    }
                }
            ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../reviewers/applications.php?id={$r->id}", e($a->title)) ?></td>
            <td><?= e($a->advisorName) ?></td>
            <td><?= applicationStatus($a) ?></td>
        </tr>
        <?php } ?>
    <?php } ?>
</table>