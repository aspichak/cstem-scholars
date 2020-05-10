<?php

require_once '../includes/init.php';

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

authorize('student');

$deadline = DB::selectSingle("Settings")['Deadline'];
$date = date("M j, Y", strtotime($deadline));
$temp = explode('-', $deadline);
$year = $temp[0];
$month = $temp[1];
$applicationsTable = 'Applications' . $month . $year;

if (strtotime('today') > strtotime($deadline)) {
    error(
        'Student Application',
        'The CSTEM Research Grant application has been closed. Please check back at a later date.',
        204
    );
}

$departments = [
    'Biology',
    'Chemistry',
    'Computer Science',
    'Design',
    'Engineering',
    'Environmental Science',
    'Geology',
    'Mathematics',
    'Natural Science',
    'Physics'
];

$application = DB::selectSingle(
    "$applicationsTable NATURAL JOIN Student NATURAL LEFT JOIN Advisor",
    'SID = ?',
    $_SESSION['id']
);

function validateAdvisorEmail($email)
{
    return DB::contains('Advisor', 'AEmail = ?', $email);
}

$validators = [
    // Basic Details
    'name' => v::length(3, 50)->setName('Name'),
    'email' => v::email()->length(null, 50)->setName('Email address'),
    'project' => v::length(3, 140)->setName('Project title'),

    // Major & GPA
    'department' => v::in($departments)->setTemplate('Invalid department'),
    'major' => v::length(2, 50)->setName('Major'),
    'gpa' => v::numeric()->min(2.0)->max(4.0)->setName('GPA'),
    'egd' => v::date()->between('+0 today', '+10 years')->setName('Expected Graduation Date'),

    // Advisor Information
    'advisor' => v::alwaysValid(),
    'advisor_email' => v::callback('validateAdvisorEmail')->setTemplate(
        'There doesn\'t appear to be an advisor associated with that email'
    ),

    // Objective & Results
    'objective' => v::length(3, 6000)->setName('Objective'),
    'results' => v::length(3, 6000)->setName('Anticipated results'),

    // Timeline
    'timeline' => v::length(3, 2000)->setName('Budget description'),

    // Budget
    'justification' => v::length(3, 2000)->setName('Budget description'),
    'budget' => v::numeric()->min(0)->setName('Budget amount'),
    'request' => v::numeric()->min(0)->max(2000)->setName('Requested amount'),
    'sources' => v::length(3, 140)->setName('Funding sources'),

    'terms' => v::equals('agree')->setTemplate('You must agree to Terms and Conditions')
];

$form = [];

// Get HTML-safe values of all form fields
foreach (array_keys($validators) as $field) {
    $form[$field] = trim(htmlentities(post($field)));
}

// Fill out fields from a saved application
if (!isPost() && $application) {
    $form = [
        'project' => $application['PTitle'],
        'objective' => $application['Objective'],
        'timeline' => $application['Timeline'],
        'budget' => $application['Budget'],
        'request' => $application['RequestedBudget'],
        'sources' => $application['FundingSources'],
        'results' => $application['Anticipatedresults'],
        'justification' => $application['Justification'],
        'advisor' => $application['AName'],
        'advisor_email' => $application['AEmail'],
        'name' => $application['SName'],
        'email' => $application['SEmail'],
        'gpa' => $application['GPA'],
        'department' => $application['Department'],
        'major' => $application['Major'],
        'egd' => $application['GraduationDate']
    ];
}

$errors = [];

function validateForm($form, $validators)
{
    global $errors;

    // Validate all form fields
    foreach ($validators as $k => $v) {
        try {
            $v->check($form[$k]);
        } catch (ValidationException $exception) {
            $errors[$k] = $exception->getMessage();
        }
    }

    return empty($errors);
}

function validateFileUpload($isOptional)
{
    global $errors;

    switch ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) {
        case UPLOAD_ERR_OK:
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!in_array($fileExtension, ALLOWED_UPLOAD_EXTENSIONS)) {
                $errors['file'] =
                    'This file type is not allowed. Accepted file types are: ' .
                    implode(', ', ALLOWED_UPLOAD_EXTENSIONS);

                return false;
            }

            if ($_FILES['file']['size'] > 1048576) {
                $errors['file'] = 'Maximum file size is 1 MB';
                return false;
            }

            return true;
        case UPLOAD_ERR_NO_FILE:
            if (!$isOptional) {
                $errors['file'] = 'Budget file is required';
            }

            return $isOptional;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errors['file'] = 'Exceeded file size limit';
            return false;
        default:
            $errors['file'] = 'Unknown error';
            return false;
    }
}

function renderError($field)
{
    global $errors;
    return isset($errors[$field]) ? "<div class=\"error\">$errors[$field]</div>" : '';
}

