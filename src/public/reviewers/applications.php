<?php

require_once '../../init.php';

User::authorize('reviewer');


#TODO: once application is submitted, change it's APPLICATION status to 'reviewed'
#      when opening a review thats already in progress, open that one don't create new one


$c = new ModelController(Review::class);
#naming rows to match previous code in landing_layout
#we are loading landing_layout and transfering over $applications as $rows, $deadline, and the form
$c->index('reviewer/applications_layout.php', ['applications' => Application::all()]);
#$c->read();


if( $c->action() == 'update' ){

    #temp review form that has filled out data
    $review = $c->model();
    $user = User::current();
    $period = Period::current();
    #use applicationID to grab reference to the application, might not need
    $application = Application::first('id=?', HTTP::get('id'));
    $review_list = Review::all();
    #TODO search review table w/ appID to see if this app already has a review in progress,
    #     if not, then just generate a new on as we have been doing

    $found = false;
    foreach( $review_list as $r ){
        if( $r->applicationID == $application->id ){
            $found = true;
        }
    }
    #if application id isn't found in review, save ne one
    #if( !$found ) {
        if ($review->save()) {
            $application->status = 'reviewed';
            $application->save(false);
            HTTP::redirect('../reviewers/applications.php');
        }

    #}
    #if it is found, then update instead
    #else {

    #}
}
echo HTML::template('reviewer/form_layout.php', ['review' => $c->model(), 'form' => $c->form(), 'application_list' => Application::all() ]);
#echo HTML::template('reviewers/landing_layout.php');


