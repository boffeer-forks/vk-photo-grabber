create table if not exists album
(
  id int unsigned auto_increment
    primary key,
  vk_album_id int unsigned not null,
  vk_user_id int unsigned not null,
  title varchar(255) null,
  constraint album_vk_album_id_uindex
  unique (vk_album_id)
)
;

create table if not exists photo
(
  id int unsigned auto_increment
    primary key,
  vk_photo_id int unsigned not null,
  vk_album_id int unsigned not null,
  vk_url varchar(500) not null,
  constraint photo_vk_photo_id_uindex
  unique (vk_photo_id)
)
;

create table if not exists user
(
  id int unsigned auto_increment
    primary key,
  vk_user_id int unsigned not null,
  first_name varchar(255) null,
  last_name varchar(255) null,
  constraint vk_user_vk_user_id_uindex
  unique (vk_user_id)
)
;
