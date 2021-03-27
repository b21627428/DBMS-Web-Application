


CALL insert_person('ankaralı@hotmail.com','123','angara','Furkan','Kaya','Yenimahalle/Ankara','03559768689','1986-07-28','buyer');
CALL insert_person('alikaya@hotmail.com','134679','akaya','Ali','Kaya','Etimesgut/Ankara','03559768689','1996-01-28','buyer');
CALL insert_person('spaceboy@outlook.com','145872ad','universe','Ozgür','Yılmaz','Etiler/Istanbul','03559761389','1999-07-28','buyer');
CALL insert_person('josephstali@gmail.com','b1p2b3p4','jozef','Joseph','Stali','Cayyolu/Ankara','03145768689','1976-06-18','buyer');
CALL insert_person('overlord@gmail.com','aqx156s12','ainssama','Ains Oal','Gown','Merkez/Ankara','05235768689','2000-07-28','buyer');
CALL insert_person('mirainikki@gmail.com','12jj3a2d','mirainikki','Gasai','Yuno','Sincan/Ankara','01456328081','1998-01-30','seller');
CALL insert_person('tempest@gmail.com','12asc3gf','rimuru','Rimuru','Tempest','Merkez/Ankara','05459321321','1998-04-20','seller');
CALL insert_person('vinland@gmail.com','235g346h','thorff','Thorffin','Olaf','Eryaman/Ankara','05448628081','1997-01-30','seller');
CALL insert_person('emrekoyun@gmail.com','123fcas54','emre','Emre','Koyun','Yenimahalle/Ankara','05459312341','1995-01-30','seller');
CALL insert_person('yukki@gmail.com','1468fsd64','darling02','Yukki','Kirito','Merkez/Ankara','05454228081','1999-06-11','seller');
CALL insert_person('tanya@gmail.com','12jd3ck45','elacqua','Tanya Von','Degurechaff','Merkez/Ankara','05354235081','1998-09-01','seller');
CALL insert_person('gymaster@hacettepe.edu.com','S1v4sl1m','KebapBorn','Ali','Kayadibi','Mamak/Ankara','05468631174','1998-01-31','buyer');
CALL insert_person('ehancı@hacettepe.edu.com','j3ks2jcm3k','grandblue','Emre','Hancı','Cayyolu/Ankara','05367314368','1995-04-11','buyer');
CALL insert_person('imthemachine@hacettepe.edu.com','dj2ıj3f','GameMaster','Harun Alperen','Oktaş','Beytepe/Ankara','05712571435','1999-05-24','buyer');
CALL insert_person('ismail012@hacettepe.edu.com','dklsk345ckd','isot','Ismail','Ates','Beytepe/Ankara','05473361235','1997-12-28','buyer');
CALL insert_person('mamakmert@hacettepe.edu.com','k3jdkc32','motherrussia','Mert','Çökelek','Mamak/Ankara','05367144356','1999-08-17','buyer');
CALL insert_person('japone@hacettepe.edu.com','ac3fs2a','Nippon','Eren','Tasdemir','Etimesgut/Ankara','05467138897','1998-09-22','seller');
CALL insert_person('pr0h3ck1r@hacettepe.edu.com','L1nuxC4nd1r','Extreme67','Sencer','Sakallı','Kızılay/Ankara','05673403012','1998-02-28','seller');
CALL insert_person('1m41@hacettepe.edu.com','0x3B56F78A','Can1s1','Sercan','Amac','Kızılay/Ankara','05556661122','1998-03-15','seller');
CALL insert_person('oguzhan.eroglu@hacettepe.edu.com','oguz','KillaOguzhan','Oguzhan','Eroglu','Beytepe/Ankara','05671201011','1998-01-30','seller');


/*  --------------------------------------  */ 

CALL create_categories(1,NULL,'ELEKTRONİK');
CALL create_categories(2,1,'BILGISAYAR/TABLET');
CALL create_categories(3,1,'TELEFON/AKSESUAR');
CALL create_categories(4,1,'ANIME');
CALL create_categories(5,1,'BEYAZ ESYA');
CALL create_categories(6,NULL,'GIYIM');
CALL create_categories(7,2,'AYAKKABI/CANTA');
CALL create_categories(8,2,'KOL SAATI');
CALL create_categories(9,2,'TAKI/MUCEVHER');
CALL create_categories(10,NULL,'EV/YASAM/KIRTASIYE/OFIS');
CALL create_categories(11,3,'MOBILYA');
CALL create_categories(12,3,'EV ve MUTFAK GERECLERI');
CALL create_categories(13,NULL,'OTO/BAHCE/YAPI MARKET');
CALL create_categories(14,4,'LASTIK/JANT');
CALL create_categories(15,4,'HIRDAVAT');
CALL create_categories(16,NULL,'SPOR/OUTDOOR');
CALL create_categories(17,5,'FITNESS/KONDISYON');
CALL create_categories(18,5,'FUTBOL');
CALL create_categories(19,5,'BAVUL/VALIZ/CANTA');
CALL create_categories(20,NULL,'KOZMETIK/KISISEL BAKIM');
CALL create_categories(21,6,'PARFUM');
CALL create_categories(22,6,'MAKYAJ');
CALL create_categories(23,6,'MAMALAR');
CALL create_categories(24,NULL,'DIGER');



