CREATE DATABASE IF NOT EXISTS researchGrant;
USE researchGrant;

CREATE TABLE `Advisor` (
  `AEmail` varchar(30) DEFAULT NULL,
  `AName` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Reviewers` (
  `RName` varchar(30) DEFAULT NULL,
  `REmail` varchar(30) NOT NULL,
  `Major` varchar(30) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL,
  PRIMARY KEY (`REmail`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Settings` (
  `NextAppNum` int(11) DEFAULT NULL,
  `Deadline` date DEFAULT NULL,
  `AdvisorDeadline` date DEFAULT NULL,
  `BeginDate` date DEFAULT NULL,
  `Budget` int(11) DEFAULT NULL,
  `ApplicationSubmission` varchar(9000) NOT NULL,
  `ChangesRequested` varchar(9000) NOT NULL,
  `ApplicationApproval` varchar(9000) NOT NULL,
  `ApplicationAward` varchar(9000) NOT NULL,
  `DistributedApps` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Settings` (`NextAppNum`, `Deadline`, `AdvisorDeadline`, `BeginDate`, `Budget`, `ApplicationSubmission`, `ChangesRequested`, `ApplicationApproval`, `ApplicationAward`, `DistributedApps`) VALUES
(1, NULL, NULL, NULL, 0, 'Your application has been submitted successfully. ', 'Your advisor has requested changes on your Undergraduate Research Grant Application.  Comment to follow:', 'Congrats, you\'re application has been approved. ', 'Congrats, you have been selected to receive the undergraduate research grant fund! Please get into contact with the Dean of STEM to learn more about your award at dean@example.edu.', 0);

CREATE TABLE `Student` (
  `SID` int(11) NOT NULL,
  `SName` varchar(20) DEFAULT NULL,
  `SEmail` varchar(30) DEFAULT NULL,
  `GPA` double DEFAULT NULL,
  `Department` varchar(30) DEFAULT NULL,
  `Major` varchar(30) DEFAULT NULL,
  `GraduationDate` date DEFAULT NULL,
  PRIMARY KEY (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Applications` (
  `ApplicationNum` int(11) NOT NULL AUTO_INCREMENT,
  `SID` int(11) DEFAULT NULL,
  `PTitle` varchar(50) DEFAULT NULL,
  `Objective` varchar(9000) DEFAULT NULL,
  `Timeline` varchar(500) DEFAULT NULL,
  `Budget` double DEFAULT NULL,
  `RequestedBudget` double DEFAULT NULL,
  `FundingSources` varchar(1000) DEFAULT NULL,
  `Anticipatedresults` varchar(1000) DEFAULT NULL,
  `Justification` varchar(1000) DEFAULT NULL,
  `BudgetFilePath` varchar(100) DEFAULT NULL,
  `Submitted` tinyint(1) DEFAULT NULL,
  `Awarded` tinyint(1) DEFAULT NULL,
  `AmountGranted` int(11) DEFAULT NULL,
  `AdvisorApproved` tinyint(1) DEFAULT NULL,
  `AdvisorComments` varchar(9000) DEFAULT NULL,
  `AEmail` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ApplicationNum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `ReviewedApps` (
  `ApplicationNum` int(11) NOT NULL AUTO_INCREMENT,
  `REmail` varchar(50) NOT NULL,
  `QAComments` varchar(1000) DEFAULT NULL,
  `QA1` int(11) DEFAULT NULL,
  `QA2` int(11) DEFAULT NULL,
  `QA3` int(11) DEFAULT NULL,
  `QA4` int(11) DEFAULT NULL,
  `QA5` int(11) DEFAULT NULL,
  `QA6` int(11) DEFAULT NULL,
  `QATotal` int(11) DEFAULT NULL,
  `FundRecommend` int(11) DEFAULT NULL,
  `Submitted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ApplicationNum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;