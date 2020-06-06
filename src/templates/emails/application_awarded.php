<?php

$layout = 'emails/layout.php';
?>

<p>Hello <?= e($application->name) ?>, </p>
<p>
    Your application, <?= e($application->title) ?>, was awarded $<?= $application->amountAwarded ?>.
</p>
<pre><?= $message ?></pre>
