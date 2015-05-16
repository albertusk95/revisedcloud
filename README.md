# revisedcloud

1. Download file yang ada di repositori ini
2. Buat database dengan nama dbcloud
3. Buat tabel `users` dan `ci_sessions` dan `track_record`

SQL script untuk tabel `track_record`
  CREATE TABLE IF NOT EXIST `track_record` (
    `id` INT NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `file_folder` VARCHAR (300) NOT NULL,
    `tanggal` INT(5) NOT NULL,
    `bulan` INT(20) NOT NULL,
    `tahun` INT(10) NOT NULL,
    `jam` INT(5) NOT NULL,
    `menit` INT(5) NOT NULL,
    `detik` INT(5) NOT NULL,
    `tipe_data` VARCHAR(200) NOT NULL
  );

SQL script untuk tabel `users`:

    CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(128) NOT NULL,
  `is_admin` INT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

SQL script untuk tabel `ci_sessions`:

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (id),
  KEY `ci_sessions_timestamp` (`timestamp`)
);
## To Do List

- [ ] **USER FEATURES**
  - [X] Upload
  - [X] All extensions
  - [X] Sort by
  - [ ] Personalize
- [ ] **ADMIN FEATURES**
  - [X] Upload
  - [X] User management (add edit delete)
  - [ ] Configure
  - [X] Logs
- [ ] **BONUS**
  - [ ] Edit on the spot
  - [ ] File viewer
  - [ ] File sharing

