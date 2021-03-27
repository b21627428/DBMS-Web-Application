delimiter //
CREATE PROCEDURE insert_person( 
	IN e_mail varchar(100),
    IN password varchar(100),
	IN nickname varchar(100),
    IN name varchar(50),
    IN lastname varchar(50),
    IN address varchar(255),
    IN phone varchar(20),
    IN DoB date,
	IN kind varchar(20)
) 
begin
	IF is_there_person(nickname) = false then
		IF is_there_registration(e_mail) = false then
			insert into registration values(e_mail,password);
		END IF;
		insert into person values(e_mail,nickname,name,lastname,address,phone,DoB);
		IF  kind = 'seller' THEN
			insert into seller values(nickname,0);
		ELSE
			insert into buyer values(nickname,0);
		END IF;
	END IF;
end; //

/* -------------------------------------  */
delimiter //
CREATE PROCEDURE create_categories(
	IN category_id int(11),
	IN parent_id int(11),
	IN name varchar(255)
)
begin
	IF is_there_category(category_id) = false THEN
		insert into category values(category_id,parent_id,LOWER(name));
	END IF;
end; //






 /* ------------------------------------- */
delimiter //
CREATE PROCEDURE update_the_stock_and_price(
	IN product_brand varchar(100),
	IN product_name varchar(100),
	IN category_name varchar(100),
	IN seller_nickname varchar(100),
	IN stock int(11),
	IN price float
)
begin
	DECLARE product_id int(11);
	DECLARE flag boolean;
	DECLARE category_id int(11);
	DECLARE isseller varchar(100);
	SET category_id = get_category_id(LOWER(category_name));
	SET product_id = get_product_id(LOWER(product_brand),LOWER(product_name));
	SET isseller = isseller(lower(seller_nickname));
	IF isseller = true THEN
		IF product_id is null THEN
			insert into product(category_id,brand,name) values(category_id,LOWER(product_brand),LOWER(product_name));
			SET product_id = get_product_id(LOWER(product_brand),LOWER(product_name));
			insert into stock_product values(seller_nickname,product_id,stock,price);
	
		ELSE
			SET flag = is_there_stock_product(seller_nickname,product_id);
        		UPDATE stock_product
        		SET stock_product.stock = stock , stock_product.price = price
        		WHERE stock_product.seller_nickname = seller_nickname AND stock_product.product_id = product_id;
    		END IF;
	END IF;
	
end; //

/* ------------------------------------- */
delimiter //
CREATE PROCEDURE add_to_bucket( 
   	buyer_nickname varchar(100),
	product_id int(11),
	seller_nickname varchar(100),
	amount int(11)
) 
begin
	DECLARE stock int(11);
	DECLARE previous_amount_var int(11);
	DECLARE adding_date DATE;
	DECLARE current_amount int(11);
    SET adding_date = CURDATE();
	SET stock = is_there_stock(seller_nickname,product_id);
	IF stock >= amount then
		IF is_in_bucket(buyer_nickname,adding_date,product_id) = true then 
			SET previous_amount_var = previous_amount(buyer_nickname,adding_date,product_id);
            SET current_amount = previous_amount_var + amount;
			UPDATE bucket
			SET bucket.amount = current_amount 
			WHERE bucket.buyer_nickname = buyer_nickname AND bucket.adding_date = adding_date AND bucket.product_id = product_id;
		ELSE
			IF is_there_buyer(buyer_nickname) = true then
				insert into bucket values(buyer_nickname,adding_date,product_id,seller_nickname,amount);
			END IF;
		END IF;	
	END IF;
end; //
/* ------------------------------------- */
delimiter //
CREATE PROCEDURE make_payment( 
   	buyer_nickname varchar(100),
	payment_date date,
	payment_type varchar(100),
	payers_name varchar(100),
	number int,
	cvc int
) 
begin
	DECLARE totalPrice int;
    DECLARE payment_time TIME;
	SET totalPrice = totalPrice_find(buyer_nickname, payment_date);
	SET payment_time = CURRENT_TIME;
	IF is_buyer_has_bucket(buyer_nickname, payment_date) = true then
		insert into payment values(payers_name,buyer_nickname,payment_date,payment_time,payment_type,totalPrice);
	
		IF payment_type = 'CREDITCARD' then
			insert into creditCard values(buyer_nickname,payment_date,payment_time,number,cvc);
		ELSEIF payment_type = 'PAYPAL' then
			insert into paypal values(buyer_nickname,payment_date,payment_time,number);
		ELSE
			insert into eft values(buyer_nickname,payment_date,payment_time,number);
		END IF;
		
	END IF;
end; //
/* ------------------------------------- */
delimiter //
CREATE PROCEDURE add_rate_to_product(
IN product_id int,
IN sel_Name varchar(100),
IN buy_Name varchar(100),
IN comment varchar(255),
IN rate int
)
BEGIN
	DECLARE rate_date date;
	SET rate_date = CURDATE();
	IF is_there_payment(buy_Name,rate_date) = true THEN
		INSERT INTO product_rate(product_id,seller_nickname,buyer_nickname,rate_date,comment,rate) VALUES (product_id,sel_Name,buy_Name,rate_date,comment,rate);
	END IF;
end; //
