create database if not exists readcomptest;

use readcomptest;

create table if not exists choice
(
    id          int auto_increment
        primary key,
    question_id int          null,
    choice      varchar(100) null,
    is_correct  tinyint(1)   null,
    delete_date datetime     null
)
    charset = utf8mb4;

create table if not exists question
(
    id          int auto_increment
        primary key,
    test_id     int      null,
    question    text     null,
    delete_date datetime null
)
    charset = utf8mb4;

create table if not exists result
(
    id          int auto_increment
        primary key,
    test_id     int                                 null,
    user_id     int                                 null,
    insert_date timestamp default CURRENT_TIMESTAMP not null
);

create table if not exists result_answer
(
    id        int auto_increment
        primary key,
    result_id int null,
    choice_id int null
);

create table if not exists test
(
    id          int auto_increment
        primary key,
    name        varchar(200) null,
    delete_date datetime     null
)
    charset = utf8mb4;

create table if not exists user
(
    id       int auto_increment
        primary key,
    userName varchar(200) null,
    password varchar(255) null
);

create table if not exists assignment
(
    id       int auto_increment
        primary key,
    user_id int(11) not null,
    test_id int(11) not null,
    result_id int(11) null,
    score int(11) null,
    completion_date datetime
);