<!DOCTYPE HTML>
<?php include 'check.php';?>
<html>
        <head>
                <meta http-equiv="Content-Type">
                        <title>Application Form</title>
                                <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
                                        <style type="text/css">
                                        body{
                                                background:#8B0000;
                                        }
                                        .form{
                                                max-width:1000px;
                                                padding:30px;
                                                margin:40px auto;
                                                background: #FFF;
                                                border-radius: 10px;
                                                -webkit-border-radius:10px;
                                                -moz-border-radius: 10px;
                                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
                                                -moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
                                                -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
                                        }
                                        .form .inner-wrap{
                                                padding: 30px;
                                                background: #F8F8F8;
                                                border-radius: 6px;
                                                margin-bottom: 15px;
                                        }
                                        .form h1{
                                                background: #808080;
                                                padding: 20px 30px 15px 30px;
                                                margin: -30px -30px 30px -30px;
                                                border-radius: 10px 10px 0 0;
                                                -webkit-border-radius: 10px 10px 0 0;
                                                -moz-border-radius: 10px 10px 0 0;
                                                color: #fff;
                                                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
                                                font: normal 30px 'Bitter', serif;
                                                -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                border: 1px solid #000000;
                                        }
                                        .form h1 > span{
                                              display: block;
                                              margin-top: 2px;
                                              font: 13px Arial, Helvetica, sans-serif;
                                      }
                                      .form label{
                                              display: block;
                                              font: 13px Arial, Helvetica, sans-serif;
                                              color: #000000;
                                              margin-bottom: 15px;
                                      }
                                      .form input[type="text"],
                                      .form input[type="email"],
                                      .form textarea,
                                      .form select {
                                              display: block;
                                              box-sizing: border-box;
                                              -webkit-box-sizing: border-box;
                                              -moz-box-sizing: border-box;
                                              width: 100%;
                                              padding: 8px;
                                              border-radius: 6px;
                                              -webkit-border-radius:6px;
                                              -moz-border-radius:6px;
                                              border: 2px solid #fff;
                                              box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
                                              -moz-box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
                                              -webkit-box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.33);
                                      }
                                      .form .section{
                                              font: normal 20px 'Bitter', serif;
                                              color: #000000;
                                              margin-bottom: 5px;
                                      }
                                      .form .section span {
                                              background: #808080;
                                              padding: 5px 10px 5px 10px;
                                              position: absolute;
                                              border-radius: 50%;
                                              -webkit-border-radius: 50%;
                                              -moz-border-radius: 50%;
                                              border: 4px solid #fff;
                                              font-size: 14px;
                                              margin-left: -45px;
                                              color: #fff;
                                              margin-top: -3px;
                                              }
                                        .form button[type="button"],
                                        .form button[type="submit"]{
                                                background: #808080;
                                                padding: 8px 20px 8px 20px;
                                                border-radius: 5px;
                                                -webkit-border-radius: 5px;
                                                -moz-border-radius: 5px;
                                                color: #fff;
                                                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
                                                font: normal 30px 'Bitter', serif;
                                                -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                border: 1px solid #257C9E;
                                                font-size: 15px;
                                        }
                                        .logout button[type="submit"]{
                                                background: #808080;
                                                padding: 8px 20px 8px 20px;
                                                border-radius: 5px;
                                                -webkit-border-radius: 5px;
                                                -moz-border-radius: 5px;
                                                color: #fff;
                                                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
                                                font: normal 30px 'Bitter', serif;
                                                -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                                                border: 1px solid #257C9E;
                                                font-size: 15px;
                                        }
                                        .form input[type="button"]:hover,
                                        .form input[type="submit"]:hover{
                                                background: #2A6881;
                                                -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
                                                -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
                                                box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
                                        }
                                        .form .privacy-policy{
                                                float: right;
                                                width: 250px;
                                                font: 12px Arial, Helvetica, sans-serif;
                                                color: #000000;
                                                margin-top: 10px;
                                                text-align: right;
                                        }
                                        .tooltip {
                                    position: relative;
                                    display: inline-block;
                                    border-bottom: 1px dotted black;
                                }

                                .tooltip .tooltiptext {
                                    visibility: hidden;
                                    width: 120px;
                                    background-color: black;
                                    color: #fff;
                                    text-align: center;
                                    border-radius: 6px;
                                    padding: 5px 0;
                                    /* Position the tooltip */
                                  position: absolute;
                                  z-index: 1;
                                  bottom: 100%;
                                  left: 50%;
                                  margin-left: -60px;
                              }

                              .tooltip:hover .tooltiptext {
                                  visibility: visible;
                              }
                                      </style>
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
