<?php

require_once '../../init.php';

User::authorize('advisor');

function UniqueRandomNumbersWithinRange($min, $max, $quantity)
{
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}
$email = User::current()->email;
$applications = Application::all('advisorEmail = ? AND status = \'submitted\'', $email);

$c = new ModelController(Application::class);

// grabbing all applications assigned to an advisor that are only submitted
$c->index('advisors/applications.php', ['applications' => $applications]);
$c->read();

// update block
if ($c->action() == 'update') {
    // TODO: assign reviewers and send emails
    $m = $c->model();
    $reviewers = User::reviewersNotCurrentUser()->fetchAll();

    if (User::current()->isReviewer() && count($reviewers) == 2) {
        $reviewers = User::reviewers()->fetchAll();
    }
    if (count($reviewers) < 3) {
        return;
    }
    // we should always have 3 or more reviewers here
    if (count($reviewers) == 3) {
        $r1 = Review(
            [
                'periodID' => Period::current()->id,
                'reviewerID' => $reviewers[0],
                'applicationID' => $c->model()['id'],
            ], true
        );
        $r2 = Review(
            [
                'periodID' => Period::current()->id,
                'reviewerID' => $reviewers[1],
                'applicationID' => $c->model()['id'],
            ], true
        );
        $r3 = Review(
            [
                'periodID' => Period::current()->id,
                'reviewerID' => $reviewers[2],
                'applicationID' => $c->model()['id'],
            ], true
        );
    } else { // handle case of more then 3. this may wind up being three but that's ok
        $x = UniqueRandomNumbersWithinRange(0, count($reviewers) - 1, 3);
        $r1 = Review(
            [
                'periodID' => Period::current()->id,
                'reviewerID' => $reviewers[$x[0]],
                'applicationID' => $c->model()['id'],
            ], true
        );
        $r2 = Review(
            [
                'periodID' => Period::current()->id,
                'reviewerID' => $reviewers[$x[1]],
                'applicationID' => $c->model()['id'],
            ], true
        );
        $r3 = Review(
            [
                'periodID' => Period::current()->id,
                'reviewerID' => $reviewers[$x[2]],
                'applicationID' => $c->model()['id'],
            ], true
        );
    }

    $r1->save();
    $r2->save();
    $r3->save();

    /*// Email the student
            Mail::send(
                $application->email,
                'CSTEM Scholars Grant Application Submitted',
                HTML::template(
                    'emails/application_submitted_student.php',
                    ['application' => $application, 'period' => $period]
                )
            );*/

    // email reviewers
    Mail::send(
        $r1->reviewerID,
        'CSTEM Scholars Grant Application In need of Review',
        HTML::template(
            'emails/application_submitted_reviewer.php',
            ['application' => $application, 'period' => $period, 'review' => $r1]
        )
    );
    $m->status = 'submitted';
    $m->save();
} // end update block

if ($c->done()) {
    // TODO: Show success/error message
    HTTP::redirect('../advisors/applications.php');
}

echo HTML::template('advisors/application.php', ['application' => $c->model(), 'form' => $c->form()]);
