<?php

require_once '../includes/init.php';

User::authorize('student');

$user = User::current();
$period = Period::current();

if (!$period) {
    HTTP::error(
        'The CSTEM Research Grant application has been closed. Please check back at a later date.',
        200,
        'Student Application'
    );
}

$application =
    Application::first('studentID = ? AND periodID = ?', $user->id, $period->id) ??
    new Application(
        [
            'name'      => $user->name,
            'email'     => $user->email,
            'studentID' => $user->id,
            'periodID'  => $period->id,
            'status'    => 'draft',
            'terms'     => HTTP::post('terms')
        ], true
    );

if (HTTP::post('submit') && $application->status == 'draft') {
    $application->status = 'submitted';
}

$form = new Form($application);

// TODO: Show error if email send fails
if (HTTP::isPost() && $application->isValid() ) {
    DB::beginTransaction();

    try {

        $application->save();

        if ($application->status == 'submitted') {
            // Email the advisor
            Mail::send(
                $application->advisorEmail,
                'CSTEM Scholars Grant Application Needs Review',
                HTML::template(
                    'emails/application_submitted_advisor.php',
                    ['application' => $application, 'period' => $period]
                )
            );

            // Email the student
            Mail::send(
                $application->email,
                'CSTEM Scholars Grant Application Submitted',
                HTML::template(
                    'emails/application_submitted_student.php',
                    ['application' => $application, 'period' => $period]
                )
            );
        }

        DB::commit();

        if ($application->status != 'draft') {
            HTTP::redirect('thank_you.php');
        }
    } catch (Exception $e) {
        // TODO: Uploaded attachment was not deleted
        DB::rollback();
        throw $e;
    }
}

echo HTML::template(
    'students/application.php',
    [
        'application' => $application,
        'form'        => $form,
        'period'      => $period
    ]
);
