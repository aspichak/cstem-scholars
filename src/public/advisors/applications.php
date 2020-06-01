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
    $application = $c->model();
    // TODO: remove the following line in future after ModelController fixed
    $application = Application::first('id=?', HTTP::get('id'));
    $reviewers = User::reviewersNotCurrentUser()->fetchAll();

    if (User::current()->isReviewer() && count($reviewers) == 2) {
        $reviewers = User::reviewers()->fetchAll();
    }
    if (count($reviewers) < 3) {
        // TODO: error message for less then three reviewers
        return;
    }
    $period = Period::current();
    // we should always have 3 or more reviewers here
    if (count($reviewers) == 3) {
        $r1 = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[0]->email,
                'applicationID' => $application->id,
                'submitted' => 0,
            ], true
        );
        $r2 = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[1]->email,
                'applicationID' => $application->id,
                'submitted' => 0,
            ], true
        );
        $r3 = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[2]->email,
                'applicationID' => $application->id,
                'submitted' => 0,
            ], true
        );
    } else { // handle case of more then 3. this may wind up being three but that's ok
        $x = UniqueRandomNumbersWithinRange(0, count($reviewers) - 1, 3);
        $r1 = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[$x[0]]->email,
                'applicationID' => $application->id,
                'submitted' => 0,
            ], true
        );
        $r2 = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[$x[1]]->email,
                'applicationID' => $application->id,
                'submitted' => 0,
            ], true
        );
        $r3 = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[$x[2]]->email,
                'applicationID' => $application->id,
                'submitted' => 0,
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
    Mail::send(
        $r2->reviewerID,
        'CSTEM Scholars Grant Application In need of Review',
        HTML::template(
            'emails/application_submitted_reviewer.php',
            ['application' => $application, 'period' => $period, 'review' => $r2]
        )
    );
    Mail::send(
        $r3->reviewerID,
        'CSTEM Scholars Grant Application In need of Review',
        HTML::template(
            'emails/application_submitted_reviewer.php',
            ['application' => $application, 'period' => $period, 'review' => $r3]
        )
    );
    $application->status = 'pending_review';
    $application->save(false);
} // end update block

if ($c->done()) {
    // TODO: Show success/error message
    HTTP::redirect('../advisors/applications.php');
}

echo HTML::template('advisors/application.php', ['application' => $c->model(), 'form' => $c->form()]);
