


	CREATE TABLE IF NOT EXISTS registration(
		e_mail varchar(100),
		password varchar(100),
		primary key(e_mail) 
	);
	CREATE TABLE IF NOT EXISTS person(
		reg_e_mail varchar(100),
		nickname varchar(100),
		name varchar(50),
		lastname varchar(50),
		address varchar(255),
		phone varchar(20),
		DoB date,
		primary key(nickname),
		foreign key(reg_e_mail) references registration(e_mail) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS seller(
		nickname varchar(100),
		rate int DEFAULT 0,
		primary key(nickname),
		foreign key(nickname) references person(nickname) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS buyer(
		nickname varchar(100),
		ranking int DEFAULT 0,
		primary key(nickname),
		foreign key(nickname) references person(nickname) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS category(
		id int(11),
		parent_id int(11),
		name varchar(255),
		primary key(id)
	);
	CREATE TABLE IF NOT EXISTS product(
		id int(11) AUTO_INCREMENT,
		category_id int(11),
		brand varchar(100),
		name varchar(255),
		primary key(id),
		foreign key(category_id) references category(id) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS stock_product(
		seller_nickname varchar(100) ,
		product_id int(11) ,
		stock int(11) DEFAULT NULL,
		price float,
		primary key(seller_nickname,product_id),
		foreign key(seller_nickname) references seller(nickname) ON DELETE CASCADE ON UPDATE CASCADE ,
		foreign key(product_id) references product(id) ON DELETE CASCADE ON UPDATE CASCADE
	);	
	CREATE TABLE IF NOT EXISTS bucket(
		buyer_nickname varchar(100) ,
		adding_date date,
		product_id int(11),
		seller_nickname varchar(100),
		amount int(11),
		primary key(buyer_nickname,adding_date,product_id),
		foreign key(buyer_nickname) references buyer(nickname) ON DELETE CASCADE ON UPDATE CASCADE,
		foreign key(seller_nickname,product_id) references stock_product(seller_nickname,product_id) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS product_rate(
		rate_id int(11) AUTO_INCREMENT,
		product_id int(11),
		seller_nickname varchar(100),
		buyer_nickname varchar(100),
		rate_date date,
		comment varchar(255),
		rate int(3),
		primary key(rate_id),
		foreign key(product_id,seller_nickname) references stock_product(product_id,seller_nickname) ON DELETE CASCADE ON UPDATE CASCADE,
		foreign key(buyer_nickname) references buyer(nickname) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS payment(
		payers_name varchar(100),
		buyer_nickname varchar(100),
		payment_date date,
		payment_time TIME,
		payment_type varchar(100),
		totalPrice int,
		primary key(buyer_nickname, payment_date, payment_time),
		foreign key(buyer_nickname) references buyer(nickname) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS creditCard(
		buyer_nickname varchar(100),
		payment_date date,
		payment_time TIME,
		card_number varchar(100),
		cvc int ,
		primary key(buyer_nickname, payment_date, payment_time),
		foreign key(buyer_nickname) references payment(buyer_nickname) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS eft(
		buyer_nickname varchar(100),
		payment_date date,
		payment_time TIME,
		eft_number varchar(100),
		
		primary key(buyer_nickname, payment_date, payment_time),
		foreign key(buyer_nickname) references payment(buyer_nickname) ON DELETE CASCADE ON UPDATE CASCADE
	);
	CREATE TABLE IF NOT EXISTS paypal(
		buyer_nickname varchar(100),
		payment_date date,
		payment_time TIME,
		paypal_no varchar(100),
		
		primary key(buyer_nickname, payment_date, payment_time),
		foreign key(buyer_nickname) references payment(buyer_nickname) ON DELETE CASCADE ON UPDATE CASCADE
	);	


