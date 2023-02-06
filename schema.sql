CREATE TABLE filenames (
  id int(10) UNSIGNED NOT NULL,
  placeId int(10) UNSIGNED DEFAULT NULL,
  filename varchar(255) NOT NULL,
  bytes int(11) UNSIGNED DEFAULT NULL,
  mtime timestamp NULL DEFAULT NULL,
  md5 char(32) DEFAULT NULL,
  lastUpdate timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE places (
  id int(10) UNSIGNED NOT NULL,
  hostname varchar(16) NOT NULL,
  path varchar(32) NOT NULL,
  comment varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE filenames
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY file_uniq (placeId,filename) USING BTREE;

ALTER TABLE places
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY path_uniq (hostname,path) USING BTREE;

ALTER TABLE filenames
  MODIFY id int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE places
  MODIFY id int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE filenames
  ADD CONSTRAINT filenames_ibfk_1 FOREIGN KEY (placeId) REFERENCES places (id);
