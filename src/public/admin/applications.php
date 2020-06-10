<?php

require_once '../../init.php';

use Respect\Validation\ValidatorFunction as v;

User::authorize('admin');

$c = new ModelController(Application::class);
$application = $c->model();
$error = null;
$selectedPeriodID = HTTP::get('periodID', Period::mostRecent()->id ?? null);
// TODO: Filter applications by period, student name, email, status, etc.
$c->index(
    'admin/applications.php',
    ['applications' => Application::all('periodID = ?', $selectedPeriodID), 'selectedPeriodID' => $selectedPeriodID]
);

$c->read();

if ($c->action() == 'update') {
    Form::assertCsrfToken();

    if (HTTP::post('action') == 'award') {
        $error = (v::number()->min(0)->setName('Amount Awarded'))(HTTP::post('amount'));

        if (!$error) {
            $application->amountAwarded = HTTP::post('amount');
            $application->status = 'awarded';

            Mail::send(
                $application->email,
                'CSTEM Scholars Grant Award',
                HTML::template(
                    'emails/application_awarded.php',
                    [
                        'application' => $application,
                        'message' => HTTP::post('message')
                    ]
                )
            );

            $application->save(false);
            HTTP::redirect('../admin/applications.php', ['success' => 'Award email sent']);
        }
    }

    if (HTTP::post('action') == 'reject') {
        $error = (v::length(3, 1000)->setName('Rejection Reason'))(HTTP::post('reason'));

        if (!$error) {
            $application->status = 'rejected';

            Mail::send(
                $application->email,
                'CSTEM Scholars Grant',
                HTML::template(
                    'emails/application_rejected.php',
                    [
                        'application' => $application,
                        'message' => HTTP::post('reason')
                    ]
                )
            );

            $application->save(false);
            HTTP::redirect('../admin/applications.php', ['success' => 'Rejection email sent']);
        }
    }
}

if ($c->delete()) {
    HTTP::redirect('applications.php');
}

echo HTML::template('admin/application.php', ['application' => $application, 'error' => $error]);
