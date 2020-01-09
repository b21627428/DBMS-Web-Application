<?php

if($_POST){
	
	if( empty($_POST['update-e_mail']) 
		|| empty($_POST['update-password'])           
		|| empty($_POST['update-name'])
		|| empty($_POST['update-Surname'])
		|| empty($_POST['update-Address'])
		|| empty($_POST['update-Phone'])
		|| empty($_POST['update-DoB']))
		{	switch($_SESSION['type']){
				case "seller" :
					echo '<script>alert("Please Fiil in the Blanks...");location="seller.php?page=profile&type=profile"</script>';
					break;
				case "buyer" :
					echo '<script>alert("Please Fiil in the Blanks...");location="buyer.php?page=profile&type=profile"</script>';
					break;
				default :
					echo '<script>alert("Error");location="log-in.php"</script>';
					break;}
					}
	else{

		
		$sql= "UPDATE person , registration SET e_mail = '".$_POST['update-e_mail']."'      ,
											   name = '".$_POST['update-name']."'          ,
											   lastname = '".$_POST['update-Surname']."'   ,
											   phone = '".$_POST['update-Phone']."'        ,
											   address = '".$_POST['update-Address']."'    ,
											   DoB = '".$_POST['update-DoB']."'
			                        
									WHERE reg_e_mail=e_mail AND nickname = '".$_SESSION['nickname']."';";
										
		$result=mysqli_query($conn,$sql) or die("Error");
		$row=mysqli_fetch_assoc($result);								
			$dosya = fopen("dashboard.txt","a+");
				$today= new DateTime();
				fwrite($dosya , " ".$today->format("Y-m-d H-i-s")." Update Profile: \nnickname = ".$_SESSION["nickname"]."\n" );
				fclose($dosya);
			switch($_SESSION['type']){
				
				case "seller" :
					echo '<script>alert("Succesfully updated");location="seller.php?page=profile&type=profile"</script>';
					break;
				case "buyer" :
					echo '<script>alert("Succesfully updated");location="buyer.php?page=profile&type=profile"</script>';
					break;
				default :
					echo '<script>alert("Error");location="log-in.php"</script>';
					break;
			}				
	}
}else{
	
	echo'<form action="" method="post">
	<h2 style="border:1px solid purple; border-radius: 10px; background-color: purple; color: #fafad2; text-align: center; padding: 5px;">-UPDATE PROFILE-</h2>
	<input type="text" name="update-e_mail" class="deneme" placeholder="New E_mail"/>
	<br/>

	<input type="password" name="update-password" class="deneme" placeholder="New Password"/>
	<br/>

	<input type="text" name="update-name" class="deneme" placeholder="New Name"/>
	<br/>

	<input type="text" name="update-Surname" class="deneme" placeholder="New Surname"/>
	<br/>

	<input type="text" name="update-Address" class="deneme" placeholder="New Address"/>
	<br/>

	<input type="text" name="update-Phone" class="deneme" placeholder="New Phone"/>
	<br/>

	<input type="date" name="update-DoB" class="deneme" placeholder="New Date"/>
	<br/>
	
	<input type="submit" style="font-weight: bold" value="Update" class="deneme"/>
	<br/>
	</form>
		
		
		
		
		
		
		
	';
		
	
	
}





?>
