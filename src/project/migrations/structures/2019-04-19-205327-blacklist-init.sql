CREATE TABLE `blacklist` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transport_id` INT(11) NOT NULL,
  `from_date` DATETIME NOT NULL,
  `to_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`transport_id`) REFERENCES `transport` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
