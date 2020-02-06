<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>

    <title>Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<style>
        body {
            background: #8B0000;
        }
        .form {
            max-width: 1000px;
            padding: 30px;
            margin: 40px auto;
            background: #FFF;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
            -moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
            -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.13);
        }

            .form h1 {
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
            .form h1 > span {
                display: block;
                margin-top: 2px;
                font: 13px Arial, Helvetica, sans-serif;
            }
			.logout{
				float: right;
				background: #F8F8F8;
                padding: 8px 20px 8px 20px;
                border-radius: 5px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                color: #000000;
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.12);
                font: normal 30px 'Bitter', serif;
                -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.17);
                border: 1px solid #257C9E;
                font-size: 15px;
			}
			.logout:hover{
                background: #F8F8F8;
                border: 1px solid #8B0000;
                    -moz-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
                    -webkit-box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
                    box-shadow: inset 0px 2px 2px 0px rgba(255, 255, 255, 0.28);
			}

			
</style>

</head>
<body>
<div class="form">
	<h1>Grant Application Requirements</h1>
	<form>
	<p>All applicants must be enrolled at least half-time, be in good academic standing
        and be engaged in a CSTEM research project. 
        Applicants must be within two years of completing their degree. 
        CSTEM faculty mentors are required for all applicants. 
        Students may only recieve one CSTEM Scholar award each year.
    </p>
	</form>
</div>


</body>
</html>