function saveApplication($form, &$existingUploadFileName, $isSubmitted)
{
    global $applicationsTable;

    $application = [
        'SID' => $_SESSION['id'],
        'PTitle' => $form['project'],
        'Objective' => $form['objective'],
        'Timeline' => $form['timeline'],
        'Budget' => $form['budget'],
        'RequestedBudget' => $form['request'],
        'FundingSources' => $form['sources'],
        'Anticipatedresults' => $form['results'],
        'Justification' => $form['justification'],
        'AEmail' => $form['advisor_email'],
        'Submitted' => $isSubmitted ? 1 : 0
    ];

    $student = [
        'SID' => $_SESSION['id'],
        'SName' => $form['name'],
        'SEmail' => $form['email'],
        'GPA' => $form['gpa'],
        'Department' => $form['department'],
        'Major' => $form['major'],
        'GraduationDate' => $form['egd']
    ];

    if (($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) == UPLOAD_ERR_OK) {
        $oldFilePath = UPLOAD_DIR . '/' . $existingUploadFileName;

        if (is_file($oldFilePath)) {
            unlink($oldFilePath);
        }

        $tempFilePath = $_FILES['file']['tmp_name'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $newFileName = date('Y-m-d') . '-' . $_SESSION['id'] . '.' . $ext;
        $newFilePath = UPLOAD_DIR . '/' . $newFileName;

        move_uploaded_file($tempFilePath, $newFilePath);
        $application['BudgetFilePath'] = $newFileName;

        $existingUploadFileName = $newFileName;
    }

    DB::insertOrUpdate($applicationsTable, $application, 'SID = ?', $_SESSION['id']);
    DB::replace('Student', $student);
}

$state = null;

if (post('submit')) {
    // Add extra validations not used in 'save'
    $validators['budget']->min(20);
    $validators['request']->min(20);

    if (validateForm($form, $validators) && validateFileUpload(!empty($application['BudgetFilePath']))) {
        saveApplication($form, $application['BudgetFilePath'], true);

        $form['deadline'] = $date;

        // Email the advisor
        email(
            $form['advisor_email'],
            'CSTEM Scholars Grant Application Needs Review',
            render('emails/application_submitted_advisor.php', $form)
        )->send();

        // Email the student
        email(
            $form['email'],
            'CSTEM Scholars Grant Application Submitted',
            render('emails/application_submitted_student.php', $form)
        )->send();

        redirect('ThankYouPage.php');
    } else {
        $state = 'error-submit';
    }
} elseif (post('save')) {
    // Make all fields optional
    foreach ($validators as $k => $v) {
        $validators[$k] = v::optional($v);
    }

    if (validateForm($form, $validators) && validateFileUpload(true)) {
        saveApplication($form, $application['BudgetFilePath'], $application['Submitted'] ?? false);
        $state = 'saved';
    } else {
        $state = 'error-save';
    }
} elseif( post('increment') ) {
    echo "button clicked";
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type">
    <title>Application Form</title>
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link href="css/students.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="./script.js"></script>

</head>
<body>

<form>
    <div class="logout">
        <div class="button-section">
            <button type="submit" class="button" name="logout" formaction="../index.php?logout=true">Logout</button>
        </div>
    </div>
</form>

<!-- start of form container -->

<form role="form" method="post" action="" enctype="multipart/form-data">
    <div class="form">
        <h1>Grant Fund Application<span>Undergraduate Research</span><span>*All Fields Required</span></h1>
        <div enctype="multipart/form-data">
            <?php if ($state == 'saved') { ?>
                <div class="success message">
                    <h2>Your application has been saved!</h2>
                    <p>You can come back any time before the deadline to update and submit your application. Be sure to
                        have your application submitted and approved by your advisor before
                        <strong><?= $date ?></strong>.</p>
                </div>
            <?php } ?>

            <?php if ($state == 'error-save') { ?>
                <div class="error message">
                    <h2>We were unable to save your application.</h2>
                    <p>Please review your application for errors and try saving again.</p>
                </div>
            <?php } ?>

            <?php if ($state == 'error-submit') { ?>
                <div class="error message">
                    <h2>We were unable to submit your application.</h2>
                    <p>Please review your application for errors and try submitting again.</p>
                </div>
            <?php } ?>

            <div class="section"><span>1</span>Basic Details</div>
            <div class="inner-wrap">
                <label>
                    Your Full Name
                    <?= renderError('name') ?>
                    <input type="text" name="name" value="<?= $form['name'] ?>" required>
                </label>
                <label>
                    Email Address
                    <?= renderError('email') ?>
                    <input type="email" name="email" value="<?= $form['email'] ?>" required>
                </label>
                <label>
                    Project Title
                    <?= renderError('project') ?>
                    <input type="text" name="project" value="<?= $form['project'] ?>" required>
                </label>
            </div>

            <div class="section"><span>2</span>Major &amp; GPA</div>
            <div class="inner-wrap">
                <label>
                    Department
                    <?= renderError('department') ?>
                    <select name="department">
                        <option value="" disabled <?= empty($form['department']) ? 'selected' : '' ?>>----Select
                            Department----
                        </option>
                        <?php foreach ($departments as $department) { ?>
                            <option value="<?= $department ?>" <?= $form['department'] == $department ? 'selected' : '' ?>>
                                <?= $department ?></option>
                        <?php } ?>
                    </select>
                </label>

                <label>
                    Major
                    <?= renderError('major') ?>
                    <input type="text" name="major" value="<?= $form['major'] ?>" required>
                </label>
                <label>
                    GPA
                    <?= renderError('gpa') ?>
                    <input type="text" name="gpa" value="<?= $form['gpa'] ?>" required>
                </label>
                <label>
                    Expected Graduation Date
                    <?= renderError('egd') ?>
                    <input type="date" name="egd" value="<?= $form['egd'] ?>" required>
                </label>
            </div>

            <div class="section"><span>3</span>Advisor Information</div>
            <div class="inner-wrap">
                <label>
                    Advisor Name
                    <?= renderError('advisor') ?>
                    <input type="text" name="advisor" value="<?= $form['advisor'] ?>" required>
                </label>
                <label>
                    Advisor Email
                    <?= renderError('advisor_email') ?>
                    <input type="email" name="advisor_email" value="<?= $form['advisor_email'] ?>"
                           pattern="^([a-zA-Z0-9_\-\.]+)@ewu.edu$" title="someone@ewu.edu" required>
                </label>
            </div>

            <div class="section"><span>4</span>Objective & Results</div>
            <div class="inner-wrap">
                <label>
                    Please describe your objective in less than 6000 characters:
                    <?= renderError('objective') ?>
                    <textarea name="objective" maxlength="6000" rows="7" required><?= $form['objective'] ?></textarea>
                </label>
                <label>
                    Please describe your anticipated results in less than 6000 characters:
                    <?= renderError('results') ?>
                    <textarea name="results" maxlength="6000" rows="7" required><?= $form['results'] ?></textarea>
                </label>
            </div>

            <div class="section"><span>5</span>Timeline</div>
            <div class="inner-wrap">
                <label>
                    Please describe your estimated timeline in less than 2000 characters:
                    <?= renderError('timeline') ?>
                    <textarea name="timeline" maxlength="2000" rows="4" required><?= $form['timeline'] ?></textarea>
                </label>
            </div>
                <!-- START BUDGET CONTAINER -->
                <div class="section"><span>6</span>Budget</div>
                <!--<form class="inner-wrap">-->
                <div class="inner-wrap">
                    <label>
                        Please describe your budget and planned spending in less than 2000 characters:
                        <?= renderError('justification') ?>
                        <textarea name="justification" maxlength="2000" required><?= $form['justification'] ?></textarea>
                    </label>
                    <label>
                        Total budget amount:
                        <?= renderError('budget') ?>
                        <input type="number" step="0.01" name="budget" value="<?= $form['budget'] ?>" required>
                    </label>
                    <label>
                        Requested budget amount from EWU:
                        <?= renderError('request') ?>
                        <input type="number" step="0.01" name="request" value="<?= $form['request'] ?>" required>
                    </label>
                    <label>
                        Please list any other funding sources you have:
                        <?= renderError('sources') ?>
                        <input type="text" name="sources" value="<?= $form['sources'] ?>" required>
                    </label>
<!--adding-->
<!--done adding-->
                    <!-- THIS IS WHERE USER INPUT FOR BUDGET SHEET GOES -->
                    <label>
                        <div id="table">

                        </div>
                        <div id="btn">
                            <button id="increment" >+</button>
                        </div>
                    </label>
<!--adding-->
<!--done adding-->
                    <!-- CHECK THE SIZE OF THE CONTAINER FOR ENTIRE BUDGET SECTION-->
                    <div class="button-section" align = "left">
                        <button type="submit" class="button" name="submit" value='submit'>Submit</button>
                        <button type="submit" class="button" name="save" value='save' formnovalidate>Save</button>
                        <label class="privacy-policy">
                            <!-- why is this popping up when clicking increment button 1620 5/9-->
                            <input type="checkbox" name="terms" value="agree" required>
                            <div class="tooltip">I agree to the Terms & Conditions
                                <span class="tooltiptext">Awards shall only be spent on allowable expenses as defined in the application. Receipts must be provided for all expenses including travel. Funds must be spent within one calendar year of dispersal A brief two-page progress report must be submitted to the faculty advisor and associate dean by the end of the project year. Any academic integrity or student code of conduct violations will result in forfeiture the award.</span>
                            </div>
                            <?= renderError('terms') ?>
                        </label>
                    </div>
            </div>
        </form>
    </div>
</form>

</body>
</html>
