drop database if exists fudan_info;
create database fudan_info;

use fudan_info;

create table login_serial ( 
	username varchar(40) not null,
	serial varchar(32) not null primary key
);

create table users (
	username varchar(40) not null primary key,
	fullname varchar(20) not null,
	email varchar(40) not null,
	password varchar(32) not null,
	event_limit integer not null default 2,
	recruit_limit integer not null default 2
);

create table event_info (
	event_id integer not null auto_increment primary key,
	title varchar(70) not null,
	username varchar(40) not null,
	speaker varchar(50),
	location varchar(40) not null,
	date_type varchar(10) not null default 'date_st',
	date datetime not null,
	category varchar(20) not null,
	register boolean not null,
	register_date_type varchar(10) default 'date_st',
	register_date datetime,
	notification boolean default false,
	publish boolean default false,
	details varchar(300),
	review_url varchar(300),
	publish_date datetime,
	edit_time timestamp default current_timestamp
);

create table recruit_info (
	recruit_id integer not null auto_increment primary key,
	username varchar(40) not null,
	publish boolean not null default false,
	details varchar(300) not null,
	edit_time timestamp default current_timestamp
);

create table published_event (
	order_id integer not null,
	event_id integer not null,
	published_date datetime not null,
	primary key (event_id, published_date)
);

create table review_read (
	published_date datetime not null primary key,
	count integer not null default 0
);

insert into users value ('admin', 'admin', 'root@lyq.me', '73a694ee2938d0d0f12531e2de0643ea', 2, 2);
