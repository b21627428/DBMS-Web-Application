<?php
	if($_POST){
		if(!filter_var($_POST["e_mail"], FILTER_VALIDATE_EMAIL)){
			echo '<script>alert("Invalid email");location="guest.php?page=register"</script>'; }
		else{
		    $pass=password_hash($_POST["password"],PASSWORD_DEFAULT);
			$sql="CALL insert_person('".$_POST["e_mail"]."','".$pass."','".$_POST["nickname"]."','".$_POST["name"]."','".$_POST["surname"]."','".$_POST["address"]."','".$_POST["phone"]."','".$_POST["DoB"]."','".$_POST["type"]."')";
			$result=mysqli_query($conn,$sql) or die("Error");
			$today= new DateTime();
			$dosya = fopen("dashboard.txt","a+");
			fwrite($dosya, "".$today->format("Y-m-d H-m-s")." Registration : \n".$_POST["e_mail"]." ".$_POST["nickname"]." ".$_POST["type"]." \n");
			fclose($dosya);
			echo '<script>alert("Registration Done Successfully");location="guest.php?page=log-in"</script>';
		}
	}else{?>
		<form action="" method="post">
		<center><div style="padding-top:30px;   " >
		<table  class="regis_table" BORDER=0 >
			<tr>
				<td>&nbspE-mail</td>
				<td><input style="border-radius:6px;" type="text" name="e_mail" />&nbsp</td>
			<tr/>
			<tr>
				<td>&nbspPassword</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="password" name="password" />&nbsp</td>
			</tr>
			<tr>
				<td>&nbspNickname</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="text" name="nickname" />&nbsp</td>
			</tr>
			<tr>
				<td>&nbspName</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="text" name="name" />&nbsp</td>
			<tr/>
			<tr>
				<td>&nbspSurname</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="text" name="surname" />&nbsp</td>
			</tr>
			<tr>
				<td>&nbspAddress</td>
				<td><textarea style="margin-top:10px;border-radius:6px;width:180px;" type="text" name="address" ></textarea>&nbsp</td>
			<tr/>
			<tr>
				<td>&nbspPhone</td>
				<td ><input style="margin-top:10px;border-radius:6px;" type="text" name="phone" />&nbsp</td>
			</tr>				
			<tr>
				<td>&nbspType</td>
				<td style="margin-top:10px;" ><select style="margin-top:10px;border-radius:6px;" name="type">
				<option value="buyer">Buyer</option>
				<option value="seller">Seller</option>
				</select></td>
			</tr>
			<tr>
				<td>&nbspBirth Date&nbsp</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="date" name="DoB" />&nbsp</td>
			</tr>
			<tr>
				<td></td>
				<td><input style="margin-top:10px;" type="submit" value="Register" />&nbsp</td>
			</tr>


		</table>
		</div></center>

		</form>	
<?php		
	}


?>