<?php

	if($_POST){
		if(isset($_POST['name']) && isset($_POST['mail']) && isset($_POST['title']) && isset($_POST['message'])) {
			if(empty($_POST['name']) || empty($_POST['mail']) || empty($_POST['title']) || empty($_POST['message'])) {
				echo '<script>alert("Please Fill Name , E-mail , Title and Message");location="guest.php?page=contact"</script>'; 
				exit;
			} else {
				$name = strip_tags($_POST['name']);
				$mail = strip_tags($_POST['mail']);
				$title = strip_tags($_POST['title']);
				$message = strip_tags($_POST['message']);
				$letter = 'Name: ' . $name . '<br/> E-Mail: '. $mail." " . '<br/>' . $message;
				mail('saidkaya1239@gmail.com', $title, $letter);
				$today= new DateTime();
				$dosya = fopen("dashboard.txt","a+");
				if(isset($_SESSION["type"])){
					$type = $_SESSION["type"];
					fwrite($dosya ,"".$today->format("Y-m-d H-m-s")." Contact : \n".$_SESSION["nickname"]." Title : ".$title."  ".strip_tags($letter)." \n");
					fclose($dosya);
					echo '<script>alert("Thanks For Contact");location="$type.php?page=contact"</script>';
				}else{
					fwrite($dosya ,"".$today->format("Y-m-d H-m-s")." Contact : \nAnonymous Title : ".$title."  ".strip_tags($letter)." \n");
					fclose($dosya);
					echo '<script>alert("Thanks For Contact");location="guest.php?page=contact"</script>';	
				}
				
				 
				exit;
			}
		} else {
			echo '<script>alert("Please Use Given Blanks");location="guest.php?page=contact"</script>'; 
			exit;
		}
	}else{
?>
		<form action="" method="post">
		<center><div style="padding-top:30px;" >
		<table  class="regis_table" >
			
			<tr>
				<td>&nbspName</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="text" name="name" />&nbsp</td>
			<tr/>
			<tr>
				<td>&nbspSurname</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="text" name="surname" />&nbsp</td>
			</tr>
			<tr>
				<td>&nbspE-mail</td>
				<td><input style="margin-top:10px;border-radius:6px;" type="text" name="mail" />&nbsp</td>
			<tr/>
			<tr>
				<td>&nbspTitle</td>
				<td ><input style="margin-top:10px;border-radius:6px;" type="text" name="title" />&nbsp</td>
			</tr>				

			<tr>
				<td>&nbspMessage&nbsp</td>
				<td><textarea style="margin-top:10px;border-radius:6px;width:185px;"  name="message" ></textarea>&nbsp</td>
			</tr>
			<tr>
				<td></td>
				<td><input style="margin-top:10px;" type="submit" value="Send" />&nbsp</td>
			</tr>


		</table>
		</div></center>

		</form>	
			
	<?php
	}






?>