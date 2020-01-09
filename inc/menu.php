
		
		<div class="top-nav-bar">
			<div class="searc-box">
				<h1> Sell&Buy</h1>
				<p style="max-width: min-content;">Shopping Platform</p>
                <?php
                if(isset($_SESSION['type'])) {
                    $type = $_SESSION['type'];
                }else{
                    $type = "guest";
                }?>
                <form name="search"  action="<?php echo($type.".php");?>" method="GET">
                    
					<div  class="form-check form-check-inline">
                        <input style="width:250px" type="text" class="form-control" name="productName" placeholder="Type product name or brand..." >
                    </div>
                    <div class="form-check form-check-inline">
                        <button style="background-color:purple; color:#fafad2; font-weight:bold;" type="submit" class="btn btn-primary">Search It!</button>
                    </div>
					
                </form>
			</div>
			<div class="menu-bar">
				<ul>
				<?php if(isset($_SESSION['type'])){
					$type = $_SESSION['type'];
				?>
					<li><a href="<?php echo ($type.".php?page=home"); ?>"><i class="fa fa-home"></i>Home</a></li>
					<li><a href="<?php echo ($type.".php?page=help"); ?>"><i class="fa fa-question-circle"></i>Help</a></li>						
					<li><a href="<?php echo ($type.".php?page=contact"); ?>"><i class="fa fa-envelope-square"></i>Contact</a></li>			
					<li><a href="<?php echo ($type.".php?page=about"); ?>"><i class="fa fa-info-circle"></i>About</a></li>
					
					<?php 
					if($_SESSION["nickname"] == "saidkaya1239"){ ?>
						<li><a href="<?php echo ($type.".php?page=data"); ?>"><i class="fa fa-info-circle"></i>Database</a></li>
						
					<?php }
					if($type == "buyer"){?>
						<li><a href="<?php echo ("buyer.php?page=bucket"); ?>"><i class="fa fa-shopping-basket"></i>Bucket</a></li>
					<?php }
					?>
					<li><a href="<?php echo ($type.".php?page=profile&type=profile"); ?>"><i class="fa fa-user"></i>Profile</a></li>
					<li><a href="guest.php"><i class="fa fa-th-large"></i>Exit</a></li>
				<?php }else{ ?>
					<li><a href="guest.php?page=home"><i class="fa fa-home"></i>Home</a></li>
					<li><a href="guest.php?page=help"><i class="fa fa-question-circle"></i>Help</a></li>						
					<li><a href="guest.php?page=contact"><i class="fa fa-envelope-square"></i>Contact</a></li>			
					<li><a href="guest.php?page=about"><i class="fa fa-info-circle"></i>About</a></li>
					<li><a href="guest.php?page=register">Sign Up</a></li>
					<li><a href="guest.php?page=log-in">Log In</a></li>
				<?php } ?>
				</ul>
			</div>
		</div>