CALL update_the_stock_and_price('Huawei','Huawei P30','TELEFON/AKSESUAR','emre',50,1059.99);
CALL update_the_stock_and_price('Huawei','Huawei P30','TELEFON/AKSESUAR','emre',150,1159.99);
CALL update_the_stock_and_price('Xiaomi','Xiaomi Red Mi Note 7','TELEFON/AKSESUAR','Can1s1',135,1650);
CALL update_the_stock_and_price('SlimeDattaKen','Slime Pillow','ANIME','rimuru',250,75);
CALL update_the_stock_and_price('Xiaomi','Xiaomi Red Mi Note 7','TELEFON/AKSESUAR','Can1s1',135,1550);
CALL update_the_stock_and_price('Samsung','Samsung Galaxy Note 3','TELEFON/AKSESUAR','KillaOguzhan',555,650);
CALL update_the_stock_and_price('Colins','Mens Sweatshirt','GIYIM','thorff',170,50);
CALL update_the_stock_and_price('US POLO','Red Tshirt','GIYIM','mirainikki',215,70);
CALL update_the_stock_and_price('TORU','5 katlı futbol topu','FUTBOL','thorff',135,5);
CALL update_the_stock_and_price('Bebelac','Bebelac Gold 3 Devam Sutu','MAMALAR','mirainikki',500,25);
CALL update_the_stock_and_price('Chanel','Chanel No.5','PARFUM','Nippon',150,250);
CALL update_the_stock_and_price('Maybelline','Maybelline Ink Likit Mat Ruj','MAKYAJ','Nippon',150,310);
CALL update_the_stock_and_price('Nivea','Nivea For Men Sampuan','KOZMETIK/KISISEL BAKIM','elacqua',210,10);
CALL update_the_stock_and_price('Rolex','Rolex Oyster Perpetual','KOL SAATI','mirainikki',50,350);
CALL update_the_stock_and_price('DarlingInTheFranxx','DarlingInTheFranxx 02 Premium Figure','ANIME','darling02',200,90);
CALL update_the_stock_and_price('DarlingInTheFranxx','DarlingInTheFranxx 02 Hugging Pillow','ANIME','darling02',200,90);
CALL update_the_stock_and_price('Petlas','Petlas Kar Lastigi','LASTIK/JANT','KillaOguzhan',132,250);
CALL update_the_stock_and_price('Rolex','Rolex Oyster Perpetual','KOL SAATI','mirainikki',165,310);
CALL update_the_stock_and_price('Bosch','Bosch Matkap','HIRDAVAT','elacqua',55,250);



call add_to_bucket('ainssama',1,'emre',15);
call add_to_bucket('ainssama',1,'emre',25);
call add_to_bucket('akaya',2,'Can1s1',55);
call add_to_bucket('universe',4,'KillaOguzhan',65);
call add_to_bucket('ainssama',3,'rimuru',100);
call add_to_bucket('angara',7,'thorff',15);
call add_to_bucket('jozef',16,'elacqua',15);
call add_to_bucket('KebapBorn',10,'Nippon',25);
call add_to_bucket('KebapBorn',10,'Nippon',25);
call add_to_bucket('KebapBorn',10,'Nippon',25);
call add_to_bucket('KebapBorn',10,'Nippon',25);
call add_to_bucket('grandblue',15,'KillaOguzhan',4);
call add_to_bucket('GameMaster',8,'mirainikki',4);
call add_to_bucket('GameMaster',15,'KillaOguzhan',4);
call add_to_bucket('motherrussia',3,'rimuru',15);
call add_to_bucket('motherrussia',14,'darling02',20);
call add_to_bucket('motherrussia',13,'darling02',20);


call make_payment('ainssama',CURDATE(),'EFT','Eren',31273182,NULL);
call make_payment('universe',CURDATE(),'EFT','Eren',31273182,NULL);
call make_payment('akaya',CURDATE(),'CREDITCARD','Eren',31273182,123);


call add_rate_to_product(1,'emre','ainssama','Great',5);
call add_rate_to_product(1,'emre','ainssama','Not bad',3);









