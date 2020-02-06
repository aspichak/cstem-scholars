	<?php
	session_start();
	require_once __DIR__."/sso-example/SSO/CAS/CAS.php";
  // Pulls the 
	require_once __DIR__."/sso-example/SSO/config.php";
	
		phpCAS::client(SAML_VERSION_1_1, $cas_host, $cas_port, $cas_context);
		//phpCAS::setCasServerCACert($cas_server_ca_cert_path);
		phpCAS::setNoCasServerValidation();
		phpCAS::handleLogoutRequests();
		phpCAS::forceAuthentication();

	
		if (isset($_REQUEST['logout'])) {
			phpCAS::logout();
			$_SESSION = array();
			session_destroy();
		}
		$user = phpCAS::getUser();
		$attributes = phpCAS::getAttributes();
		$userType = $attributes["UserType"];
		$email = $attributes["Email"];
		$_SESSION["id"] = $attributes["Ewuid"];
		$_SESSION["email"] = $email;
		$_SESSION["user"] = $user;
		
		$database = parse_ini_file("config.ini");
		$host = $database['host'];
		$db = $database['db'];
		$user = $database['user'];
		$pass = $database['pass'];
        $charset = 'utf8mb4';
        $dsn  = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try{
                 $pdo = new PDO($dsn, $user, $pass, $options);
        }catch(\PDOException $e)
        {
                 throw new \PDOException($e->getMessage(), (int)$e->getCode());
		}
		$sth = $pdo->prepare("SELECT BeginDate, Deadline, AdvisorDeadline FROM Settings");
		$sth->execute();
		$dates = $sth->fetch();
		$beginDate = $dates["BeginDate"];
		$deadLine = $dates["Deadline"];
		$advisorDeadLine = $dates["AdvisorDeadline"];
		$today = date("Y-m-d");
		$sth = $pdo->prepare("SELECT AEmail FROM Advisor");
		$sth->execute();
		$advisorEmails = $sth->fetchAll();
		$sth = $pdo->prepare("SELECT REmail FROM Reviewers");
		$sth->execute();
		$reviewerEmails = $sth->fetchAll();	
		
		$type=$_GET["id"];

		if($type == "admin")
		{
			if($email == "lcornick@ewu.edu")header("location:/Admin/index.php");
			else{header("location:infoPages/NotAdmin.php");}
		}
		elseif($type == 'student')
		{
			if($userType == 'Employee')header("location:infoPages/NotStudent.php");
			elseif($today <= $deadLine && $today >= $beginDate)
			{
				header("location:/students/studentFormView.php");
			}
			else
			{
				header("location:infoPages/closedStudent.php");
			}
		}
		elseif($type == 'faculty')
		{
			if($userType == 'Student')header("location:infoPages/NotAdvisor.php");
			elseif($today <= $advisorDeadLine && $today >= $beginDate)
			{
				
				foreach($advisorEmails as $value)
				{
					
					if($value["AEmail"] == $email)
					{
						$isAdvisor = true;
						
					}
				}
				if($isAdvisor){header("location:faculty/facultylandingpage.php");}
				else{header("location:infoPages/emptyFaculty.php");}	
			}
			else{header("location:infoPages/closedFaculty.php");}

		}
		elseif($type == "reviewer")
		{
			if($userType == 'Student')header("location:infoPages/notReviewer.php");
				foreach($reviewerEmails as $value)
				{
					if($value["REmail"] == $email)
					{
						$isReviewer = true;
						
					}
				}
				if($isReviewer)
				{
					if($today > $advisorDeadLine)
					{
						header("location:/reviewers/ReviewStudents.php");
					}
					else
					{
						header("location:infoPages/closedReviewer.php");
					}
				}	
				else
				{	header("location:infoPages/notReviewer.php"); }
			
				
		}

	
	?>
