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


<div class="row" style="margin-left:70px;width:95%;">
    <div class="column left" >
        <?php
        function create(){
            $conn=mysqli_connect('localhost','root','','bns');
            $output='';
            $i=0;
            $sql = "SELECT * FROM product_rate as pr , product as p
								  WHERE pr.product_id = p.id AND
										pr.seller_nickname = '".$_GET['seller']."' AND
										p.name = '".$_GET['name']."' AND
										p.brand = '".$_GET['brand']."';";
									
									
            $result = mysqli_query($conn,$sql);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
                    $output.= " <tr>
                                <th scope=\"row\">".(++$i)."</th>
                                <td>".$row["brand"]."</td>
                                <td>".$row["name"]."</td>
                                <td>".$row["seller_nickname"]."</td>
                                <td>".$row["buyer_nickname"]."</td>
                                <td>".$row["rate_date"]."</td>
                                <td>".$row["comment"]."</td>
                                <td>".$row["rate"]."</td>
                                </tr>";
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
                <th scope="col" style="background-color: purple; color: #fafad2;">Seller Name</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Customer</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Date</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Comment</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Rate</th>
            </tr>
            </thead>
            <tbody>
            <?php echo create();?>
            </tbody>
        </table>
    </div>
</body>
</html>

