<?= render('emails/header.php') ?>

<p>Hello <?= $v['name'] ?>, </p>
<p>Your CSTEM Scholars application was successfully submitted. You may go back and make changes to your application at
    any time before the <?= $v['deadline'] ?> deadline.</p>

<?= render('emails/footer.php') ?>
