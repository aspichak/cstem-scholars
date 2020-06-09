<?php

require_once '../../init.php';

User::authorize('reviewer');

$c = new ModelController(Review::class);
#naming rows to match previous code in landing_layout
#we are loading landing_layout and transfering over $applications as $rows, $deadline, and the form
$c->index('reviewer/applications_layout.php', ['reviews' => Review::all('reviewerID = ?', User::current()->email) , 'apps' => Application::all()]);


if( $c->action() == 'update' ){

    $review = $c->model();
    $review->submitted = 1;

    #use applicationID to grab reference to the application, might not need
    $application = Application::first('id=?', $review->applicationID);

    if ($review->save()) {
        $application->status = 'reviewed';
        $application->save(false);
        HTTP::redirect('../reviewers/applications.php');
    }
}
echo HTML::template('reviewer/form_layout.php', ['review' => $c->model(), 'form' => $c->form()->disableInlineErrors(), 'application_list' => Application::all() ]);


