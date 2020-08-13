CREATE TABLE `cart` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `route_id` INT(11) NOT NULL,
  `date` DATETIME NOT NULL,
  `hash` VARCHAR(255) NOT NULL,
  `transport_id` INT(11) NULL,
  `client_id` INT(11) NULL,
  `notes` VARCHAR (255) NULL,
  `status` INT(11) NULL,
  `passengers` INT(4) DEFAULT 0,
  `time` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`route_id`) REFERENCES `route` (`id`) ON UPDATE CASCADE,
  FOREIGN KEY (`transport_id`) REFERENCES `transport`(`id`),
  FOREIGN KEY (`client_id`) REFERENCES `client`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
