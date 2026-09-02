drop database if exists permendagri_tahun_2019_nomor_90_test;
create database permendagri_tahun_2019_nomor_90_test;
use permendagri_tahun_2019_nomor_90_test;

drop table if exists perencanaan_provinsi_log;
drop table if exists perencanaan_provinsi;

create or replace table perencanaan_provinsi (
    id               varchar(15),
    kode             varchar(15),
    kode_urusan      varchar(1),
    kode_bidang      varchar(2),
    kode_program     varchar(2),
    kode_kegiatan    varchar(4),
    kode_subkegiatan varchar(2),
    nama             varchar(345),
    keterangan       varchar(30),
    created_at       date,
    created_by       varchar(255),
    updated_at       date,
    updated_by       varchar(255),
    is_deleted       bit,
    deleted_at       date,
    deleted_by       varchar(255),
    constraint ck_perencanaan_provinsi_01 check (id = replace(kode, 'X', '0')),
    constraint ck_perencanaan_provinsi_02 check (kode = concat_ws('.', kode_urusan, kode_bidang, kode_program, kode_kegiatan, kode_subkegiatan)),
    constraint ck_perencanaan_provinsi_03 check (kode_urusan regexp '^[X1-9]$'),
    constraint ck_perencanaan_provinsi_04 check (kode_bidang regexp '^XX|0[1-9]|[1-9][0-9]$'),
    constraint ck_perencanaan_provinsi_05 check (kode_program regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_perencanaan_provinsi_06 check (kode_kegiatan regexp '^[1-9]\.(0[1-9]|[1-9][0-9])$'),
    constraint ck_perencanaan_provinsi_07 check (kode_subkegiatan regexp '^0[1-9]|[1-9][0-9]$'),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

create or replace table perencanaan_provinsi_log (
    id               bigint auto_increment,
    id_reference     varchar(15),
    kode             varchar(15),
    kode_urusan      varchar(1),
    kode_bidang      varchar(2),
    kode_program     varchar(2),
    kode_kegiatan    varchar(4),
    kode_subkegiatan varchar(2),
    nama             varchar(345),
    keterangan       varchar(30),
    created_at       date,
    created_by       varchar(255),
    updated_at       date,
    updated_by       varchar(255),
    is_deleted       bit,
    deleted_at       date,
    deleted_by       varchar(255),
    logged_at        date,
    logged_by        varchar(255),
    constraint fk_perencanaan_provinsi_log_01 foreign key (id_reference) references perencanaan_provinsi (id),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

drop table if exists perencanaan_kabupaten_log;
drop table if exists perencanaan_kabupaten;

create or replace table perencanaan_kabupaten (
    id               varchar(15),
    kode             varchar(15),
    kode_urusan      varchar(1),
    kode_bidang      varchar(2),
    kode_program     varchar(2),
    kode_kegiatan    varchar(4),
    kode_subkegiatan varchar(2),
    nama             varchar(345),
    keterangan       varchar(36),
    created_at       date,
    created_by       varchar(255),
    updated_at       date,
    updated_by       varchar(255),
    is_deleted       bit,
    deleted_at       date,
    deleted_by       varchar(255),
    constraint ck_perencanaan_kabupaten_01 check (id = replace(kode, 'X', '0')),
    constraint ck_perencanaan_kabupaten_02 check (kode = concat_ws('.', kode_urusan, kode_bidang, kode_program, kode_kegiatan, kode_subkegiatan)),
    constraint ck_perencanaan_kabupaten_03 check (kode_urusan regexp '^[X1-9]$'),
    constraint ck_perencanaan_kabupaten_04 check (kode_bidang regexp '^XX|0[1-9]|[1-9][0-9]$'),
    constraint ck_perencanaan_kabupaten_05 check (kode_program regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_perencanaan_kabupaten_06 check (kode_kegiatan regexp '^[1-9]\.(0[1-9]|[1-9][0-9])$'),
    constraint ck_perencanaan_kabupaten_07 check (kode_subkegiatan regexp '^0[1-9]|[1-9][0-9]$'),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

create or replace table perencanaan_kabupaten_log (
    id               bigint auto_increment,
    id_reference     varchar(15),
    kode             varchar(15),
    kode_urusan      varchar(1),
    kode_bidang      varchar(2),
    kode_program     varchar(2),
    kode_kegiatan    varchar(4),
    kode_subkegiatan varchar(2),
    nama             varchar(323),
    keterangan       varchar(36),
    created_at       date,
    created_by       varchar(255),
    updated_at       date,
    updated_by       varchar(255),
    is_deleted       bit,
    deleted_at       date,
    deleted_by       varchar(255),
    logged_at        date,
    logged_by        varchar(255),
    constraint fk_perencanaan_kabupaten_log_01 foreign key (id_reference) references perencanaan_kabupaten (id),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

drop table if exists fungsi_log;
drop table if exists fungsi;

create or replace table fungsi (
    id             varchar(5),
    kode           varchar(5),
    kode_fungsi    varchar(2),
    kode_subfungsi varchar(2),
    nama           varchar(255),
    keterangan     varchar(30),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    constraint ck_fungsi_01 check (id = kode),
    constraint ck_fungsi_02 check (kode = concat_ws('.', kode_fungsi, kode_subfungsi)),
    constraint ck_fungsi_03 check (kode_fungsi regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_fungsi_04 check (kode_subfungsi regexp '^0[1-9]|[1-9][0-9]$'),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

create or replace table fungsi_log (
    id             bigint auto_increment,
    id_reference   varchar(5),
    kode           varchar(5),
    kode_fungsi    varchar(2),
    kode_subfungsi varchar(2),
    nama           varchar(255),
    keterangan     varchar(30),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    logged_at      date,
    logged_by      varchar(255),
    constraint fk_fungsi_log_01 foreign key (id_reference) references fungsi (id),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

drop table if exists sumber_log;
drop table if exists sumber;

create or replace table sumber (
    id             varchar(14),
    kode           varchar(14),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(1),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(2),
    nama           varchar(255),
    keterangan     varchar(2105),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    constraint ck_sumber_01 check (id = kode),
    constraint ck_sumber_02 check (kode = concat_ws('.', kode_rekening1, kode_rekening2, kode_rekening3, kode_rekening4, kode_rekening5, kode_rekening6)),
    constraint ck_sumber_03 check (kode_rekening1 regexp '^[1-2]$'),
    constraint ck_sumber_04 check (kode_rekening2 regexp '^[1-9]$'),
    constraint ck_sumber_05 check (kode_rekening3 regexp '^[1-9]$'),
    constraint ck_sumber_06 check (kode_rekening4 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_sumber_07 check (kode_rekening5 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_sumber_08 check (kode_rekening6 regexp '^0[1-9]|[1-9][0-9]$'),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

create or replace table sumber_log (
    id             bigint auto_increment,
    id_reference   varchar(14),
    kode           varchar(14),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(1),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(2),
    nama           varchar(255),
    keterangan     varchar(2105),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    logged_at      date,
    logged_by      varchar(255),
    constraint fk_sumber_log_01 foreign key (id_reference) references sumber (id),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

drop table if exists neraca_log;
drop table if exists neraca;

create or replace table neraca (
    id             varchar(16),
    kode           varchar(16),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(2),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(3),
    nama           varchar(255),
    keterangan     varchar(961),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    constraint ck_neraca_01 check (id = kode),
    constraint ck_neraca_02 check (kode = concat_ws('.', kode_rekening1, kode_rekening2, kode_rekening3, kode_rekening4, kode_rekening5, kode_rekening6)),
    constraint ck_neraca_03 check (kode_rekening1 regexp '^[1-3]$'),
    constraint ck_neraca_04 check (kode_rekening2 regexp '^[1-9]$'),
    constraint ck_neraca_05 check (kode_rekening3 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_neraca_06 check (kode_rekening4 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_neraca_07 check (kode_rekening5 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_neraca_08 check (kode_rekening6 regexp '^00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]$'),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

create or replace table neraca_log (
    id             bigint auto_increment,
    id_reference   varchar(16),
    kode           varchar(16),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(2),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(3),
    nama           varchar(255),
    keterangan     varchar(961),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    logged_at      date,
    logged_by      varchar(255),
    constraint fk_neraca_log_01 foreign key (id_reference) references neraca (id),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

drop table if exists lra_log;
drop table if exists lra;

create or replace table lra (
    id             varchar(16),
    kode           varchar(16),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(2),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(3),
    nama           varchar(255),
    keterangan     varchar(862),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    constraint ck_lra_01 check (id = kode),
    constraint ck_lra_02 check (kode = concat_ws('.', kode_rekening1, kode_rekening2, kode_rekening3, kode_rekening4, kode_rekening5, kode_rekening6)),
    constraint ck_lra_03 check (kode_rekening1 regexp '^[4-6]$'),
    constraint ck_lra_04 check (kode_rekening2 regexp '^[1-9]$'),
    constraint ck_lra_05 check (kode_rekening3 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_lra_06 check (kode_rekening4 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_lra_07 check (kode_rekening5 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_lra_08 check (kode_rekening6 regexp '^00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]$'),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

create or replace table lra_log (
    id             bigint auto_increment,
    id_reference   varchar(16),
    kode           varchar(16),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(2),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(3),
    nama           varchar(255),
    keterangan     varchar(862),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    logged_at      date,
    logged_by      varchar(255),
    constraint fk_lra_log_01 foreign key (id_reference) references lra (id),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

drop table if exists lo_log;
drop table if exists lo;

create or replace table lo (
    id             varchar(16),
    kode           varchar(16),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(2),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(3),
    nama           varchar(255),
    keterangan     varchar(1053),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    constraint ck_lo_01 check (id = kode),
    constraint ck_lo_02 check (kode = concat_ws('.', kode_rekening1, kode_rekening2, kode_rekening3, kode_rekening4, kode_rekening5, kode_rekening6)),
    constraint ck_lo_03 check (kode_rekening1 regexp '^[7-8]$'),
    constraint ck_lo_04 check (kode_rekening2 regexp '^[1-9]$'),
    constraint ck_lo_05 check (kode_rekening3 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_lo_06 check (kode_rekening4 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_lo_07 check (kode_rekening5 regexp '^0[1-9]|[1-9][0-9]$'),
    constraint ck_lo_08 check (kode_rekening6 regexp '^00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]$'),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;

create or replace table lo_log (
    id             bigint auto_increment,
    id_reference   varchar(16),
    kode           varchar(16),
    kode_rekening1 varchar(1),
    kode_rekening2 varchar(1),
    kode_rekening3 varchar(2),
    kode_rekening4 varchar(2),
    kode_rekening5 varchar(2),
    kode_rekening6 varchar(3),
    nama           varchar(255),
    keterangan     varchar(1053),
    created_at     date,
    created_by     varchar(255),
    updated_at     date,
    updated_by     varchar(255),
    is_deleted     bit,
    deleted_at     date,
    deleted_by     varchar(255),
    logged_at      date,
    logged_by      varchar(255),
    constraint fk_lo_log_01 foreign key (id_reference) references lo (id),
    primary key (id)
) engine = innodb
  charset = utf8mb4
  collate = utf8mb4_unicode_ci;