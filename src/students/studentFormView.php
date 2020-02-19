<!DOCTYPE HTML>
<?php
require_once '../includes/init.php';
authorize('student');
include 'check.php';
?>
<html>
        <head>
                <meta http-equiv="Content-Type">
                        <title>Application Form</title>
                                <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
                                <link href="css/students.css" rel="stylesheet">
      </head>

      <body>
              <form role="form" method="post" enctype="multipart/form-data" align="right" >
                <div class = logout >
                <div class="button-section">
                 <button type="submit" class="button" name = "logout" formaction="../index.php?logout=true">Logout</button>
               </div>
               </div>
               </form>
                 <form role="form" method="post" action="pageRedirect.php" enctype="multipart/form-data">
                      <div class="form">
                              <h1>Grant Fund Application<span>Undergraduate Research</span><span>*All Fields Required</span></h1>
                      <form enctype="multipart/form-data">
                          <div class="section"><span>1</span>Basic Details</div>
                          <div class="inner-wrap">
                              <label>Your Full Name <input type="text" name="name" value = "<?php if(isset($name)){echo $name;} ?>" required/></label>
                              <label>Email Address <input type="email" name="email" value = "<?php if(isset($email)){echo $email;} ?>" pattern = "^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$"/></label>
                              <label>Project Title <input type="text" name="project" value = "<?php if(isset($pTitle)){echo $pTitle;} ?>" required/></label>
                          </div>

                          <div class="section"><span>2</span>Major &amp; GPA</div>
                          <div class="inner-wrap">
                          <label> Department
                              <select name="department" required>
                              <option disabled selected value>----Select Department----</option>
                                <option value="Biology">Biology</option>
                                <option value="Chemistry">Chemistry or Biochemistry</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Design">Design</option>
                                <option value="Engineering">Enginerring</option>
                                <option value="Environmental Science">Environmental Science</option>
                                <option value="Geology">Geology</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Natural Science">Natural Science</option>
                                <option value="Physics">Physics</option>
                              </select>
                              </label>
                              <label>Major <input type="text" name="major" value = "<?php if(isset($major)){echo $major;} ?>" required/></label>
                              <label>GPA <input type="text" name="gpa" value = "<?php if(isset($gpa)){echo $gpa;} ?>" required/></label>
                              <label>Expected Graduation Date <input type="date" name="egd" value = "<?php if(isset($gradDate)){echo $gradDate;} ?>" required/></label>
                          </div>

			  <div class="section"><span>3</span>Advisor Information</div>
                              <div class="inner-wrap">
                              <label>Advisor Name <input type="text" name="advisor" value = "<?php if(isset($advisor)){echo $advisor;} ?>" required/></label>
                              <label>Advisor Email <input type="email" name="advisor_email" value = "<?php if(isset($aemail)){echo $aemail;} ?>" pattern = "^([a-zA-Z0-9_\-\.]+)@ewu.edu$" title="someone@ewu.edu" required/></label>
                          </div>

                                      <div class="section"><span>4</span>Objective & Results</div>
                          <div class="inner-wrap">
                              <label>Please describe your objective in less than 6000 characters: <textarea name="objective" maxlength="6000" rows="7"  required><?php if(isset($objective)){echo $objective;} ?></textarea></label>
                              <label>Please describe your anticipated results in less than 6000 characters: <textarea name="results" maxlength="6000" rows="7" required><?php if(isset($anticipatedResults)){echo $anticipatedResults;} ?></textarea></label>
                          </div>
                          <div class="section"><span>5</span>Timeline</div>
              <div class="inner-wrap">
                  <label>Please describe your estimated timeline in less than 2000 characters: <textarea name="timeline" maxlength="2000" rows="4" required><?php if(isset($timeline)){echo $timeline;} ?></textarea></label>
              </div>

                          <div class="section"><span>6</span>Budget</div>
              <div class="inner-wrap">
                  <label>Please describe your budget and planned spending in less than 2000 characters:<textarea name="justification" maxlength ="2000"  required><?php if(isset($justification)){echo $justification;}?></textarea></label>
                  <label>Total budget amount:<input type="text" name="budget" value = "<?php if(isset($budget)){echo $budget;} ?>"  pattern="^[0-9]*$" required/></label>
                  <label>Requested budget amount from EWU:<input type="text" name="request" value = "<?php if(isset($requestedBudget)){echo $requestedBudget;} ?>"  pattern= "^[0-9]*$" required/></label>
                  <label>Please list any other funding sources you have: <input type="text" name="sources" value = "<?php if(isset($fundingSources)){echo $fundingSources;} ?>"required/></label>
                  <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                  <label> Upload budget spreadsheet: <input name="file" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/></label>
              </div>

              <div class="button-section">
              <button type="submit" class="button" name="submit" formaction="studentSubmit.php" >Submit </button>
               <button type="submit" class="button" name="save" formaction="studentSave.php" >Save </button>
               <span class="privacy-policy">
               <input type="checkbox" name="privPolicy" required>
                          <div class="tooltip">I agree to the Terms & Conditions
                          <span class="tooltiptext">Awards shall only be spent on allowable expenses as defined in the application. Receipts must be provided for all expenses including travel. Funds must be spent within one calendar year of dispersal A brief two-page progress report must be submitted to the faculty advisor and associate dean by the end of the project year. Any academic integrity or student code of conduct violations will result in forfeiture the award.
</span>
                          </div>
               </span>
              </div>
          </div>
          </form>
  </form>


</body>
</html>
