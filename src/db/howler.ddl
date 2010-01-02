drop table if exists entries;
create table entries (
  entry_id char(32) primary key,
  label varchar(64) not null,
  url varchar(100) not null,
  parent_entry_id char(32) null,
  type char(1) not null,
  date_added date not null,
  prefix char(1) null
) ENGINE=InnoDB;

drop table if exists id3;
create table id3 (
  entry_id char(32) primary key,
  artist varchar(64) null,
  album varchar(64) null,
  title varchar(64) null,
  foreign key (entry_id) references entries(entry_id)
) ENGINE=InnoDB;

drop table if exists playlists;
create table playlists (
  playlist_id varchar(32) primary key,
  user_id varchar(32) not null,
  name varchar(32) not null
) ENGINE=InnoDB;

drop table if exists playlist_entries;
create table playlist_entries (
  playlist_id varchar(32) not null,
  entry_id varchar(32) not null,
  foreign key (playlist_id) references playlists(playlist_id),
  foreign key (entry_id) references entries(entry_id)
) ENGINE=InnoDB;