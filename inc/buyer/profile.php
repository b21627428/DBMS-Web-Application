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
					<li>Name Surname : ".$row["name"]." ".$row["lastname"].'</li>
					<li>Nickname : '.$row["nickname"]." ".'</li>
					<li>Address : '.$row["address"]." ".'</li>
					<li>Phone : '.$row["phone"]." ".'</li>
					<li>Date Of Birth : '.$row["DoB"]." ".'</li>
				</ul>
			</div>
			<form action="upload.php?nickname='.$_SESSION['nickname'].'&type='.$_SESSION['type'].'" method="post" enctype="multipart/form-data">
    	<input type="file" name="fileToUpload" id="fileToUpload"><br/>
    	<input type="submit" value="Upload Image" name="submit" style="margin-top: 5px; margin-bottom: 30px;float: left">
		</form>
		</div>
		<div   style="width:65%;" class="right-side">
			<div style="width:100%" class="MyContent">
				<ul  class="nav nav-tabs">
					<li ><a href="buyer.php?page=profile&type=profile">Profile</a></li>
					<li><a href="buyer.php?page=profile&type=my-payments">My Payments</a></li>
				</ul>
			</div>	
			
			<div style="width:100%;" class="tab-content2">';
							switch($_GET["type"]){
								
								case "profile";
									include("inc/profile-update.php");
									break;
								case "my-payments";
									include("inc/buyer/payments.php");
									break;
								default:
									echo '<script>alert("Error");location="buyer.php?page=profile&type=profile"</script>';
								break;
					}
					echo'		
				</div>
		</div>
	</div>
';

?>
