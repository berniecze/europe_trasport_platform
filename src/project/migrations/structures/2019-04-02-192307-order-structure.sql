CREATE TABLE `client_order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `number` VARCHAR(255) NOT NULL,
  `pay_id` VARCHAR(255) NOT NULL,
  `cart_id` INT(11) NULL,
  `status` INT NOT NULL DEFAULT 2,
  `final_price` decimal(8,2) NOT NULL,
  `created` DATETIME NOT NULL,
  `driver_id` INT(11) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON UPDATE CASCADE,
  FOREIGN KEY (`driver_id`) REFERENCES `driver`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
