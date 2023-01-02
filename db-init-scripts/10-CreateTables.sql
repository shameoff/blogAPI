CREATE TABLE `User` (
    id varchar(100) primary key,
    fullName varchar(255) NOT NULL,
    birthDate date,
    gender varchar(10),
    phoneNumber varchar(16),
    email varchar(150) NOT NULL,
    UNIQUE (email)
);

CREATE TABLE `Tag` (
    id varchar(100) primary key,
    tagName varchar(100) NOT NULL,
    UNIQUE (tagName)
);

CREATE TABLE `Post` (
    id varchar(100) primary key,
    title varchar(200) NOT NULL,
    content text NOT NULL,
    readingTime int,
    photo text
);

CREATE TABLE `Post-Tag` (
    postId varchar(100),
    tagId varchar(100),
    primary key (postId, tagId)
);

CREATE TABLE `Like` (
    id varchar(100) primary key,
    userId varchar(100),
    postId varchar(100),
    foreign key (userId) references User(id),
    foreign key (postId) references Post(id)
);

CREATE TABLE `Comment` (
    id varchar(100) primary key,
    userId varchar(100) NOT NULL,
    postId varchar(100),
    parentId varchar(100),
    content text,
    foreign key (userId) references User(id),
    foreign key (postId) references Post(id),
    foreign key (parentId) references Comment(id)
);
