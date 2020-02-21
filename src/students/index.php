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
    redirect('../infoPages/closedStudent.php');
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

if (!isPost()) {
    $application = DB::selectSingle(
        "$applicationsTable NATURAL JOIN Student NATURAL JOIN Advisor",
        'SID = ?',
        $_SESSION['id']
    );

    if ($application) {
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
}

$errors = [];

function validate($form, $validators)
{
    global $errors;
    $errors = [];

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

function renderError($field)
{
    global $errors;
    return isset($errors[$field]) ? "<div class=\"error\">$errors[$field]</div>" : '';
}

function saveApplication($form, $isSubmitted)
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

    if (DB::contains($applicationsTable, 'SID = ?', $_SESSION['id'])) {
        DB::update($applicationsTable, $application, 'SID = ?', $_SESSION['id']);
        DB::update('Student', $student, 'SID = ?', $_SESSION['id']);
    } else {
        DB::insert($applicationsTable, $application);
        DB::insert('Student', $student);
    }
}

$state = null;

if (post('submit')) {
    if (validate($form, $validators)) {
        saveApplication($form, true);
        email(
            $form['advisor_email'],
            'CSTEM Scholars Grant Application Needs Review',
            render('emails/application.php', $form)
        )->send();
        redirect('ThankYouPage.php');
    }
} elseif (post('save')) {
    // Make all fields optional
    foreach ($validators as $k => $v) {
        $validators[$k] = v::optional($v);
    }

    if (validate($form, $validators)) {
        saveApplication($form, false);
        $state = 'saved';
    } else {
        $state = 'error';
    }
}
?>
<!DOCTYPE HTML>
<html>
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

<form role="form" method="post" action="" enctype="multipart/form-data">
    <div class="form">
        <h1>Grant Fund Application<span>Undergraduate Research</span><span>*All Fields Required</span></h1>
        <form enctype="multipart/form-data">
            <?php if ($state == 'saved') { ?>
                <div class="success message">
                    <h2>Your application has been saved!</h2>
                    <p>You can come back any time before the deadline to update and submit your application. Be sure to
                        have your application submitted and approved by your advisor before
                        <strong><?= $date ?></strong>.</p>
                </div>
            <?php } ?>

            <?php if ($state == 'error') { ?>
                <div class="error message">
                    <h2>We were unable to save your application.</h2>
                    <p>Please review your application for errors and try saving again.</p>
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
                            <option value="<?= $department ?>" <?= $form['department'] == $department ? 'selected' : '' ?>><?= $department ?></option>
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

            <div class="section"><span>6</span>Budget</div>
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
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576"/>
                <label>
                    Upload budget spreadsheet:
                    <input name="file" type="file"
                           accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .pdf"
                           required>
                </label>
            </div>

            <div class="button-section">
                <button type="submit" class="button" name="submit" value='submit'>Submit</button>
                <button type="submit" class="button" name="save" value='save' formnovalidate>Save</button>
                <label class="privacy-policy">
                    <input type="checkbox" name="terms" value="agree" required>
                    <div class="tooltip">I agree to the Terms & Conditions
                        <span class="tooltiptext">Awards shall only be spent on allowable expenses as defined in the application. Receipts must be provided for all expenses including travel. Funds must be spent within one calendar year of dispersal A brief two-page progress report must be submitted to the faculty advisor and associate dean by the end of the project year. Any academic integrity or student code of conduct violations will result in forfeiture the award.</span>
                    </div>
                    <?= renderError('terms') ?>
                </label>
            </div>
        </form>
    </div>
</form>

</body>
</html>
