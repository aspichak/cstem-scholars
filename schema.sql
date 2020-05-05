create table `Application`
(
    `id` int auto_increment
        primary key,
    `studentID` int not null,
    `periodID` int not null,
    `name` varchar(50) null,
    `email` varchar(50) null,
    `title` varchar(140) null,
    `major` varchar(30) null,
    `gpa` double null,
    `graduationDate` date null,
    `advisorName` varchar(50) null,
    `advisorEmail` varchar(50) null,
    `description` text null,
    `timeline` text null,
    `justification` text null,
    `totalBudget` decimal(10, 2) null,
    `requestedBudget` decimal(10, 2) null,
    `fundingSources` text null,
    `attachment` varchar(100) null,
    `status` varchar(30) not null,
    `additionalInformationRequested` bool default false,
    foreign key (advisorEmail) references User(email)
                on update cascade
                on delete set null,
    foreign key (periodID) references Period(id)
                on delete no action
                on update cascade
);

create table `Period`
(
    `id` int auto_increment primary key,
    `beginDate` date not null,
    `deadline` date not null,
    `advisorDeadline` date not null,
    `budget` decimal(10, 2) not null
);

create table `User`
(
    `name` varchar(50) null,
    `email` varchar(50) not null primary key,
    `isAdvisor` boolean default false, 
    `isReviewer` boolean default false, 
    `isAdmin` boolean default false
);

create table `Review`
(
    `id` int auto_increment primary key,
    `reviewerID` varchar(50) null,
    `applicationID` int not null,
    `periodID` int not null,
    `comments` text null,
    `q1` int null,
    `q2` int null,
    `q3` int null,
    `q4` int null,
    `q5` int null,
    `q6` int null,
    `fundingRecommended` int null,
    `submitted` boolean not null default false,
    foreign key (reviewerID) references User(email)
                on update cascade
                on delete cascade,
    foreign key (applicationID) references Application(id)
                on delete cascade
                on update cascade,
    foreign key (periodID) references Period(id)
                on delete cascade
                on update cascade
);
