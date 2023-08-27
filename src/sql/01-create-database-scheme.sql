begin;

drop table if exists hardwares;
create table hardwares
(
    "id" serial primary key not null,
    "unique_number" serial not null
);

drop table if exists characteristics;
create table characteristics
(
    "id" serial primary key not null,
    "name" text not null
);

drop table if exists hardwares_characteristics;
create table hardwares_characteristics
(
    "id" serial primary key not null,
    "hardware_id" serial not null references hardwares(id) on delete cascade,
    "characteristic_id" serial not null references characteristics(id) on delete cascade,
    "value" text not null
);

drop table if exists users;
create table users
(
    "id" serial primary key not null,
    "user" text unique not null,
    "password" text not null
);

commit;
