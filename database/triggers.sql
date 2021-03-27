CREATE TRIGGER update_buyer_rate AFTER INSERT ON payment FOR EACH ROW
    UPDATE buyer
    SET buyer.ranking = buyer.ranking + (SELECT totalPrice / 100 FROM payment WHERE payment.buyer_nickname = NEW.buyer_nickname AND payment.payment_date = NEW.payment_date AND  payment.payment_time = NEW.payment_time)
    WHERE nickname = NEW.buyer_nickname
;	
CREATE TRIGGER update_seller_rate AFTER INSERT ON product_rate FOR EACH ROW
    UPDATE seller 
    SET seller.rate=(SELECT AVG(rate) from product_rate where NEW.seller_nickname = product_rate.seller_nickname )
    WHERE nickname = NEW.seller_nickname
;	
CREATE TRIGGER reduce_stock AFTER INSERT ON payment FOR EACH ROW 
    UPDATE stock_product 
    JOIN bucket ON bucket.product_id = stock_product.product_id
    SET stock_product.stock = stock_product.stock - bucket.amount
    WHERE (bucket.buyer_nickname = NEW.buyer_nickname AND bucket.adding_date = NEW.payment_date);
;
CREATE TRIGGER delete_bucket AFTER INSERT ON payment FOR EACH ROW
    DELETE FROM bucket WHERE bucket.buyer_nickname = NEW.buyer_nickname AND bucket.adding_date = NEW.payment_date
;