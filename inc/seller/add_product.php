<?php
if($_POST){
	if( 
	empty($_POST['brand']) 
		|| empty($_POST['product_name'])           
		|| empty($_POST['category'])
		|| empty($_POST['stock'])
		|| empty($_POST['price'])
	){	
		echo '<script>alert("Please Fiil in the Blanks...");location="seller.php?page=profile&type=add-product"</script>';}
	else{
		$target_dir = "uploads2/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			$uploadOk = 0;
		}
		if ($uploadOk == 0) {
			
			echo '<script>alert("Sorry, your file was not uploaded.");location="'.$_SESSION['type'].'.php?page=profile&type=profile"</script>';
		}
		else{
			$sql="SELECT * FROM category WHERE name =  '".$_POST['category']."';";
			$result=mysqli_query($conn,$sql) or die("Error1");
			$row=mysqli_fetch_assoc($result);
			if(mysqli_num_rows($result) > 0){


				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$conn=mysqli_connect('localhost','root','','bns');
					$path="uploads2/";
					$path.=$_FILES['fileToUpload']['name'];
					$sql= "CALL update_the_stock_and_price('".$_POST['brand']."','".$_POST['product_name']."','".$_POST['category']."','".$_SESSION['nickname']."','".$_POST['stock']."','".$_POST['price']."','".$path."');";
					$result=mysqli_query($conn,$sql) or die("Error2");
					$dosya = fopen("dashboard.txt","a+");
					$today= new DateTime();
					fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Add Product to Stock: \nseller_nickname = ".$_SESSION["nickname"]." category=".$_POST["category"]." brand=".$_POST["brand"]." name=".$_POST["product_name"]." stock=".$_POST["stock"]." price=".$_POST["price"]."\n" );
					fclose($dosya);
					echo '<script>alert("Product Successfully Added/Updated");location="seller.php?page=profile&type=add-product"</script>';
				} else {
					echo '<script>alert("Sorry, there was an error uploading your file.");location="'.$_SESSION['type'].'.php?page=profile&type=profile"</script>';
				}

			
			
			
			}else{
				echo '<script>alert("Please Use Given Categories");location="seller.php?page=profile&type=add-product"</script>';
			}
			
		}		
		
	}
}else{
	
	echo'
	
	<form action="" method="post" enctype="multipart/form-data">
	<input type="text" name="brand" class="adp" placeholder="Enter product brand"/>
	<br/>

	<input type="text" name="product_name" class="adp" placeholder="Enter product name"/>
	<br/>

	<input type="text" name="category" class="adp" placeholder="Enter product category"/>
	<br/>

	<input type="text" name="stock" class="adp" placeholder="Enter product stock amount"/>
	<br/>

	<input type="text" name="price" class="adp" placeholder="Enter product price"/>
	<br/>
	
	
    <input type="file" style="margin:10px 0px 10px 0px;"name="fileToUpload" id="fileToUpload"><br/>

	
	<input type="submit" style="font-weight: bold" value="ADD/UPDATE PRODUCT" class="adp"/>
	<br/>
	
	</form>
	';
}

?>
