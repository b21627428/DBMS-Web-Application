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
    <div style="width:99%;" class="column left" >
		<table class="table">
            <thead>
            <tr style="margin-left:0px;">
                <th scope="col" style="background-color: purple; color: #fafad2;">Top Rated Seller</th>
                <th scope="col" style="background-color: purple; color: #fafad2; ">Top Seller</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Top Buyer</th>
                 <th scope="col" style="background-color: purple; color: #fafad2;">Top Selled Product</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Top Rated Product</th>
            </tr>
            </thead>
            <tbody>
            <?php 
				include("inc/statistics.php");
			?>
			<tr>
				<td><?php echo get_highest_seller(); ?></td>
				<td><?php echo top_seller(); ?></td>
				<td><?php echo top_buyer(); ?></td>
				<td><?php echo top_selled_product(); ?></td>
				<td><?php echo top_rated_product(); ?></td>
			</tr>
            </tbody>
        </table>
        <table class="table">
            <thead>
            <tr style="margin-left:0px;">
                <th scope="col" style="background-color: purple; color: #fafad2;">#</th>
                <th scope="col" style="background-color: purple; color: #fafad2; ">Brand</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Name</th>
                 <th scope="col" style="background-color: purple; color: #fafad2;">Price</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Seller</th>
				<th scope="col" style="background-color: purple; color: #fafad2;">Seller Rate</th>
            </tr>
            </thead>
            <tbody>
            <?php 
				include("inc/create.php");
				if(isset($_SESSION["type"])){
					echo create($_SESSION["type"]);
				}
				else{
					echo create("guest");
				}
			?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

