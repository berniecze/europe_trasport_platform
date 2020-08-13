CREATE TABLE `country`
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `destination`
ADD COLUMN country_id INT(11) NOT NULL,
ADD CONSTRAINT FOREIGN KEY(country_id) REFERENCES country(id);