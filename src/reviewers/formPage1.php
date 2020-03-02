<!DOCTYPE html>
<html lang="en">
<?php
require_once '../includes/init.php';

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

// Getting deadline so we can access correct database table
$deadline = DB::selectSingle("Settings")['Deadline'];
$date = date("M j, Y", strtotime($deadline));
$temp = explode('-', $deadline);
$year = $temp[0];
$month = $temp[1];
$reviewedApplicationsTable = 'reviewedapps' . $month . $year;

// Store Application Number into $_SESSION so we can access later. Disappears otherwise.
if( post('appNum') ){
    $_SESSION['revAppID'] = post('appNum');
}

$validators = [
    // Questions
    'learn' => v::numeric()->min(0)->max(3)->setName('learn'),
    'justified' => v::numeric()->min(0)->max(3)->setName('justified'),
    'method' => v::numeric()->min(0)->max(3)->setName('method'),
    'time' => v::numeric()->min(0)->max(3)->setName('time'),
    'project' => v::numeric()->min(0)->max(3)->setName('project'),
    'budget' => v::numeric()->min(0)->max(3)->setName('budget'),

    // Reviewer point total
    'QATotal' => v::numeric()->min(0)->max(18)->setName('QATotal'),

    // Recommendation to Not Fund, Partially Fund, or Fully Fund
    'fund' => v::numeric()->min(0)->max(2)->setName('fund'),

    // Additional comments the reviewer has
    'qual_comments' => v::length(3, 6000)->setName('Comments')

];

$form = [];

// Get HTML-safe values of all form fields
foreach (array_keys($validators) as $field) {
    $form[$field] = trim(htmlentities(post($field)));
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

function saveReview($form, $isSubmitted)
{
    global $reviewedApplicationsTable;

    // array representing form that is submitted to DB
    $reviewedApplication = [
        'QA1' => $form['learn'],
        'QA2' => $form['justified'],
        'QA3' => $form['method'],
        'QA4' => $form['time'],
        'QA5' => $form['project'],
        'QA6' => $form['budget'],
        'QATotal' => $form['QATotal'],
        'FundRecommend' => $form['fund'],
        'QAComments' => $form['qual_comments'],
        'Submitted' => $isSubmitted ? 1 : 0
    ];

    DB::update($reviewedApplicationsTable, $reviewedApplication, 'ApplicationNum = ?', $_SESSION['revAppID'] );
}

$state = null;

#Get the Application number to display in HTML later
$app_id = post('appNum');

#Ensures no error pops up when form is first loaded since Q1-Q6 will be empty
if( $form['learn'] != '' ) {
    $form['QATotal'] = $form['learn'] + $form['justified'] + $form['method'] + $form['time'] + $form['project'] + $form['budget'];
    if (validate($form, $validators)) {
        saveReview($form, true);
        redirect('ReviewStudents.php');
    }
}

?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
</head>
<body>
<div class="container">
    <div class="imagebg"></div>
    <div class="row " style="margin-top: 50px">
        <div class="col-md-6 col-md-offset-3 form-container">
            <h2>Feedback</h2>
            <form role="form" method="post" id="reused_form">
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <input type="hidden" name="app" value=<?php echo $app_id; ?>/>
                        <label>Please Verify Application ID: <?php echo $app_id; ?></label>
                        <label>Does the project demonstrate experiential learning in a CSTEM discipline?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="learn" value="0">0</label>
                            <label class="radio-inline"><input type="radio" name="learn" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="learn" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="learn" value="3">3</label>
                        </p>
                        <label>Is the budget justified in the project description, including realistic?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="justified" value="0">0</label>
                            <label class="radio-inline"><input type="radio" name="justified" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="justified" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="justified" value="3">3</label>
                        </p>
                        <label>Are the proposed methods appropriate to achieve the goals?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="method" value="0">0</label>
                            <label class="radio-inline"><input type="radio" name="method" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="method" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="method" value="3">3</label>
                        </p>
                        <label>Is the timeline proposed reasonable?(Too little? Too much?)</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="time" value="0">0</label>
                            <label class="radio-inline"><input type="radio" name="time" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="time" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="time" value="3">3</label>
                        </p>
                        <label>Is the project well explained (including rationale) and justified?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="project" value="0">0</label>
                            <label class="radio-inline"><input type="radio" name="project" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="project" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="project" value="3">3</label>
                        </p>
                        <label>Does the budget only include eligible activities (supplies, equipment, field travel,
                            conference travel)?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="budget" value="0">0</label>
                            <label class="radio-inline"><input type="radio" name="budget" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="budget" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="budget" value="3">3</label>
                        </p>
                        <label>Based on eligibility and quality scores, RECOMMEND one of the following
                            categories</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="fund" value="0">Do Not
                                Fund</label>
                            <label class="radio-inline"><input type="radio" name="fund" value="1">Fund if
                                Possible</label>
                            <label class="radio-inline"><input type="radio" name="fund" value="2">Fund</label>
                        </p>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="comments"> Quality Assessment Comments:</label>
                                <textarea class="form-control" type="textarea" name="qual_comments"
                                          placeholder="Your Comments" maxlength="6000" rows="7" style="resize: none" ></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <button type="submit" class="button" name="submitButton"
                                        onclick="return confirm('Are you sure you want to submit?')"
                                        >Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

