<?php


    function bucket(){
        $conn=mysqli_connect('localhost','root','','bns');
        $fact = array();
		$output='';
		$totalPrice = 0.00;
        $i=0;
        if($_POST){
            
            switch ($_POST['buttton']){
                case 'Delete':
                        $sql="DELETE FROM product_in_bucket WHERE bucket_id='".$_GET['bucket_id']."'AND product_id='".$_GET['product_id']."' AND seller_nickname='".$_GET['seller_nickname']."';";
                        $result = @mysqli_query($conn,$sql);
								$dosya = fopen("dashboard.txt","a+");
								$today= new DateTime();
								fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Delete Product in Bucket:  \nbuyer_nickname = ".$_SESSION["nickname"]." seller_nickname=".$_GET["seller_nickname"]." product_id=".$_GET["product_id"]."  \n" );
								fclose($dosya);                        
						echo '<script>alert("Successfully Deleted");location="buyer.php?page=bucket"</script>';
                    break;
                case 'Update Amount':
                        if(isset($_POST['amount']) && is_numeric($_POST['amount'])){
                                $sql="CALL add_to_bucket('".$_SESSION['nickname']."','".$_GET['product_id']."','".$_GET['seller_nickname']."','".$_POST['amount']."');";
                                $result = @mysqli_query($conn,$sql);
								$dosya = fopen("dashboard.txt","a+");
								$today= new DateTime();
								fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Update Product in Bucket:  \nbuyer_nickname = ".$_SESSION["nickname"]." seller_nickname=".$_GET["seller_nickname"]." product_id=".$_GET["product_id"]." amount=".$_POST["amount"]." \n" );
								fclose($dosya);
                                echo '<script>alert("Successfully Updated");location="buyer.php?page=bucket"</script>';
                        }else{
                            echo '<script>alert("Invalid Amount");location="buyer.php?page=bucket"</script>';
                        }
                    break;
                default:
                    break;
            }
        }
        else{
            $sql = "SELECT * FROM my_bucket WHERE buyer_nickname='".$_SESSION['nickname']."'; ";
            $result = @mysqli_query($conn,$sql);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
					
					$totalPrice += $row["total"];
                    $output.= "
							<tr>
                                 <td><a href='buyer.php?page=show&brand=".$row['brand']."&name=".$row['name']."&seller=".$row['seller_nickname']."'><img width='120px;' height='120px' src='".$row['product_img_path']."'></a></td>
                                 <td>".$row["brand"]."</td>
                                <td>".$row["name"]."</td>
                                <td>".$row["amount"]."</td>
                                <td>".$row["price"]."₺</td>
                                <td>".$row["seller_nickname"]."</td>
                                <td>".$row["total"]."₺</td>
                                <td><form action='buyer.php?page=bucket&bucket_id=".$row['bucket_id']."&seller_nickname=".$row['seller_nickname']."&product_id=".$row['product_id']."' method='post'>
								<input type=\"text\" name=\"amount\"  style=\"width:50px;text-align:center; border-radius: 6px;\">	
								<input type=\"submit\" name='buttton' class='btn btn-primary' value='Update Amount'/></form></td>
								<td><form action='buyer.php?page=bucket&bucket_id=".$row['bucket_id']."&seller_nickname=".$row['seller_nickname']."&product_id=".$row['product_id']."' method='post' >
								<input type=\"submit\" name='buttton' class='btn btn-primary' value='Delete' /></td></form>
								</td>
                                </tr>";
                }
            }
        }
		array_push($fact , $output);
		array_push($fact , $totalPrice);
        return $fact;
    }
        

?>
<div class="row" style="margin-left: 70px;">
    <div style="width:95% ; margin: 10px 0px 0px 10px;" class="column left" >
        <table class="table">
            <thead>
            <tr style="margin-left:0px;">
                <th scope="col" style="background-color: purple; color: #fafad2;">#</th>
                <th scope="col" style="background-color: purple; color: #fafad2; ">Brand</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Name</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Amount</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Unit Price</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Seller</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Total Price</th>
                <th scope="col" style="background-color: purple; color: #fafad2;"></th>
                <th scope="col" style="background-color: purple; color: #fafad2;"></th>
            </tr>
            </thead>
            <tbody>
                <?php $k=bucket();
					echo $k[0];
					
				if(strlen($k[0]) != 0 ){ ?>			 
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><?php 
								echo $k[1]."₺"; ?>
						</td>
						<td>
							<form action="buyer.php?page=makepayment" method="post">								 
								<input style="margin-bottom:5px;float:right;" type="submit" name='buttton' class='btn btn-primary' value='Make Payment' />
							</form>
						</td>
					</tr>
				<?php } ?>
					
            </tbody>
        </table>
					
    </div>
</div>
