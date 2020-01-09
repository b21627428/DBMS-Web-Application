<?php
$sql = "SELECT * FROM person WHERE nickname = '".$_SESSION['nickname']."';";
$result=mysqli_query($conn,$sql) or die("Error");
$row=mysqli_fetch_assoc($result);
echo'
	

	<div style="padding-top:10px;margin-left:75px;" class="row">
		<div class="overview" style="text-align: center" >
			
			<img style="width: 240px;height: 240px;" src="'.$row['profile_img_path'].'" class="picture">
			
			<div class="info">
				<ul>';
					echo "
					<li>Mail : ".$row["reg_e_mail"]."</li>
					<li>Name Surname : ".$row["name"]." ".$row["lastname"].'</li>
					<li>Nickname : '.$row["nickname"]." ".'</li>
					<li>Address : '.$row["address"]." ".'</li>
					<li>Phone : '.$row["phone"]." ".'</li>
					<li>Date Of Birth : '.$row["DoB"]." ".'</li>'.
					'<!DOCTYPE html>
						<html>
						<head>
						<!-- Font Awesome Icon Library -->
						<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
						<style>
						.checked {
						  color: orange;
						}
						</style>
						</head>
						<body>'.
					"<li >Star Rating</li>";
					$sql = "SELECT rate FROM seller WHERE nickname = '".$_SESSION['nickname']."';";
					$result=mysqli_query($conn,$sql) or die("Error");
					$row=mysqli_fetch_assoc($result);
					
					for($i=0 ; $i< (int)$row["rate"] ; $i++){
						echo'<span class="fa fa-star checked"></span>';
					}
					for( ; $i<5 ; $i++){
						echo'<span class="fa fa-star"></span>';
					}

						echo'
						</body>
						</html>';
					
				echo'
				</ul>
			</div>
			<form action="upload.php?nickname='.$_SESSION['nickname'].'&type='.$_SESSION['type'].'" method="post" enctype="multipart/form-data">
    	<input type="file" name="fileToUpload" id="fileToUpload"><br/>
    	<input type="submit" value="Upload Image" name="submit" style="margin-top: 5px; margin-bottom: 30px;float: left">
		</form>
		</div>
		<div  class="right-side">
			<div class="MyContent">
		
				<ul class="nav nav-tabs">
			
					<li ><a href="seller.php?page=profile&type=profile">Profile</a></li>
					<li><a href="seller.php?page=profile&type=my-products">My Products</a></li>
					<li><a href="seller.php?page=profile&type=add-product">Add/Update Product</a></li>
					<li><a href="seller.php?page=profile&type=delete_product">Delete Product</a></li>
					<li><a href="seller.php?page=profile&type=my-orders">My Orders</a></li>

				</ul>
			</div>	
			<div class="tab">
				<div class="tab-content">';
							switch($_GET["type"]){
								
								case "profile";
									include("inc/profile-update.php");
									break;
								case "my-products";
										include("inc/seller/show_my_products.php");
									break;
								case "add-product";
									include("inc/seller/add_product.php");
									break;
								case "delete_product";
									include("inc/seller/delete_product.php");
									break;
								case "my-orders";
									include("inc/seller/my_orders.php");
									break;
								default:
									echo '<script>alert("Error");location="seller.php?page=profile&type=profile"</script>';
								break;
					}
					echo'
					
				</div>
				
			</div>
		</div>
';

?>
