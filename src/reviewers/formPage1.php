<!DOCTYPE html>
<html lang="en">
<?php
require_once '../includes/init.php';
include_once 'creds.php';
authorize('reviewer');
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
    <script type="text/javascript">
        function doubleCheck(submitButton) {
            let submit = window.confirm("Are you sure you want to submit?")
            if (submit)
                submitButton = true;
            else
                submitButton = false;
        }
    </script>
</head>
<body>
<?php $app_id = $_POST['appNum']; ?>
<div class="container">
    <div class="imagebg"></div>
    <div class="row " style="margin-top: 50px">
        <div class="col-md-6 col-md-offset-3 form-container">
            <h2>Feedback</h2>
            <form role="form" method="post" id="reused_form">
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <input type="hidden" name="app" value=<?php echo $app_id; ?>/>
                        <label>Please Verify Application ID: <?php echo $app_id; ?></label>
                        <label>Does the project demonstrate experiential learning in a CSTEM discipline?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="learn" value="0" required>0</label>
                            <label class="radio-inline"><input type="radio" name="learn" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="learn" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="learn" value="3">3</label>
                        </p>
                        <label>Is the budget justified in the project description, including realistic?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="justified" value="0"
                                                               required>0</label>
                            <label class="radio-inline"><input type="radio" name="justified" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="justified" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="justified" value="3">3</label>
                        </p>
                        <label>Are the proposed methods appropriate to achieve the goals?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="method" value="0" required>0</label>
                            <label class="radio-inline"><input type="radio" name="method" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="method" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="method" value="3">3</label>
                        </p>
                        <label>Is the timeline proposed reasonable?(Too little? Too much?)</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="time" value="0" required>0</label>
                            <label class="radio-inline"><input type="radio" name="time" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="time" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="time" value="3">3</label>
                        </p>
                        <label>Is the project well explained (including rationale) and justified?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="project" value="0" required>0</label>
                            <label class="radio-inline"><input type="radio" name="project" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="project" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="project" value="3">3</label>
                        </p>
                        <label>Does the budget only include eligible activities (supplies, equipment, field travel,
                            conference travel)?</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="budget" value="0" required>0</label>
                            <label class="radio-inline"><input type="radio" name="budget" value="1">1</label>
                            <label class="radio-inline"><input type="radio" name="budget" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="budget" value="3">3</label>
                        </p>
                        <label>Based on eligibility and quality scores, RECOMMEND one of the following
                            categories</label>
                        <p>
                            <label class="radio-inline"><input type="radio" name="fund" value="0" required>Do Not
                                Fund</label>
                            <label class="radio-inline"><input type="radio" name="fund" value="1">Fund if
                                Possible</label>
                            <label class="radio-inline"><input type="radio" name="fund" value="2">Fund</label>
                        </p>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="comments"> Quality Assessment Comments:</label>
                                <textarea class="form-control" type="textarea" name="qual_comments"
                                          placeholder="Your Comments" maxlength="6000" rows="7"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <!-- Need to have confirm work properly -->
                                <button type="submit" class="button" name="submitButton"
                                        onclick="return confirm('Are you sure you want to submit?')"
                                        formaction="submitted.php?id=<?php echo $app_id ?>">Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

