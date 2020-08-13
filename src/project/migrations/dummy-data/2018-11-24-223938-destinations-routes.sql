INSERT INTO `country` (`id`, `name`, `code`) VALUES (1, 'Czechia', 'CZE');
INSERT INTO `country` (`id`, `name`, `code`) VALUES (2, 'Germany', 'GER');


INSERT INTO `destination` (`id`, `name`, `description`, `photo`, `active`, `country_id`, `type`)
VALUES (1, 'Berlin', 'Just a capital', 'https://media.novinky.cz/087/670878-top_foto1-lxmvc.jpg?1519993805', 1, 2, 'city');

INSERT INTO `destination` (`id`, `name`, `description`, `photo`, `active`, `country_id`, `type`)
VALUES (2, 'Prague', 'Just another capital', 'https://www.thetimes.co.uk/imageserver/image/methode%2Fsundaytimes%2Fprod%2Fweb%2Fbin%2F37d4f22a-c56e-11e7-99c5-32c02fc6ba3c.jpg?crop=2250%2C1266%2C0%2C0&resize=685', 1, 1, 'city');

INSERT INTO `destination` (`id`, `name`, `description`, `photo`, `active`, `country_id`, `type`)
VALUES (3, 'Brno', 'Just a village', 'https://skrblik.cz/wp-content/uploads/tema/00-radce-brno.jpg', 1, 1, 'city');

INSERT INTO `route` (`departure_id`, `arrival_id`, `price`, `distance`, `active`, `duration`)
VALUES (1, 2, 200, 388, 1, '4hrs 15min');

INSERT INTO `route` (`departure_id`, `arrival_id`, `price`, `distance`, `active`, `duration`)
VALUES (3, 2, 100, 388, 1, '2hrs 10min');

INSERT INTO `route` (`departure_id`, `arrival_id`, `price`, `distance`, `active`, `duration`)
VALUES (3, 1, 100, 650, 1, '6hrs 30min');
