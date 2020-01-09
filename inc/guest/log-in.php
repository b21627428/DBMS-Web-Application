<?php
	if($_POST){
									
		if( empty($_POST['password']) || empty($_POST['nickname'])){
				echo '<script>alert("Please Enter Your Nickname and Password");location="guest.php?page=log-in"</script>'; 	
			}
		elseif( empty($_POST['nickname']) && !empty($_POST['password'])){
				echo '<script>alert("Please Enter Nickname");location="guest.php?page=log-in"</script>'; 	
			}
		elseif( empty($_POST['password']) && !empty($_POST['nickname'])){
				echo '<script>alert("Please Enter Your Password");location="guest.php?page=log-in"</script>'; 	
			}
		else{
			$dosya = fopen("dashboard.txt","a+");
			$today= new DateTime();
			$sql="SELECT password FROM person , registration WHERE reg_e_mail=e_mail AND nickname = '".$_POST['nickname']."';";
			$result=mysqli_query($conn,$sql) or die("Error");
			$row = mysqli_fetch_assoc($result);
			if(is_null($result)){
				echo '<script>alert("There is no account with given Nickname");location="guest.php?page=log-in"</script>';
			}
			elseif( !password_verify($_POST['password'],$row['password'] )){
					
					fwrite($dosya, "".$today->format("Y-m-d H-i-s")." Wrong Password Login : \n".$_POST["nickname"]." \n");
				echo '<script>alert("Wrong Password");location="guest.php?page=log-in"</script>';
				}
			else{
				session_start();
				$_SESSION['nickname'] = $_POST['nickname'];
				$sql = "SELECT * FROM buyer WHERE nickname = '".$_POST['nickname']."';";
				$result=mysqli_query($conn,$sql) or die("Error");
				$row=mysqli_fetch_assoc($result);
				if(mysqli_num_rows($result) > 0){
					$_SESSION['type'] = "buyer";
					fwrite($dosya, "".$today->format("Y-m-d H-i-s")." Successfully Login : \n".$_POST["nickname"]." buyer \n");
					echo'<script>alert("Log-in Successfully");location="buyer.php?page=home"</script>';											
				}else{
					$_SESSION['type'] = "seller";
					fwrite($dosya, "".$today->format("Y-m-d H-i-s")." Successfully Login : \n".$_POST["nickname"]." seller \n");
					echo'<script>alert("Log-in Successfully");location="seller.php?page=home"</script>';											
				}
			}
			fclose($dosya);
		}
		
	}else{?>
			<form action="" method="post">
			<center><div style="padding-top:30px;   " >
		
				<input style="margin-top:10px;border-radius:10px;" type="text" name="nickname" placeholder=" Nickname" />
				<input style="margin-top:10px;border-radius:10px;" type="password" name="password" placeholder=" Password" />
				<input style="margin-top:10px;" type="submit" name="log-in" value=" Login " />
				</div>
			</center>
			</form>
			
<?php	}




?>