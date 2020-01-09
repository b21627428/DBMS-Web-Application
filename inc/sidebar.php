
<div class="side-menu" >
				<ul>
					<?php
						$sql = "SELECT * FROM category";
						$result = mysqli_query($conn, $sql);

						if (mysqli_num_rows($result) > 0) {
							// output data of each row
							while($row = mysqli_fetch_assoc($result)) {
								if(isset($_SESSION["type"])){
									printf("<li><a href='%s.php?page=category&id=%s'>%s</a></li>",$_SESSION["type"],$row["id"],$row["name"]);
								}else{
									printf("<li><a href='guest.php?page=category&id=%s'>%s</a></li>",$row["id"],$row["name"]);
								}
							}
						}
					?>
				</ul>
			</div>