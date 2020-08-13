CREATE TABLE `page` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `template` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `active` INT(1) NOT NULL DEFAULT 0,
  `seo_description` TEXT DEFAULT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `seo_keywords` VARCHAR(255) DEFAULT NULL,
  `show_search_form` TINYINT(1) DEFAULT 0,
  `search_default_from` INT(11) DEFAULT NULL,
  `search_default_to` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (search_default_from) REFERENCES destination(id),
  FOREIGN KEY (search_default_to) REFERENCES destination(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
