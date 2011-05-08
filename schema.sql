SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `status` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `shipping_price` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `vat_rate` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `billing_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_addr1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `billing_addr2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `billing_addr3` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `billing_postal_code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_addr1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_addr2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_addr3` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_postal_code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `order_products`;
CREATE TABLE `order_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `variation_id` int(10) unsigned DEFAULT NULL,
  `quantity` mediumint(8) unsigned NOT NULL,
  `price` decimal(8,2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(8,2) unsigned NOT NULL,
  `sale_price` decimal(8,2) unsigned DEFAULT NULL,
  `quantity` mediumint(8) unsigned DEFAULT NULL,
  `primary_photo_id` int(10) unsigned DEFAULT NULL,
  `avg_review_rating` decimal(3,1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quantity` (`quantity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `order` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `product_categories_products`;
CREATE TABLE `product_categories_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product` (`product_id`),
  KEY `fk_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `product_photos`;
CREATE TABLE `product_photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id_filename` (`product_id`,`filename`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE `product_reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rating` tinyint(2) unsigned NOT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `date` (`date`),
  KEY `rating` (`rating`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
DROP TRIGGER IF EXISTS `avg_review_rating_ins`;
DELIMITER //
CREATE TRIGGER `avg_review_rating_ins` AFTER INSERT ON `product_reviews`
 FOR EACH ROW BEGIN
    SELECT AVG(rating) INTO @avg_rating FROM product_reviews WHERE product_id = NEW.product_id;
    UPDATE products SET avg_review_rating = @avg_rating WHERE id = NEW.product_id;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `avg_review_rating_up`;
DELIMITER //
CREATE TRIGGER `avg_review_rating_up` AFTER UPDATE ON `product_reviews`
 FOR EACH ROW BEGIN
    SELECT AVG(rating) INTO @avg_rating FROM product_reviews WHERE product_id = NEW.product_id;
    UPDATE products SET avg_review_rating = @avg_rating WHERE id = NEW.product_id;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `avg_review_rating_del`;
DELIMITER //
CREATE TRIGGER `avg_review_rating_del` AFTER DELETE ON `product_reviews`
 FOR EACH ROW BEGIN
    SELECT AVG(rating) INTO @avg_rating FROM product_reviews WHERE product_id = OLD.product_id;
    UPDATE products SET avg_review_rating = @avg_rating WHERE id = OLD.product_id;
END
//
DELIMITER ;

DROP TABLE IF EXISTS `product_specifications`;
CREATE TABLE `product_specifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `product_variations`;
CREATE TABLE `product_variations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_categories_products`
  ADD CONSTRAINT `product_categories_products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_photos`
  ADD CONSTRAINT `product_photos_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_variations`
  ADD CONSTRAINT `product_variations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
