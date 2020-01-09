<?php
session_start();
	if(isset($_SESSION["type"]) and $_SESSION["type"] == "seller"){
		
		include("inc/database.php");
		echo'<html><head>';
		include("inc/head.php");		
		echo'</head><body>';
		include("inc/menu.php");
		echo'<div class="header">';
		include("inc/sidebar.php");
		echo'<div class="content">';
		$page = @$_GET["page"];
			Switch($page){
				case "about":
					include("inc/about.php");
					break;
				case "contact":
					include("inc/contact.php");
					break;
				case "help":
					include("inc/help.php");
					break;
				case "profile":
					include("inc/seller/profile.php");
					break;
				case "show":
					include("inc/show.php");
					break;
                case "data";
					if($_SESSION["nickname"]=="saidkaya1239"){
						include("inc/data.php");
					}else{
						include("inc/products.php");
					}
                    break;
                case "home":
				default;
					include("inc/products.php");
					break;		
			}		
		echo'</div></div>';
		include("inc/footer.php");
		echo'</body></html>';
		
	}
	else{
		echo "Seni Çakal Seni";
	}
?>