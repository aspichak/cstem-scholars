CREATE DATABASE researchgrant;
USE researchGrant;

create table `advisor`
(
	`AEmail` varchar(30)
		primary key,
	`AName` varchar(20) null
)
charset=utf8mb4;

create table `periods`
(
	`ID` int auto_increment
		primary key,
	`Deadline` date not null,
	`AdvisorDeadline` date not null,
	`BeginDate` date not null,
	`Budget` int not null
)
charset=utf8mb4;

create table `student`
(
	`SID` int not null
		primary key,
	`SName` varchar(20) null,
	`SEmail` varchar(30) null,
	`GPA` double null,
	`Department` varchar(30) null,
	`Major` varchar(30) null,
	`GraduationDate` date null
)
charset=utf8mb4;

create table `applications`
(
	`ID` int auto_increment
		primary key,
	`SID` int not null,
	`PTitle` varchar(50) null,
	`Objective` text null,
	`Timeline` text null,
	`Budget` double null,
	`RequestedBudget` double null,
	`FundingSources` text null,
	`Anticipatedresults` text null,
	`Justification` text null,
	`BudgetFilePath` varchar(100) null,
	`Submitted` boolean not null default false,
	`Awarded` boolean not null default false,
	`AmountGranted` int null,
	`AdvisorApproved` boolean not null default false,
	`AdvisorComments` text null,
	`AEmail` varchar(30) null,
	`PeriodID` int not null,
	foreign key (SID) references student(SID)
				on update cascade
				on delete cascade,
	foreign key (AEmail) references advisor(AEmail)
				on update cascade
				on delete set null,
	foreign key (PeriodID) references periods (ID)
				on delete no action
				on update cascade
)
charset=utf8mb4;

create table `reviewers`
(
	RName varchar(30) null,
	REmail varchar(30) not null
		primary key,
	Major varchar(30) null,
	Active tinyint(1) not null default 0
)
charset=utf8mb4;

create table `reviewedapps`
(
	`ID` int auto_increment
		primary key,
	`AppID` int not null,
	`REmail` varchar(50) not null,
	`QAComments` varchar(1000) null,
	`QA1` int null,
	`QA2` int null,
	`QA3` int null,
	`QA4` int null,
	`QA5` int null,
	`QA6` int null,
	`QATotal` int null,
	`FundRecommend` int null,
	`Submitted` boolean not null default false,
	`PeriodID` int not null,
	foreign key (PeriodID) references periods (ID)
				on delete no action
				on update cascade,
	foreign key (REmail) references reviewers (REmail)
				on delete no action
				on update cascade,
	foreign key (AppID) references applications (ID)
				on delete cascade
				on update cascade
)
charset=utf8mb4;

create table `settings`
(
	`ApplicationSubmission` text not null,
	`ChangesRequested` text not null,
	`ApplicationApproval` text not null,
	`ApplicationAward` text not null,
	`DistributedApps` tinyint(1) not null
)
charset=utf8mb4;

INSERT INTO researchgrant.settings (ApplicationSubmission, ChangesRequested, ApplicationApproval, ApplicationAward, DistributedApps) VALUES ('Your application has been submitted successfully. ', 'Your advisor has requested changes on your Undergraduate Research Grant Application.  Comment to follow:', 'Congrats, you''re application has been approved. ', 'Congrats, you have been selected to receive the undergraduate research grant fund! Please get into contact with the Dean of STEM to learn more about your award at dean@example.edu.', 0);


