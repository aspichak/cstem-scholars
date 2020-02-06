<?php
 $database = parse_ini_file("../config.ini");
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
 
 if(isset($_POST["Export"])){
      $sth = $pdo->prepare("SELECT Deadline FROM Settings");
      $sth->execute();
      $date_array = $sth->fetch();
      $deadline = $date_array['Deadline'];
      $temp = explode("-", $deadline);
      $year = $temp[0];
      $month = $temp[1];
      $appTabe = 'Applications'.$month.$year;
      header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=Report.csv');  
      $sth= $pdo->prepare("SELECT COUNT(ApplicationNum) as 'StartedApps' From ".$appTabe);
      $sth-> execute();
      $query = $sth->fetch();
       $startedApps= $query['StartedApps'];
      $sth= $pdo->prepare("SELECT COUNT(ApplicationNum) as 'Submitted' From ".$appTabe." where Submitted =1");
       $sth ->execute();
       $query=$sth->fetch();
      $submitted = $query['Submitted'];
      $sth= $pdo->prepare("SELECT COUNT(ApplicationNum) as 'Approved' From ".$appTabe." where AdvisorApproved =1");
      $sth ->execute();
      $query = $sth->fetch();
     $approved = $query['Approved'];
      $sth= $pdo->prepare("SELECT COUNT(ApplicationNum) as 'Awarded', SUM(AmountGranted) as 'Total' From ".$appTabe." where Awarded=1");
       $sth ->execute();
       $query = $sth->fetch();
      $awarded = $query['Awarded'];
      $amount = $query['Total'];
      $output = fopen("php://output", "w");  
      fputcsv($output, array('Started Applications', 'Submitted Applications', 'Advior Approved Applications', 'Awarded Applications', 'Total Amount Awarded'));  
  
          
      fputcsv($output, array($startedApps,$submitted,$approved,$awarded,$amount));  
        
      fclose($output);  
 }  
?>