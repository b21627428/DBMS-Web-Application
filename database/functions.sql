delimiter $$
CREATE FUNCTION is_there_registration(e_mail varchar(100)) RETURNS boolean
DETERMINISTIC
BEGIN
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
DELIMITER ;
/*    ------------------------    */
delimiter $$
CREATE FUNCTION is_there_person(nickname varchar(100)) RETURNS boolean
DETERMINISTIC
BEGIN
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
DELIMITER ;
/*    ------------------------    */
delimiter $$
CREATE FUNCTION is_there_category(category_id int(11)) RETURNS boolean
DETERMINISTIC
BEGIN
	DECLARE flag int(11);
    
    SET flag = ( SELECT COUNT(*) 
    FROM category
    WHERE category.id = category_id ) ;
    
	IF  flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$
DELIMITER ;
/*    ------------------------    */
delimiter $$
CREATE FUNCTION is_there_stock_product(seller_nickname varchar(100),product_id int(11)) RETURNS boolean
DETERMINISTIC
BEGIN
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
/*    ------------------------    */
delimiter $$
CREATE FUNCTION get_product_id(brand varchar(100),name varchar(255)) RETURNS INT(11)
BEGIN
	DECLARE product_id_var int(11);
	SET product_id_var = NULL;
    
    SET product_id_var = (SELECT product.id
    		      FROM product
    		      WHERE product.name = name AND product.brand = brand );
    
	RETURN product_id_var;
END$$
DELIMITER ;
/*    ------------------------    */
delimiter $$
CREATE FUNCTION get_category_id(category_name varchar(100)) RETURNS INT(11)
BEGIN
	DECLARE cat_id int(11);
	SET cat_id = NULL;
    
    SET cat_id =  (SELECT category.id
    		FROM category
    		WHERE category.name = category_name);
    
	RETURN cat_id;
END$$
DELIMITER ;
/* --------------------------- */
delimiter $$
CREATE FUNCTION get_count_product() RETURNS INT(11)
BEGIN
	DECLARE count int(11);
    SET count = (SELECT count(product.id)
    		FROM product);
	RETURN count;
END$$
DELIMITER ;


/* --------------------------- */
delimiter $$
CREATE FUNCTION isseller(seller_nickname varchar(100)) RETURNS boolean
BEGIN
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
DELIMITER ;
/* --------------------------- */
delimiter $$
CREATE FUNCTION is_there_stock(seller_nickname varchar(100),productid varchar(100)) RETURNS INT(11)
BEGIN
	DECLARE stock int(11);
    	SET stock = (SELECT stock_product.stock
    		     FROM stock_product
		     WHERE stock_product.seller_nickname = seller_nickname AND stock_product.product_id = productid);
	RETURN stock;
END$$
DELIMITER ;
/*    ------------------------    */
delimiter $$
CREATE FUNCTION is_in_bucket(buyer_nickname varchar(100),adding_date date,product_id int(11)) RETURNS boolean
DETERMINISTIC
BEGIN
	DECLARE flag int(11);
    
    SET flag = (SELECT COUNT(*)
    		FROM bucket
    		WHERE bucket.buyer_nickname = buyer_nickname AND bucket.adding_date = adding_date AND bucket.product_id = product_id);
    
	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$
DELIMITER ;
/* --------------------------- */
delimiter $$
CREATE FUNCTION previous_amount(buyer_nickname varchar(100),adding_date date,product_id int(11)) RETURNS INT(11)
BEGIN
	DECLARE amount int(11);
    	SET amount = (SELECT bucket.amount 
	FROM bucket 
	WHERE bucket.buyer_nickname = buyer_nickname AND bucket.adding_date = adding_date AND bucket.product_id = product_id);
	
	RETURN amount;
END$$
DELIMITER ;
/* --------------------------- */
delimiter $$
CREATE FUNCTION is_there_buyer(buyer_nickname varchar(100)) RETURNS boolean
BEGIN
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
DELIMITER ;
/* --------------------------- */
delimiter $$
CREATE FUNCTION is_buyer_has_bucket(buyer_nickname varchar(100),bucket_date date) RETURNS boolean
BEGIN
	DECLARE flag int(11);
    	SET flag = (SELECT COUNT(*)
    		     	FROM bucket
			WHERE bucket.buyer_nickname = buyer_nickname AND bucket.adding_date = bucket_date);

	
	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$
DELIMITER ;
/* --------------------------- */
delimiter $$
CREATE FUNCTION totalPrice_find(buyer_nickname varchar(100),bucket_date date) RETURNS int
BEGIN
	DECLARE price int;
    	SET price = (SELECT SUM(bucket.amount*stock_product.price)
    		     FROM bucket,stock_product
		     WHERE bucket.buyer_nickname = buyer_nickname AND bucket.adding_date = bucket_date AND bucket.product_id = stock_product.product_id);
	return price;
END$$
DELIMITER ;
/* --------------------------- */
delimiter $$
CREATE FUNCTION is_there_payment(buyer_nickname_var varchar(100), r_date date) RETURNS boolean
BEGIN
	DECLARE flag int;
    SET flag = (SELECT COUNT(*)
    		FROM payment
		WHERE payment.buyer_nickname = buyer_nickname_var AND payment.payment_date = r_date);

	
	IF flag = 0 THEN
		RETURN (false);
	ELSE	
		RETURN (true);
	END IF;
END$$
DELIMITER ;


