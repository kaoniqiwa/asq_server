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