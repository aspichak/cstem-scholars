<?= render('emails/header.php') ?>

<p>Hello <?= $v['advisor'] ?>, </p>
<p>
    A CSTEM Scholars application is available for your review. Go to
    <a href="<?= url('reviewers/ReviewStudents.php') ?>"><?= url('reviewers/ReviewStudents.php') ?></a>
    to review it. Here are the details:
</p>

<div class="label">Name:</div>
<p><?= $v['name'] ?> &lt;<a href="mailto:<?= $v['email'] ?>"><?= $v['email'] ?></a>&gt;</p>

<div class="label">Project Title:</div>
<p><?= $v['project'] ?></p>

<div class="label">Major:</div>
<p><?= $v['major'] ?></p>

<div class="label">GPA:</div>
<p><?= $v['gpa'] ?></p>

<div class="label">Expected Graduation Date:</div>
<p><?= $v['egd'] ?></p>

<div class="label">Objective:</div>
<pre><?= $v['objective'] ?></pre>

<div class="label">Anticipated Results:</div>
<pre><?= $v['results'] ?></pre>

<div class="label">Timeline:</div>
<pre><?= $v['timeline'] ?></pre>

<div class="label">Budget Description:</div>
<pre><?= $v['justification'] ?></pre>

<div class="label">Requested Budget:</div>
<p>$<?= $v['request'] ?></p>

<?= render('emails/footer.php') ?>
