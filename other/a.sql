create table member(
  seq int(11) primary key auto_increment,
  id varchar(255) not null,
  name varchar(255),
  gender enum('男','女'),
  phone varchar(255),
  e_mail varchar(255),
  post_code varchar(255),
  address varchar(255),
  unique(id)
);



create table doctor(
  seq int(11) primary key auto_increment,
  id varchar(255) unique,
  cid varchar(255) unique,
  name varchar(255),
  level varchar(255),
  dept varchar(255),
  phone varchar(255) unique,
  create_time timestamp default current_timestamp,
  update_time timestamp default current_timestamp
);


create table  orders (
  seq int(11) primary key auto_increment,
 id varchar(255) unique,
  mid varchar(255) unique,
  phone varchar(255) unique,
  order_type varchar(255),
  price int(11),
  create_time timestamp default current_timestamp,
  update_time timestamp default current_timestamp
);
