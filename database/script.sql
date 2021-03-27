-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 09 Oca 2020, 17:58:36
-- Sunucu sürümü: 10.4.11-MariaDB
-- PHP Sürümü: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `bns`
--

DELIMITER $$
--
-- Yordamlar
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_rate_to_product` (IN `buyer_nickname` VARCHAR(100), IN `seller_nickname` VARCHAR(100), IN `product_id` INT, IN `comment` VARCHAR(255), IN `rate` INT)  BEGIN
	DECLARE rate_date date;
	SET rate_date = CURDATE();
	INSERT INTO product_rate(buyer_nickname,seller_nickname,product_id,rate_date,comment,rate) VALUES (buyer_nickname,seller_nickname,product_id,rate_date,comment,rate);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_to_bucket` (`buyer_nickname` VARCHAR(100), `product_id` INT(11), `seller_nickname` VARCHAR(100), `amount` INT(11))  begin
	DECLARE bucket_id int(11);
	DECLARE stock int(11);
	DECLARE previous_amount int(11);
	DECLARE current_amount int(11);
	
	SET bucket_id = get_bucket_id(buyer_nickname);
	SET stock = get_stock_product(seller_nickname,product_id);

	IF (stock is not null and stock >= amount) then
		IF ( bucket_id is null or is_there_payment(bucket_id) ) = true then
			insert into bucket(buyer_nickname) values(buyer_nickname);
			SET bucket_id = get_bucket_id(buyer_nickname);
		END IF;
		IF is_product_in_bucket(bucket_id,seller_nickname,product_id) = true then 
			SET previous_amount = get_product_in_bucket_previous_amount(bucket_id,seller_nickname,product_id);
			SET current_amount = previous_amount + amount;
			UPDATE product_in_bucket
			SET product_in_bucket.amount = current_amount 
			WHERE product_in_bucket.bucket_id = bucket_id AND product_in_bucket.seller_nickname = seller_nickname AND product_in_bucket.product_id = product_id;
		ELSE
			insert into product_in_bucket values(bucket_id,seller_nickname,product_id,amount,0);
		END IF;
	END IF;	
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_categories` (IN `name` VARCHAR(255))  begin
	IF is_there_category(name) = false THEN
		insert into category(name) values(name);
	END IF;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_person` (IN `e_mail` VARCHAR(100), IN `password` VARCHAR(100), IN `nickname` VARCHAR(100), IN `name` VARCHAR(50), IN `lastname` VARCHAR(50), IN `address` VARCHAR(255), IN `phone` VARCHAR(20), IN `DoB` DATE, IN `kind` VARCHAR(20))  begin
	IF is_there_person(nickname) = false then
		IF is_there_registration(e_mail) = false then
			insert into registration values(e_mail,password);
		END IF;
		insert into person values(e_mail,nickname,name,lastname,address,phone,DoB,'uploads/default.jpg');
		IF  kind = 'seller' THEN
			insert into seller values(nickname,0);
		ELSE
			insert into buyer values(nickname,0);
		END IF;
	END IF;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `make_payment` (`buyer_nickname` VARCHAR(100), `payment_date` DATE, `payment_type` VARCHAR(100), `payers_name` VARCHAR(100), `number` INT, `cvc` INT)  begin
	DECLARE bucket_id int(11);
	DECLARE totalPrice int;
    DECLARE payment_time TIME;
	
	SET bucket_id = get_bucket_id(buyer_nickname);
	SET totalPrice = get_bucket_total_price(bucket_id);
	SET payment_time = CURRENT_TIME;
	IF bucket_id is not null then
		insert into payment values(bucket_id,payers_name,payment_date,payment_time,totalPrice);
	
		IF payment_type = 'CREDITCARD' then
			insert into creditCard values(bucket_id,number,cvc);
		ELSEIF payment_type = 'PAYPAL' then
			insert into paypal values(bucket_id,number);
		ELSE
			insert into eft values(bucket_id,number);
		END IF;
		
	END IF;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_the_stock_and_price` (IN `product_brand` VARCHAR(100), IN `product_name` VARCHAR(100), IN `category_name` VARCHAR(100), IN `seller_nickname` VARCHAR(100), IN `stock` INT(11), IN `price` FLOAT, IN `new_img` VARCHAR(100))  begin
    DECLARE product_id int(11);
	DECLARE flag boolean;
	DECLARE category_id int(11);
	DECLARE isseller varchar(100);
	SET category_id = get_category_id(LOWER(category_name));
	SET product_id = get_product_id(LOWER(product_brand),LOWER(product_name));
	SET isseller = is_there_seller(lower(seller_nickname));
	IF isseller = true THEN
			   IF product_id is null THEN
							insert into product(brand,name,category_id) values(LOWER(product_brand),LOWER(product_name),LOWER(category_id));
							SET product_id = get_product_id(LOWER(product_brand),LOWER(product_name));
							insert into stock_product values(seller_nickname,product_id,stock,price,new_img);
			   ELSE
							SET flag = is_there_stock_product(seller_nickname,product_id);
							IF flag = true THEN
									UPDATE stock_product
									SET stock_product.stock = stock , stock_product.price = price , stock_product.product_img_path = new_img
									WHERE stock_product.seller_nickname = seller_nickname AND stock_product.product_id = product_id;
							ELSE
									 insert into stock_product values(seller_nickname,product_id,stock,price,new_img);
							END IF ;
				END IF;
				
	END IF;

end$$

--
-- İşlevler
--
CREATE DEFINER=`root`@`localhost` FUNCTION `get_bucket_id` (`buyer_nickname` VARCHAR(100)) RETURNS INT(11) BEGIN
	DECLARE bucket_id int;
    	SET bucket_id = (SELECT MAX(id)
    		     FROM bucket
		     WHERE bucket.buyer_nickname = buyer_nickname); 
	return bucket_id;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_bucket_total_price` (`bucket_id` INT(11)) RETURNS INT(11) BEGIN
	DECLARE price int;
    	SET price = (SELECT SUM(p.amount*s.price)
    		     FROM product_in_bucket AS p ,stock_product AS s
		     WHERE p.bucket_id = bucket_id AND p.seller_nickname = s.seller_nickname  AND p.product_id = s.product_id);
	return price;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_category_id` (`category_name` VARCHAR(100)) RETURNS INT(11) BEGIN
	DECLARE cat_id int(11);
	SET cat_id = NULL;
    
    SET cat_id =  (SELECT category.id
    		FROM category
    		WHERE category.name = category_name);
    
	RETURN cat_id;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_product_id` (`brand` VARCHAR(100), `name` VARCHAR(255)) RETURNS INT(11) BEGIN
	DECLARE product_id_var int(11);
	SET product_id_var = NULL;
    
    SET product_id_var = (SELECT product.id
    		      FROM product
    		      WHERE product.name = name AND product.brand = brand );
    
	RETURN product_id_var;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_product_in_bucket_previous_amount` (`bucket_id` INT(11), `seller_nickname` VARCHAR(100), `product_id` INT(11)) RETURNS INT(11) BEGIN
	DECLARE amount int(11);
    
    SET amount = (SELECT p.amount
    		FROM product_in_bucket AS p
    		WHERE p.bucket_id = bucket_id AND p.seller_nickname = seller_nickname AND p.product_id = product_id);
    
	RETURN amount;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_stock_product` (`seller_nickname` VARCHAR(100), `productid` VARCHAR(100)) RETURNS INT(11) BEGIN
	DECLARE stock int(11);
    	SET stock = (SELECT stock_product.stock
    		     FROM stock_product
		     WHERE stock_product.seller_nickname = seller_nickname AND stock_product.product_id = productid);
	RETURN stock;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_product_in_bucket` (`bucket_id` INT(11), `seller_nickname` VARCHAR(100), `product_id` INT(11)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int(11);
    
    SET flag = (SELECT COUNT(*)
    		FROM product_in_bucket AS p
    		WHERE p.bucket_id = bucket_id AND p.seller_nickname = seller_nickname AND p.product_id = product_id);
    
	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_there_buyer` (`buyer_nickname` VARCHAR(100)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int(11);
    	SET flag = (SELECT COUNT(*)
    		     	FROM buyer
			WHERE buyer.nickname = buyer_nickname);

	
	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_there_category` (`category_name` VARCHAR(255)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int(11);
    
    SET flag = ( SELECT COUNT(*) 
    FROM category
    WHERE category.name = category_name ) ;
    
	IF  flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_there_payment` (`bucket_id` INT(11)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int;
    SET flag = (SELECT COUNT(*)
    		FROM payment
		WHERE payment.bucket_id = bucket_id );

	
	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_there_person` (`nickname` VARCHAR(100)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int(11);
    
    SET flag = ( SELECT COUNT(*)
    FROM person
    WHERE person.nickname = nickname  );
    
	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_there_registration` (`e_mail` VARCHAR(100)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int(11);
    
    SET flag = ( SELECT COUNT(*) 
    FROM registration
    WHERE registration.e_mail = e_mail ) ;
    
	IF flag = 0 THEN
		RETURN (false);
	ELSE 
		RETURN (true);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_there_seller` (`seller_nickname` VARCHAR(100)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int(11);
    	SET flag = (SELECT COUNT(*)
    		     	FROM seller
			WHERE seller.nickname = seller_nickname);

	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_there_stock_product` (`seller_nickname` VARCHAR(100), `product_id` INT(11)) RETURNS TINYINT(1) BEGIN
	DECLARE flag int(11);
    
    SET flag = ( SELECT COUNT(*) 
    FROM stock_product
    WHERE stock_product.seller_nickname = seller_nickname AND stock_product.product_id = product_id ) ;
    
	IF  flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `all_products`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `all_products` (
`id` int(11)
,`category_name` varchar(255)
,`brand` varchar(100)
,`name` varchar(255)
,`seller_nickname` varchar(100)
,`stock` int(11)
,`price` float
);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bucket`
--

CREATE TABLE `bucket` (
  `id` int(11) NOT NULL,
  `buyer_nickname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `bucket`
--

INSERT INTO `bucket` (`id`, `buyer_nickname`) VALUES
(1, 'saidkaya1239'),
(2, 'saidkaya1239');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `buyer`
--

CREATE TABLE `buyer` (
  `nickname` varchar(100) NOT NULL,
  `ranking` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `buyer`
--

INSERT INTO `buyer` (`nickname`, `ranking`) VALUES
('saidkaya1239', 2),
('YüzKel', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'ELECTRONIC'),
(2, 'COMPUTER/TABLET'),
(3, 'PHONE/ACCESSORY'),
(4, 'ANIME'),
(5, 'HOUSEHOLD APPLIANCES'),
(6, 'CLOTHES'),
(7, 'SHOES/BAGS'),
(8, 'WATCH'),
(9, 'JEWELRY'),
(10, 'HOME/LIFE/STATIONERY/OFICE'),
(11, 'FURNITURE'),
(12, 'HOME&KITCHEN DEVİCES'),
(13, 'OTOMOBILE/GARDEN/HARDWARE'),
(14, 'TIRE/RIM'),
(15, 'HARDWARE'),
(16, 'SPOR/OUTDOOR'),
(17, 'FITNESS/CONDITION'),
(18, 'FUTBOLL'),
(19, 'SUITCASE/VALIS/BAG'),
(20, 'COSMETIC/PERSONAL CARE'),
(21, 'PARFUME'),
(22, 'MAKEUP'),
(23, 'BABY FOODS'),
(24, 'OTHERS');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `creditcard`
--

CREATE TABLE `creditcard` (
  `bucket_id` int(11) NOT NULL,
  `card_number` varchar(100) DEFAULT NULL,
  `cvc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `creditcard`
--

INSERT INTO `creditcard` (`bucket_id`, `card_number`, `cvc`) VALUES
(1, '123', 983);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `eft`
--

CREATE TABLE `eft` (
  `bucket_id` int(11) NOT NULL,
  `eft_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `my_bucket`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `my_bucket` (
`product_img_path` varchar(100)
,`bucket_id` int(11)
,`product_id` int(11)
,`buyer_nickname` varchar(100)
,`seller_nickname` varchar(100)
,`brand` varchar(100)
,`name` varchar(255)
,`amount` int(11)
,`price` float
,`total` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `my_orders`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `my_orders` (
`product_id` int(11)
,`bucket_id` int(11)
,`is_rated` int(11)
,`buyer_nickname` varchar(100)
,`brand` varchar(100)
,`name` varchar(255)
,`seller_nickname` varchar(100)
,`amount` int(11)
,`unit_price` float
,`totalPrice` int(11)
,`payment_date` date
,`payment_time` time
);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payment`
--

CREATE TABLE `payment` (
  `bucket_id` int(11) NOT NULL,
  `payers_name` varchar(100) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_time` time DEFAULT NULL,
  `totalPrice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `payment`
--

INSERT INTO `payment` (`bucket_id`, `payers_name`, `payment_date`, `payment_time`, `totalPrice`) VALUES
(1, 'Muhammed Said Kaya', '2020-01-09', '13:36:17', 240);

--
-- Tetikleyiciler `payment`
--
DELIMITER $$
CREATE TRIGGER `reduce_stock` AFTER INSERT ON `payment` FOR EACH ROW UPDATE stock_product 
    JOIN product_in_bucket ON product_in_bucket.product_id = stock_product.product_id
    SET stock_product.stock = stock_product.stock - product_in_bucket.amount
    WHERE (product_in_bucket.bucket_id = NEW.bucket_id )
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_buyer_rate` AFTER INSERT ON `payment` FOR EACH ROW UPDATE buyer
    SET buyer.ranking = buyer.ranking + (SELECT totalPrice / 100 FROM payment AS p, bucket AS b WHERE p.bucket_id = b.id AND p.bucket_id = NEW.bucket_id )
    WHERE nickname = (SELECT buyer_nickname FROM payment AS p, bucket AS b WHERE p.bucket_id = b.id AND p.bucket_id = NEW.bucket_id )
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `paypal`
--

CREATE TABLE `paypal` (
  `bucket_id` int(11) NOT NULL,
  `paypal_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `person`
--

CREATE TABLE `person` (
  `reg_e_mail` varchar(100) DEFAULT NULL,
  `nickname` varchar(100) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `DoB` date DEFAULT NULL,
  `profile_img_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `person`
--

INSERT INTO `person` (`reg_e_mail`, `nickname`, `name`, `lastname`, `address`, `phone`, `DoB`, `profile_img_path`) VALUES
('emrehanci@gmail.com', 'avukedNazar', 'Emre', 'Hancı', 'Macunköy/Ankara', '5512583307', '1995-11-15', 'uploads/Image.jpeg'),
('furkan@yanii.com', 'Leqra', 'Furkan', 'Kaya', 'Yenimahalle/Ankara', '5062987162', '1994-07-09', 'uploads/IMG_20200108_154037.jpg'),
('lordking_said@hotmail.com', 'Lord', 'Muhammed', 'Kaya', 'Karşıyaka Mezarlığı', '5459328081', '1998-01-30', 'uploads/IMG_20191214_104953.jpg'),
('oguzhan@gmail.com', 'Okuzhan', 'Oğuzhan', 'Eroğlu', 'Beytepe', '5314313131', '1998-01-31', 'uploads/IMG_20191223_123549.jpg'),
('saidkaya1239@gmail.com', 'saidkaya1239', 'Muhammed Said', 'Kaya', 'Çiğdemtepe Mahallesi 1116.sokak 21/14', '5459328081', '1998-01-30', 'uploads/said.jpg'),
('mertcokelek@gmail.com', 'Term', 'Mert', 'Çökelek', 'Mamak', '5062989878', '1998-01-01', 'uploads/IMG_20190522_105409.jpg'),
('alikayadibi@gmail.com', 'ToCake', 'Ali', 'Kayadibi', 'Mamak Çöplük Yanı', '5459876767', '1998-08-20', 'uploads/P91128-172953.jpg'),
('yk06@gmail.com', 'YüzKel', 'Yüksel', 'Kaya', 'Yıldız', '5062987161', '1974-08-20', 'uploads/IMG_20190120_182429.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `product`
--

INSERT INTO `product` (`id`, `brand`, `name`, `category_id`) VALUES
(1, 'gate', '74hc08 and ', 1),
(2, 'gate', 'cd4071 or', 1),
(3, 'gate', '74hc86 xor', 1),
(4, 'casper', 'excalibur g770', 2),
(5, 'acer', 'aspire a315-42g', 2),
(6, 'hp', 'pavilion 15-ec007nt', 2),
(7, 'xiaomi', 'redmi note 8 64 gb', 3),
(8, 'xiaomi', 'redmi note 8 pro 64 gb', 3),
(9, 'xiaomi', 'redmi 8a 32 gb', 3),
(10, 'profilo', 'cmg120dtr a+++ 9 kg 1200 devir çamaşır makinesi', 5),
(11, 'samsung', 'rt46k6000ww/tr a+ 468 it no-frost buzdolabı', 5),
(12, '-', 'haikyuu t-shirt', 4),
(13, '-', 'fairy tail t-shirt', 4),
(14, '-', 'one punch man t-shirt', 4),
(15, '-', 'vocaloid miku hatsune t-shirt', 4),
(16, '-', 'naruto anime sweatshirts cosplay costume hoodies', 4),
(17, '-', 'my hero academia pilow', 4),
(18, 'koton', 'cep detaylı kazak', 6),
(19, 'koton', 'sweatshirt', 6),
(20, 'koton', 'boğazlı kazak', 6),
(21, 'harley davidson', 'harley davidson jim black yağli deri unisex bot', 7),
(22, 'caterpillar', 'colorado zip wp 015f1073 siyah', 7),
(23, 'kinetix', 'sarı klasik bot rainor modeli', 7),
(24, 'rolex', 'datajust 78278 36 mm full altın kol saati', 8),
(25, 'kaçak', 'sigara', 24);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_in_bucket`
--

CREATE TABLE `product_in_bucket` (
  `bucket_id` int(11) NOT NULL,
  `seller_nickname` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `is_rated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `product_in_bucket`
--

INSERT INTO `product_in_bucket` (`bucket_id`, `seller_nickname`, `product_id`, `amount`, `is_rated`) VALUES
(1, 'avukedNazar', 12, 2, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_rate`
--

CREATE TABLE `product_rate` (
  `id` int(11) NOT NULL,
  `buyer_nickname` varchar(100) DEFAULT NULL,
  `seller_nickname` varchar(100) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rate_date` date DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `rate` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `product_rate`
--

INSERT INTO `product_rate` (`id`, `buyer_nickname`, `seller_nickname`, `product_id`, `rate_date`, `comment`, `rate`) VALUES
(1, 'saidkaya1239', 'avukedNazar', 12, '2020-01-09', 'Well', 5);

--
-- Tetikleyiciler `product_rate`
--
DELIMITER $$
CREATE TRIGGER `update_seller_rate` AFTER INSERT ON `product_rate` FOR EACH ROW UPDATE seller 
    SET seller.rate=(SELECT AVG(rate) from product_rate where NEW.seller_nickname = product_rate.seller_nickname )
    WHERE nickname = NEW.seller_nickname
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `registration`
--

CREATE TABLE `registration` (
  `e_mail` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `registration`
--

INSERT INTO `registration` (`e_mail`, `password`) VALUES
('alikayadibi@gmail.com', '$2y$10$NHSFrxvr/xKXUHRFwM9KrO0whwvV9ADKCQQcyprna7JhJa7eVArge'),
('emrehanci@gmail.com', '$2y$10$exY0JyK0KAnlxsQeVuhZaeP0i1UkaUHxfxIqd7lE/t7rfDuqYbqJy'),
('furkan@yanii.com', '$2y$10$huwqClmnNUgClApoiN.eNuHRHdyjR/NtW/jM.dU2lAoXMRsD1k95i'),
('lordking_said@hotmail.com', '$2y$10$/98qjo158slBDewf1VObROt0nkMv6eYy/zI227ydrn1.VIcVQ124e'),
('mertcokelek@gmail.com', '$2y$10$qQB78UrCskizoZeoYIm8cOlrOmSCed3Kk5z3ML98c4uqaSF7X1p2m'),
('oguzhan@gmail.com', '$2y$10$WEnZsO9cCh2DRNbBdRq3Xewg3mdtrUhgsbU12vfa4lmdYV8me8w3C'),
('saidkaya1239@gmail.com', '$2y$10$4S6yjOxjWM2dg4FTYKrtF.w.TOb8PfCQF8nxKsEjDzrjCN7yXQnMS'),
('yk06@gmail.com', '$2y$10$QaOhdOD9GRLTgyuWIlC6fu9rHGEGZztfDadTcAvGzbjaPCWTxlakm');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seller`
--

CREATE TABLE `seller` (
  `nickname` varchar(100) NOT NULL,
  `rate` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `seller`
--

INSERT INTO `seller` (`nickname`, `rate`) VALUES
('avukedNazar', 5),
('Leqra', 0),
('Lord', 0),
('Okuzhan', 0),
('Term', 0),
('ToCake', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `stock_product`
--

CREATE TABLE `stock_product` (
  `seller_nickname` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `product_img_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `stock_product`
--

INSERT INTO `stock_product` (`seller_nickname`, `product_id`, `stock`, `price`, `product_img_path`) VALUES
('avukedNazar', 12, 148, 119.99, 'uploads2/haikyuu.jpg'),
('avukedNazar', 13, 150, 119.99, 'uploads2/fairy tail.jpg'),
('avukedNazar', 14, 150, 119.99, 'uploads2/one punch man.jpg'),
('avukedNazar', 15, 150, 119.99, 'uploads2/vocaloid miku hatsune.jpg'),
('avukedNazar', 16, 45, 319.99, 'uploads2/naruto anime sweatshirt.jpg'),
('avukedNazar', 17, 64, 179.99, 'uploads2/hero pilow.jpg'),
('avukedNazar', 18, 25, 35.99, 'uploads2/cep detaylı kazak.jpg'),
('avukedNazar', 19, 25, 35.99, 'uploads2/sweatshirt.jpg'),
('avukedNazar', 20, 25, 41.99, 'uploads2/erkek boğazlı kazak.jpg'),
('avukedNazar', 25, 20, 20, 'uploads2/kaçak sigara.jpg'),
('Leqra', 21, 32, 367, 'uploads2/harley-davidson-jim-black-yagli-deri-unisex-bot__1340470750548513.jpg'),
('Leqra', 22, 31, 567, 'uploads2/caterpillar-colorado-zip-wp-015f1073-siyah__0397493743478499.jpg'),
('Leqra', 23, 90, 149.99, 'uploads2/kinetix bot.png'),
('Leqra', 24, 5, 82.55, 'uploads2/rolex.png'),
('Lord', 1, 100, 4.75, 'uploads2/and.jpg'),
('Lord', 2, 100, 3.75, 'uploads2/or.jpeg'),
('Lord', 3, 70, 7.75, 'uploads2/xor.jpg'),
('Lord', 4, 35, 5699, 'uploads2/casper pc.jpg'),
('Lord', 5, 35, 2998.99, 'uploads2/acer pc.jpg'),
('Lord', 6, 35, 4879, 'uploads2/hp pc.jpg'),
('Lord', 7, 68, 1559.9, 'uploads2/redminote8.jpg'),
('Lord', 8, 150, 2069.9, 'uploads2/redminote8pro.jpg'),
('Lord', 9, 99, 899.9, 'uploads2/redmi8a.jpg'),
('Lord', 10, 45, 2091.57, 'uploads2/profiloçamaşır.jpg'),
('Lord', 11, 20, 2769, 'uploads2/samsungfrostbuzdolabı.jpg'),
('ToCake', 25, 200, 2, 'uploads2/kaçak sigara.jpg');

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `users`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `users` (
`e_mail` varchar(100)
,`password` varchar(100)
,`nickname` varchar(100)
,`name` varchar(50)
,`lastname` varchar(50)
,`address` varchar(255)
,`phone` varchar(20)
,`DoB` date
);

-- --------------------------------------------------------

--
-- Görünüm yapısı `all_products`
--
DROP TABLE IF EXISTS `all_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_products`  AS  select `p`.`id` AS `id`,`c`.`name` AS `category_name`,`p`.`brand` AS `brand`,`p`.`name` AS `name`,`sp`.`seller_nickname` AS `seller_nickname`,`sp`.`stock` AS `stock`,`sp`.`price` AS `price` from ((`product` `p` join `stock_product` `sp`) join `category` `c`) where `p`.`id` = `sp`.`product_id` and `p`.`category_id` = `c`.`id` order by `p`.`id` ;

-- --------------------------------------------------------

--
-- Görünüm yapısı `my_bucket`
--
DROP TABLE IF EXISTS `my_bucket`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `my_bucket`  AS  select `sp`.`product_img_path` AS `product_img_path`,`pb`.`bucket_id` AS `bucket_id`,`p`.`id` AS `product_id`,`b`.`buyer_nickname` AS `buyer_nickname`,`sp`.`seller_nickname` AS `seller_nickname`,`p`.`brand` AS `brand`,`p`.`name` AS `name`,`pb`.`amount` AS `amount`,`sp`.`price` AS `price`,cast(`pb`.`amount` * `sp`.`price` as decimal(10,2)) AS `total` from (((`bucket` `b` join `product_in_bucket` `pb`) join `stock_product` `sp`) join `product` `p`) where `b`.`id` = `pb`.`bucket_id` and `pb`.`seller_nickname` = `sp`.`seller_nickname` and `pb`.`product_id` = `sp`.`product_id` and `sp`.`product_id` = `p`.`id` and !(`b`.`id` in (select `pay`.`bucket_id` from `payment` `pay`)) ;

-- --------------------------------------------------------

--
-- Görünüm yapısı `my_orders`
--
DROP TABLE IF EXISTS `my_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `my_orders`  AS  select `sp`.`product_id` AS `product_id`,`pa`.`bucket_id` AS `bucket_id`,`pb`.`is_rated` AS `is_rated`,`b`.`buyer_nickname` AS `buyer_nickname`,`p`.`brand` AS `brand`,`p`.`name` AS `name`,`pb`.`seller_nickname` AS `seller_nickname`,`pb`.`amount` AS `amount`,`sp`.`price` AS `unit_price`,`pa`.`totalPrice` AS `totalPrice`,`pa`.`payment_date` AS `payment_date`,`pa`.`payment_time` AS `payment_time` from ((((`bucket` `b` join `payment` `pa`) join `product_in_bucket` `pb`) join `stock_product` `sp`) join `product` `p`) where `b`.`id` = `pa`.`bucket_id` and `b`.`id` = `pb`.`bucket_id` and `pb`.`product_id` = `sp`.`product_id` and `sp`.`seller_nickname` = `pb`.`seller_nickname` and `sp`.`product_id` = `p`.`id` ;

-- --------------------------------------------------------

--
-- Görünüm yapısı `users`
--
DROP TABLE IF EXISTS `users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `users`  AS  select `r`.`e_mail` AS `e_mail`,`r`.`password` AS `password`,`p`.`nickname` AS `nickname`,`p`.`name` AS `name`,`p`.`lastname` AS `lastname`,`p`.`address` AS `address`,`p`.`phone` AS `phone`,`p`.`DoB` AS `DoB` from (`registration` `r` join `person` `p`) where `r`.`e_mail` = `p`.`reg_e_mail` ;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bucket`
--
ALTER TABLE `bucket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_nickname` (`buyer_nickname`);

--
-- Tablo için indeksler `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`nickname`);

--
-- Tablo için indeksler `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`bucket_id`);

--
-- Tablo için indeksler `eft`
--
ALTER TABLE `eft`
  ADD PRIMARY KEY (`bucket_id`);

--
-- Tablo için indeksler `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`bucket_id`);

--
-- Tablo için indeksler `paypal`
--
ALTER TABLE `paypal`
  ADD PRIMARY KEY (`bucket_id`);

--
-- Tablo için indeksler `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`nickname`),
  ADD KEY `reg_e_mail` (`reg_e_mail`);

--
-- Tablo için indeksler `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `searchBrand` (`brand`),
  ADD KEY `searchName` (`name`);

--
-- Tablo için indeksler `product_in_bucket`
--
ALTER TABLE `product_in_bucket`
  ADD PRIMARY KEY (`bucket_id`,`seller_nickname`,`product_id`),
  ADD KEY `seller_nickname` (`seller_nickname`,`product_id`);

--
-- Tablo için indeksler `product_rate`
--
ALTER TABLE `product_rate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`,`seller_nickname`),
  ADD KEY `buyer_nickname` (`buyer_nickname`);

--
-- Tablo için indeksler `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`e_mail`);

--
-- Tablo için indeksler `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`nickname`);

--
-- Tablo için indeksler `stock_product`
--
ALTER TABLE `stock_product`
  ADD PRIMARY KEY (`seller_nickname`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bucket`
--
ALTER TABLE `bucket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `product_rate`
--
ALTER TABLE `product_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `bucket`
--
ALTER TABLE `bucket`
  ADD CONSTRAINT `bucket_ibfk_1` FOREIGN KEY (`buyer_nickname`) REFERENCES `buyer` (`nickname`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `buyer`
--
ALTER TABLE `buyer`
  ADD CONSTRAINT `buyer_ibfk_1` FOREIGN KEY (`nickname`) REFERENCES `person` (`nickname`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `creditcard_ibfk_1` FOREIGN KEY (`bucket_id`) REFERENCES `payment` (`bucket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `eft`
--
ALTER TABLE `eft`
  ADD CONSTRAINT `eft_ibfk_1` FOREIGN KEY (`bucket_id`) REFERENCES `payment` (`bucket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`bucket_id`) REFERENCES `bucket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `paypal`
--
ALTER TABLE `paypal`
  ADD CONSTRAINT `paypal_ibfk_1` FOREIGN KEY (`bucket_id`) REFERENCES `payment` (`bucket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_ibfk_1` FOREIGN KEY (`reg_e_mail`) REFERENCES `registration` (`e_mail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `product_in_bucket`
--
ALTER TABLE `product_in_bucket`
  ADD CONSTRAINT `product_in_bucket_ibfk_1` FOREIGN KEY (`bucket_id`) REFERENCES `bucket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_in_bucket_ibfk_2` FOREIGN KEY (`seller_nickname`,`product_id`) REFERENCES `stock_product` (`seller_nickname`, `product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `product_rate`
--
ALTER TABLE `product_rate`
  ADD CONSTRAINT `product_rate_ibfk_1` FOREIGN KEY (`product_id`,`seller_nickname`) REFERENCES `stock_product` (`product_id`, `seller_nickname`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_rate_ibfk_2` FOREIGN KEY (`buyer_nickname`) REFERENCES `buyer` (`nickname`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `seller`
--
ALTER TABLE `seller`
  ADD CONSTRAINT `seller_ibfk_1` FOREIGN KEY (`nickname`) REFERENCES `person` (`nickname`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `stock_product`
--
ALTER TABLE `stock_product`
  ADD CONSTRAINT `stock_product_ibfk_1` FOREIGN KEY (`seller_nickname`) REFERENCES `seller` (`nickname`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
