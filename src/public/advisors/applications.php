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

    if (User::current()->isReviewer() && count($reviewers) <= 2) {
        $reviewers = User::reviewers()->fetchAll();
    }
    if (count($reviewers) == 0) {
        // TODO: error message for less then three reviewers
        HTTP::error("There are no reviewers in the system", 200);
    }
    $period = Period::current();
    // we should always have at least one reviwer here
    $reviews = array();
    $numReviews = count($reviewers);
    if ($numReviews > 3)
        $numReviews = 3;
    $x = UniqueRandomNumbersWithinRange(0, count($reviewers) - 1, $numReviews);
    foreach($x as $i) {
        $review = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[$i]->email,
                'applicationID' => $application->id,
            ], true
        );
        $review->save(false);
        Mail::send(
            $review->reviewerID,
            'CSTEM Scholars Grant Application In need of Review',
            HTML::template(
                'emails/application_submitted_reviewer.php',
                ['application' => $application, 'period' => $period, 'review' => $review]
            )
        );
    }

    $application->status = 'pending_review';
    $application->save(false);

    // TODO: handle errors with a message instead of just redirecting no matter what
    HTTP::redirect('../advisors/applications.php');
} // end update block

echo HTML::template('advisors/application.php', ['application' => $c->model(), 'form' => $c->form()]);
