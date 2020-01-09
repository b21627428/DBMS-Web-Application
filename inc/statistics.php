<?php
    function get_highest_seller(){ // top rated seller
        $conn=mysqli_connect('localhost','root','','bns');
        $sql="
    SELECT seller_nickname AS SONUC
    FROM product_rate
    WHERE 31>DATEDIFF(CURDATE(),rate_date)
    GROUP BY seller_nickname
    ORDER BY SUM(rate)/COUNT(*) DESC LIMIT 1
    ";
        $result=mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['SONUC'];
        }
    }



    function top_seller(){ // top seller
        $conn=mysqli_connect('localhost','root','','bns');
        $sql="
				SELECT seller_nickname AS SONUC
                FROM payment as p , product_in_bucket as pib
                WHERE pib.bucket_id = p.bucket_id AND  31>DATEDIFF(CURDATE(),payment_date)
                GROUP BY seller_nickname
                ORDER BY COUNT(*) DESC LIMIT 1
                ";
        $result=mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['SONUC'];
        }
    }



    function top_buyer(){ // top buyer
        $conn=mysqli_connect('localhost','root','','bns');
        $sql="
                    SELECT buyer_nickname AS SONUC
                    FROM bucket as b ,payment as p , product_in_bucket as pib
                    WHERE  b.id=p.bucket_id AND  b.id=pib.bucket_id AND pib.bucket_id = p.bucket_id AND  31>DATEDIFF(CURDATE(),payment_date)
                    GROUP BY buyer_nickname
                    ORDER BY COUNT(*) DESC LIMIT 1
                    ";
        $result=mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['SONUC'];
        }
    }



    function top_selled_product(){
        $conn=mysqli_connect('localhost','root','','bns');
        $sql="
					SELECT CONCAT(CONCAT(pr.brand,' '),pr.name) AS SONUC
                    FROM product as pr ,bucket as b ,payment as p , product_in_bucket as pib
                    WHERE  pr.id=pib.product_id AND b.id=p.bucket_id AND  b.id=pib.bucket_id AND pib.bucket_id = p.bucket_id AND  									31>DATEDIFF(CURDATE(),payment_date)
                    GROUP BY product_id
                    ORDER BY COUNT(*) DESC LIMIT 1
                    ";
        $result=mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['SONUC'];
        }
    }



    function top_rated_product(){
        $conn=mysqli_connect('localhost','root','','bns');
        $sql="
				SELECT name AS SONUC
                FROM product_rate , product
                WHERE product_rate.product_id=product.id
                GROUP BY product_rate.product_id
                ORDER BY SUM(rate)/COUNT(*) DESC LIMIT 1
                ";
        $result=mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['SONUC'];
        }
    }



    function list_of_category_based_top_sales(){
        $conn=mysqli_connect('localhost','root','','bns');
        $sql="
            SELECT c.id AS CATEGORY_NAME , COUNT(*) AS SCORE
            FROM category as c , product as pr , payment as p , product_in_bucket as pib 
            WHERE pr.category_id = c.id AND p.id = pib.product_id AND pib.bucket_id=p.bucket_id
            GROUP BY c.id
            ORDER BY COUNT(*) DESC
            ";
        $result=mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['CATEGORY_NAME'];
        }
    }

?>
