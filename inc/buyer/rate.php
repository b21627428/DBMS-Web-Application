<?php
	if(isset($_GET["seller_nickname"]) && isset($_GET["product_id"]) && isset($_GET["id"]) ){
			$conn=mysqli_connect('localhost','root','','bns');
			$sql='';
			switch ($_POST['rate']){
                case 'Well':
                    $sql = "CALL add_rate_to_product('".$_SESSION["nickname"]."','".$_GET["seller_nickname"]."','".$_GET["product_id"]."','".$_POST['rate']."',5)";
                    break;
                case 'Good':
                    $sql = "CALL add_rate_to_product('".$_SESSION["nickname"]."','".$_GET["seller_nickname"]."','".$_GET["product_id"]."','".$_POST['rate']."',4)";
                    break;
                case 'Regular' :
                    $sql = "CALL add_rate_to_product('".$_SESSION["nickname"]."','".$_GET["seller_nickname"]."','".$_GET["product_id"]."','".$_POST['rate']."',3)";
                    break;
                case 'Not-Bad':
                    $sql = "CALL add_rate_to_product('".$_SESSION["nickname"]."','".$_GET["seller_nickname"]."','".$_GET["product_id"]."','".$_POST['rate']."',2)";
                    break;
                case 'Bad' :
                    $sql = "CALL add_rate_to_product('".$_SESSION["nickname"]."','".$_GET["seller_nickname"]."','".$_GET["product_id"]."','".$_POST['rate']."',1)";
                    break;
                default :
                    break;
            }

			$result = mysqli_query($conn,$sql) or die("Error1");
			$sql="UPDATE product_in_bucket
			      SET is_rated=1
			      WHERE bucket_id='".$_GET['id']."' AND seller_nickname='".$_GET['seller_nickname']."' AND product_id='".$_GET['product_id']."';";
            $result = mysqli_query($conn,$sql) or die("Error2");
            $dosya = fopen("dashboard.txt","a+");
			$today= new DateTime();
			fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Product is rated:  \nbuyer_nickname = ".$_SESSION["nickname"]." seller_nickname=".$_GET["seller_nickname"]." product_id=".$_GET["product_id"]." rate=".$_POST['rate']." \n" );
			fclose($dosya);
			echo '<script>alert("Successfully rated.");location="buyer.php?page=comment&id='.$_GET["id"].'"</script>';
	}else{
        echo '<script>alert("ERROR");location="buyer.php?page=comment&id='.$_GET["id"].'"</script>';
	}
    
?>