CREATE TABLE `transport` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `active` INT(1) NOT NULL DEFAULT 0,
  `name` VARCHAR(255) NOT NULL,
  `photo_url` VARCHAR(255) NOT NULL,
  `capacity` INT(11) NOT NULL,
  `luggage` INT(11) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 1.00,
  `multiplier_price` decimal(8,2) NOT NULL DEFAULT 1.00,
  `fixed_price` decimal(8,2) NOT NULL DEFAULT 1.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
