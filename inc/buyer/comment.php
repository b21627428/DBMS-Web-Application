<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        /* Create two unequal columns that floats next to each other */
        .column {
            float: left;
            padding: 10px;
        }

        .left {
            width: 99%;
        }


        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>


<div class="row" style="margin-left:70px;">
    <div class="column left" >
        <?php
        function create(){
            $conn=mysqli_connect('localhost','root','','bns');
            $x = @$_GET["id"];
			
				$today= new DateTime();
				$output='';
				$i=0;
				$sql = "SELECT * FROM my_orders WHERE bucket_id = '".$x."'";
				$result = mysqli_query($conn,$sql);
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)){
						$output.= "<form action='buyer.php?page=rate&id=".$x."&seller_nickname=".$row['seller_nickname']."&product_id=".$row['product_id']." ' method='post'><tr>
								   <th scope=\"row\">".(++$i)."</th>
									<td>".$row["brand"]."</td>
									<td>".$row["name"]."</td>
									<td>".$row["amount"]."</td>
									<td>".$row["unit_price"]."₺</td>
									<td>".$row["seller_nickname"]."</td>
									<td>".$row["totalPrice"]."₺</td>";
									?>
									<?php
									 $expireDate = new DateTime($row['payment_date']);
									 $diff = $today->diff($expireDate)->format("%a");
									 
									if($diff <= 7 && $row["is_rated"]==0) {
										$output.= "<td>
									<select name='rate'>
									<option value=\"Well\">Well</option>
									<option value=\"Good\">Good</option>
									<option value=\"Regular\">Regular</option>
									<option value=\"Not-Bad\">Not Bad</option>
									<option value=\"Bad\">Bad</option>
									</select></td>
									<td><input type='submit' value='SEND COMMENT/RATE'></td>";
									}
						$output.= "</tr></form>";
						}
				}
				
				return $output;
			
			
			
        }
        ?>
        <table class="table">
            <thead>
            <tr >
                <th scope="col" style="background-color: purple; color: #fafad2;">#</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Product Brand</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Product Name</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Taken Amount</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Unit Price</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Seller Nickname</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Total Price</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Comment/Rate</th>
                <th scope="col" style="background-color: purple; color: #fafad2;"></th>
            </tr>
            </thead>
            <tbody>
            <?php echo create();?>
            </tbody>
        </table>
    </div>
</body>
</html>

