<?php

$title = 'Review Application';
$layout = 'admin/_layout.php';


$appNum = HTTP::get('id');
$application = null;
$count = 0;
foreach( $application_list as $a ){
    if( $a->id == $appNum ){
        $application = $a;
    }
}


?>

<h1><?= e($application->title) ?></h1>
<?= template('application_details.php', $application) ?>

<br>
<h2> REVIEW FORM </h2>

<?= $form->errors() ?>

<form method="POST" enctype="multipart/form-data">
    <?= $form->csrf() ?>
    <div class="form">
        <label>Does the project demonstrate experiential learning in a CSTEM discipline?</label>
        <p>
            <?= $form->radio('q1', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q1', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q1', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q1', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Is the budget justified in the project description, including realistic?</label>
        <p>
            <?= $form->radio('q2', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q2', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q2', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q2', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Are the proposed methods appropriate to achieve the goals?</label>
        <p>
            <?= $form->radio('q3', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q3', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q3', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q3', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Is the timeline proposed reasonable?(Too little? Too much?)</label>
        <p>
            <?= $form->radio('q4', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q4', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q4', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q4', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Is the project well explained (including rationale) and justified?</label>
        <p>
            <?= $form->radio('q5', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q5', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q5', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q5', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Does the budget only include eligible activities (supplies, equipment, field travel,
            conference travel)?</label>
        <p>
            <?= $form->radio('q6', 1, ['value = "0"'] ) ?>
            <label>0</label>
            <?= $form->radio('q6', 1, ['value = "1"'] ) ?>
            <label>1</label>
            <?= $form->radio('q6', 1, ['value = "2"'] ) ?>
            <label>2</label>
            <?= $form->radio('q6', 1, ['value = "3"'] ) ?>
            <label>3</label>
            <br>
        </p>
        <label>Based on eligibility and quality scores, RECOMMEND one of the following
            categories</label>
        <p>
            <?= $form->radio('fundingRecommended', 1, ['value = "1"'] ) ?>
            <label>Yes</label>
            <?= $form->radio('fundingRecommended', 1, ['value = "0"'] ) ?>
            <label>No</label>
            <br>
        </p>
        <label>Quality Assessment Comments:</label><br>
        <?= $form->textarea( 'comments', ['maxlength' => 6000, 'rows' => 6, 'required'] ) ?>
        <div class="button-section">
            <button type="submit" class="button" name="submit" value="submit">Submit</button>
        </div>
    </div>
</form>
