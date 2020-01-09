<?php

        function create($type){
            $conn=mysqli_connect('localhost','root','','bns');
            $output='';
            $i=0;
            $page = @$_GET["page"];
			$width=120;
			$height=120;
			
			if($_POST){
				if(isset($_POST['amount']) && is_numeric($_POST['amount'])){
                    $x = $_SESSION["nickname"];
                    $y = (int)$_GET["product_id"];
                    $z = $_GET["seller_nickname"];
                    $k = (int)$_POST["amount"];
                    $sql= "CALL add_to_bucket('".$x."','".$y."','".$z."','".$k."');";
                    $result=mysqli_query($conn,$sql) or die("Error2");
					$dosya = fopen("dashboard.txt","a+");
					$today= new DateTime();
					fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Add Product to Bucket: \nbuyer_nickname = ".$_SESSION["nickname"]." seller_nickname=".$_GET["seller_nickname"]." product_id=".$_GET["product_id"]." amount=".$_POST["amount"]." \n" );
					fclose($dosya);                    
					echo '<script>alert("Product Successfully Added/Updated");location="buyer.php?page=home"</script>';
                }else{
                    echo '<script>alert("Invalid Amount");location="buyer.php?page=home"</script>';
                }
				

			}
			else{
				switch($page){
                    case "category":
                        $x = @$_GET["id"];
                        $sql = "SELECT * FROM stock_product,product,seller WHERE nickname = seller_nickname AND product_id=id AND category_id = '".$x."' ";
                        $result = mysqli_query($conn,$sql);
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)){
                                    $x = $row["seller_nickname"];
									$y = $row["product_id"];
									$z = @$_POST["amount"];
									$output.= " 
                                 <form action='buyer.php?page=home&seller_nickname=$x&product_id=$y' method='post' >
                                <tr>
                                <td><a href='$type.php?page=show&brand=".$row['brand']."&name=".$row['name']."&seller=".$row['seller_nickname']."'><img width='".$width."px' height='".$height."px' src='".$row['product_img_path']."'></a></td>
                                <td style='width:100px;'>".$row["brand"]."</td>
                                <td>".$row["name"]."</td>
                                <td>".$row["price"]."₺</td>
                                <td>".$row["seller_nickname"]."</td>
								<td>";
                                    for($j=0 ; $j< (int)$row["rate"] ; $j++){
                                        $output.= '<span style="margin-left:5px; margin-top:5px;color:orange; " class="fa fa-star checked"  ></span>';
                                    }
                                    for( ; $j<5 ; $j++){
                                        $output.= '<span class="fa fa-star" style="margin-left:5px;margin-top:5px;color:black;" ></span>';
                                    }
                                    if(isset($_SESSION['type']) && $_SESSION['type']=="buyer"){
                                        $output.='</td>
										<td> 
										<input type="text" name="amount"   style="width:50px;height:30px;text-align:center; border-radius: 6px;">	
										<input type="submit" class="btn btn-primary" value="Add to Bucket"/> 
                                          
										</td>
										</tr>
										</form>';

                                    }else{
                                        $output.='</td></tr></form>';
                                    }



							}
						}
                        break;
                    case "home":
                    default:
                        if(isset($_GET['productName'])){
                            $x = strtolower($_GET['productName']);
                            $sql = "SELECT * FROM stock_product,product,seller WHERE nickname = seller_nickname AND product_id=id AND (name REGEXP '$x' OR brand REGEXP '$x') ";
                            $result = mysqli_query($conn,$sql);
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)){
                                    $x = $row["seller_nickname"];
									$y = $row["product_id"];
									$z = @$_POST["amount"];
									$output.= " 
                                 <form action='buyer.php?page=home&seller_nickname=$x&product_id=$y' method='post' >
                                <tr>
                                <td><a href='$type.php?page=show&brand=".$row['brand']."&name=".$row['name']."&seller=".$row['seller_nickname']."'><img width='".$width."px' height='".$height."px' src='".$row['product_img_path']."'></a></td>
                                <td style='width:100px;'>".$row["brand"]."</td>
                                <td>".$row["name"]."</td>
                                <td>".$row["price"]."₺</td>
                                <td>".$row["seller_nickname"]."</td>
								<td>";
                                    for($j=0 ; $j< (int)$row["rate"] ; $j++){
                                        $output.= '<span style="margin-left:5px; margin-top:5px;color:orange; " class="fa fa-star checked"  ></span>';
                                    }
                                    for( ; $j<5 ; $j++){
                                        $output.= '<span class="fa fa-star" style="margin-left:5px;margin-top:5px;color:black;" ></span>';
                                    }
                                    if(isset($_SESSION['type']) && $_SESSION['type']=="buyer"){
                                        $output.='</td>
										<td> 
										<input type="text" name="amount"   style="width:50px;height:30px;text-align:center; border-radius: 6px;">	
										<input type="submit" class="btn btn-primary" value="Add to Bucket"/> 
                                          
										</td>
										</tr>
										</form>';

                                    }else{
                                        $output.='</td></tr></form>';
                                    }


                                }
                            }else{
								if(isset($_SESSION["type"])){
									$type = $_SESSION["type"];
	                                echo '<script>alert("Couldn\'t found any record");location="'.$type.'.php?page=home"</script>';
								
								}else{
	                                echo '<script>alert("Couldn\'t found any record");location="guest.php?page=home"</script>';
								
								}
                            }
                        }else{
                            $sql = "SELECT * FROM stock_product,seller,product WHERE nickname = seller_nickname AND product_id=id  ";
                            $result = @mysqli_query($conn,$sql);
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)){
                                    $x = $row["seller_nickname"];
									$y = $row["product_id"];
									$z = @$_POST["amount"];
									$output.= " 
                                 <form action='buyer.php?page=home&seller_nickname=$x&product_id=$y' method='post' >
                                <tr>
                                <td><a href='$type.php?page=show&brand=".$row['brand']."&name=".$row['name']."&seller=".$row['seller_nickname']."'><img width='".$width."px' height='".$height."px' src='".$row['product_img_path']."'></a></td>
                                <td style='width:100px;'>".$row["brand"]."</td>
                                <td>".$row["name"]."</td>
                                <td>".$row["price"]."₺</td>
                                <td>".$row["seller_nickname"]."</td>
								<td>";
                                    for($j=0 ; $j< (int)$row["rate"] ; $j++){
                                        $output.= '<span style="margin-left:5px; margin-top:5px;color:orange; " class="fa fa-star checked"  ></span>';
                                    }
                                    for( ; $j<5 ; $j++){
                                        $output.= '<span class="fa fa-star" style="margin-left:5px;margin-top:5px;color:black;" ></span>';
                                    }
                                    if(isset($_SESSION['type']) && $_SESSION['type']=="buyer"){
                                        $output.='</td>
										<td> 
										<input type="text" name="amount"   style="width:50px;height:30px;text-align:center; border-radius: 6px;">	
										<input type="submit" class="btn btn-primary" value="Add to Bucket"/> 
                                          
										</td>
										</tr>
										</form>';

                                    }else{
                                        $output.='</td></tr></form>';
                                    }


                                }
                            }
                        }
                        break;
			}
                                }

            return $output;
        }
		
?>