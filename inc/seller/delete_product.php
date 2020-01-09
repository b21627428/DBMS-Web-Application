<?php

if($_POST){
    if( empty($_POST['brand'])
        || empty($_POST['product_name'])
    ){
        echo '<script>alert("Please Fiil in the Blanks...");location="seller.php?page=profile&type=add-product"</script>';}
    else{
            $ssql="SELECT id FROM product WHERE brand = '".$_POST['brand']."' AND name= '".$_POST['product_name']."';";
            $result=mysqli_query($conn,$ssql) or die("Error2");
            if(mysqli_num_rows($result)==0){
                echo '<script>alert("Couldn\'t find and product with taken parameters");location="seller.php?page=profile&type=delete_product"</script>';
            }else{
                $sql= "DELETE FROM stock_product WHERE seller_nickname = '".$_SESSION['nickname']."' AND product_id IN(SELECT id FROM product WHERE brand = '".$_POST['brand']."' AND name= '".$_POST['product_name']."');";
                $result=mysqli_query($conn,$sql) or die("Error2");
				$dosya = fopen("dashboard.txt","a+");
				$today= new DateTime();
				fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Delete Product from Stock:\nseller_nickname = \n".$_SESSION["nickname"]." brand=".$_POST["brand"]." name=".$_POST["product_name"]."\n" );
				fclose($dosya);				
                echo '<script>alert("Product Successfully DELETED");location="seller.php?page=profile&type=add-product"</script>';
            }
    }
}else{

    echo'<form action="" method="post">
	<input type="text" name="brand" class="adp" placeholder="Enter product brand"/>
	<br/>

	<input type="text" name="product_name" class="adp" placeholder="Enter product name"/>
	<br/>


	<input type="submit" style="font-weight: bold" value="DELETE PRODUCT" class="adp"/>
	<br/>
	</form>';
}

?>
