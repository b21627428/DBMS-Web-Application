<?php
	
	session_start();
	unset($_SESSION["nickname"]);
	unset($_SESSION["type"]);
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
				case "about";
					include("inc/about.php");
					break;
				case "contact";
					include("inc/contact.php");
					break;
				case "help";
					include("inc/help.php");
					break;
				case "register";
					include("inc/guest/register.php");
					break;
				case "log-in";
					
					include("inc/guest/log-in.php");				
					break;
                case "show";
                    include("inc/show.php");
                    break;

				case "home";
				default;
					include("inc/products.php");
					break;		

		}

	echo'</div></div>';
	include("inc/footer.php");
	echo'</body></html>';
	
?>