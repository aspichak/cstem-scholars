<?php

require_once '../../init.php';

User::authorize('reviewer');

// TODO: Make a section in DB for reviewerEmail
# get the current deadline for scholarship
$periods = Period::all( '1 ORDER BY beginDate DESC' );
$deadline = $periods[0]->deadline;
#get all the applications assigned to this reviewer (currently advisor 05/28/2020)
$applications = Application::all( 'advisorEmail = ?', User::current()->email );
#date( 'M j, Y', strtotime($deadline)); prints period in M:D:Y format


$c = new ModelController(Review::class);
#naming rows to match previous code in landing_layout
#we are loading landing_layout and transfering over $applications as $rows, $deadline, and the form
$c->index('reviewer/landing_layout.php', ['rows' => $applications, 'deadline' => $deadline, 'form' => $c->form()]);
$c->create();

#$ROWS, $ROW, $DEADLINE, $FORM ARE OBJECTS, NOT ARRAYS
# $row->studentID NOT $row['studentID']

#echo HTML::template('reviewer/landing_layout.php', ['index' => ]);
echo HTML::template('reviewer/landing_layout.php');
?>
