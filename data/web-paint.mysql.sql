drop table if exists `users`;
drop table if exists `images`;

create table `users`
(
    `id` bigint unsigned not null auto_increment,
    `email` varchar(64) not null,
    `psswd` varchar(32) not null,
    `username` varchar(64) not null,

    primary key(`id`)
) engine = innodb default character set = utf8;

create table `images`
(
    `id` bigint unsigned not null auto_increment,
    `user_id` bigint unsigned not null,
    `image_src` text not null,
    `title` varchar(32) not null,
    
    primary key(`id`)
) engine = innodb default character set = utf8;

insert into `users` values (1, 'jonyb@gmail.com', md5('secret'), 'jonyb');
insert into `users` values (2, 'debug@gmail.com', md5('debug'), 'debug');