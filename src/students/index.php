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

$form = new Form($application);
$isUploadRequired = (!$application->attachment || $application->status != 'draft');
$file = new FileUpload('attachment', $isUploadRequired, ALLOWED_UPLOAD_EXTENSIONS, 10485760);

// TODO: If file is invalid show error even if old file exists
// TODO: Show error if email send fails
if (HTTP::isPost() && $application->isValid() && ($file->isValid() || $application->attachment)) {
    DB::beginTransaction();

    try {
        if ($application->status == 'draft') {
            $application->status = 'submitted';
        }

        $oldFilename = $application->attachment;
        $today = date('Y-m-d');
        $newFilename = "$today-{$user->id}.{$file->extension()}"; // 2020-12-30-00100001.pdf
        $file->move(UPLOAD_DIR, $newFilename);
        $application->attachment = $newFilename;
        $application->save();

        if ($application->status == 'submitted') {
            // Email the advisor
            Mail::send(
                $application->advisorEmail,
                'CSTEM Scholars Grant Application Needs Review',
                HTML::template('emails/application_submitted_advisor.php', $form)
            )->send();

            // Email the student
            Mail::send(
                $application->email,
                'CSTEM Scholars Grant Application Submitted',
                HTML::template('emails/application_submitted_student.php', $form)
            )->send();
        }

        DB::commit();
        FileUpload::delete(UPLOAD_DIR, $oldFilename);

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
        'file'        => $file,
        'period'      => $period
    ]
);
