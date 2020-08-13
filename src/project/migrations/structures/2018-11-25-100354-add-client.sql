CREATE TABLE `client` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `lastname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `ticket_number` VARCHAR(255) NOT NULL,
  `extra_cargo` VARCHAR(255) NOT NULL,
  `from_address` VARCHAR(255) NOT NULL,
  `to_address` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL,
  `transport_ticket_return` VARCHAR(255) NULL,
  `return_departure_datetime` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
