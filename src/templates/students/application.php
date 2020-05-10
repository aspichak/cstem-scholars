<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type">
    <title>Application Form</title>
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link href="css/students.css" rel="stylesheet">
</head>
<body>

<form>
    <div class="logout">
        <div class="button-section">
            <button type="submit" class="button" name="logout" formaction="../index.php?logout=true">Logout</button>
        </div>
    </div>
</form>

<form method="POST" enctype="multipart/form-data">
    <?= $form->csrf() ?>

    <div class="form">
        <h1>Grant Fund Application<span>Undergraduate Research</span><span>*All Fields Required</span></h1>
            <?php if (HTTP::isPost() && (!$application->isValid() || !$file->isValid())) { ?>
                <div class="error message">
                    <h2>We were unable to submit your application.</h2>
                    <p>Please review your application for errors and try again.</p>
                </div>
            <?php } ?>

            <?php if (HTTP::post('save') && $application->isValid() && $file->isValid()) { ?>
                <div class="success message">
                    <h2>Your application has been saved!</h2>
                    <p>You can come back any time before the deadline to submit your application. Be sure to have your
                        application submitted and approved by your advisor before
                        <strong><?= date("M j, Y", strtotime($period->deadline)) ?></strong>.
                        <strong>
                            Please note that your application is not submitted and will not be reviewed until you do so.
                        </strong>
                    </p>
                </div>
            <?php } ?>

            <div class="section"><span>1</span>Basic Details</div>
            <div class="inner-wrap">
                <label>Your Full Name: <?= $form->input('text', 'name', ['disabled']) ?></label>
                <label>Email Address:  <?= $form->input('text', 'email', ['required']) ?></label>
                <label>Project Title:  <?= $form->input('text', 'title', ['required']) ?></label>
            </div>

            <div class="section"><span>2</span>Major &amp; GPA</div>
            <div class="inner-wrap">
                <label>
                    Your Major: 
                    <?= $form->select('major', array_combine(Application::DEPARTMENTS, Application::DEPARTMENTS)) ?>
                </label>

                <label>
                    GPA:
                    <?= $form->input('number', 'gpa', ['min' => 1, 'max' => 4, 'step' => 0.01, 'required']) ?>
                </label>

                <label>
                    Expected Graduation Date:
                    <?= $form->input('date', 'graduationDate', ['required']) ?>
                </label>
            </div>

            <div class="section"><span>3</span>Advisor Information</div>
            <div class="inner-wrap">
                <label>
                    Advisor Name:
                    <?= $form->input('text', 'advisorName', ['list' => 'advisorNames', 'required']) ?>
                </label>

                <label>
                    Advisor Email:
                    <?= $form->input('text', 'advisorEmail', ['list' => 'advisorEmails', 'required']) ?>
                </label>

                <datalist id="advisorNames">
                    <?php
                    foreach (User::advisors() as $advisor) {
                        echo HTML::tag('option', $advisor->name, ['value' => $advisor->name]);
                    }
                    ?>
                </datalist>

                <datalist id="advisorEmails">
                    <?php
                    foreach (User::advisors() as $advisor) {
                        echo HTML::tag('option', $advisor->email, ['value' => $advisor->email]);
                    }
                    ?>
                </datalist>
            </div>

            <div class="section"><span>4</span>Objective & Results</div>
            <div class="inner-wrap">
                <label>
                    Provide a brief description of the project, including a statement of the problem and/or objective 
                    of the project, an explanation of the importance of the project, and a statement of work that 
                    briefly describes your methodology and expected outcomes. (6000 characters max)
                    <?= $form->textarea('description', ['maxlength' => 6000, 'rows' => 12, 'required']) ?>
                </label>

                <label>
                    Describe your estimated timeline (2000 characters max)
                    <?= $form->textarea('timeline', ['maxlength' => 2000, 'rows' => 6, 'required']) ?>
                </label>
            </div>

            <div class="section"><span>7</span>Budget</div>
            <div class="inner-wrap">
                <label>
                    Describe your budget and planned spending (2000 characters max)
                    <?= $form->textarea('justification', ['maxlength' => 2000, 'rows' => 6, 'required']) ?>
                </label>

                <label>
                    Total budget amount:
                    <?= $form->input('number', 'totalBudget', ['min' => 0, 'step' => 0.01, 'required']) ?>
                </label>

                <label>
                    Requested budget amount from EWU:
                    <?= $form->input(
                        'number',
                        'requestedBudget',
                        ['min' => 0, 'max' => 2000, 'step' => 0.01, 'required']
                    ) ?>
                </label>

                <label>
                    Please list any other funding sources you have:
                    <?= $form->input('text', 'fundingSources', ['required']) ?>
                </label>

                <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                <label for="attachment">Upload budget table</label>
                <p>Valid file types are: <?= implode(', ', ALLOWED_UPLOAD_EXTENSIONS) ?></p>
                <input type="file" name="attachment" id="attachment"
                       accept="<?= implode(',', array_map(fn($ext) => ".$ext", ALLOWED_UPLOAD_EXTENSIONS)) ?>">

                <?php
                if (HTTP::isPost() && !$file->isValid()) {
                    echo HTML::tag('div', $file->error(), ['class' => 'error']);
                }
                ?>

                <?php if ($application->attachment) { ?>
                    <p>
                        <?= HTML::link(
                            "../download.php?file={$application->attachment}",
                            "Download previously uploaded file"
                        ) ?>
                    </p>
                <?php } ?>
            </div>

            <div class="section"><span>8</span>Terms and Conditions</div>
            <div class="inner-wrap">
                <p>
                    Awards shall only be spent on allowable expenses as defined in the application. Receipts must be
                    provided for all expenses including travel. Funds must be spent within one calendar year of
                    dispersal. A brief two-page progress report must be submitted to the faculty advisor and associate
                    dean by the end of the project year. Any academic integrity or student code of conduct violations
                    will result in forfeiture of the award.
                </p>
                <label>
                    <input type="checkbox" name="terms" id="terms" value="agree" required>
                    I agree to the Terms & Conditions
                </label>
                <?= $form->error('terms') ?>
            </div>

            <div class="button-section">
                <button type="submit" class="button" name="submit" value="submit">Submit</button>

                <?php if ($application->status == 'draft') { ?>
                    <button type="submit" class="button" name="save" value="save" formnovalidate>Save and Continue
                        Later
                    </button>
                <?php } ?>
            </div>
    </div>
</form>

</body>
</html>
