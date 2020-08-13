CREATE TABLE `route` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `departure_id` INT(11) NOT NULL,
  `arrival_id` INT(11) NOT NULL,
  `price` DECIMAL(8,2) NOT NULL DEFAULT 1.0,
  `distance` INT(11) NOT NULL DEFAULT 1,
  `active` INT(1) NOT NULL DEFAULT 0,
  `duration` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`departure_id`) REFERENCES `destination` (`id`) ON UPDATE CASCADE,
  FOREIGN KEY (`arrival_id`) REFERENCES `destination` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
