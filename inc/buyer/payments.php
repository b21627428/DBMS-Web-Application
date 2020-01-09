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
            width: 100%;
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


<div class="row">
    <div class="column left" >
        <?php
        function create(){
            $conn=mysqli_connect('localhost','root','','bns');
            $x = @$_GET["id"];
            $output='';
            $i=0;
            $sql = "SELECT * FROM bucket,payment WHERE bucket_id=id AND buyer_nickname = '".$_SESSION['nickname']."';";
            $result = mysqli_query($conn,$sql);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
                    $output.= " <form action='buyer.php?page=comment&id=".$row['bucket_id']."' method='post'><tr>
                                <th scope=\"row\"><input type='submit' value='".(++$i)."'></th>
                                <td>".$row["payers_name"]."</td>
                                <td>".$row["payment_date"]."</td>
                                <td>".$row["payment_time"]."</td>
                                <td>".$row["totalPrice"]."₺</td>
                                </tr></form>";
                }
            }
            return $output;
        }
        ?>
        <table class="table">
            <thead>
            <tr >
                <th scope="col" style="background-color: purple; color: #fafad2;">#</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Payer Name</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Payment Date</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Payment time</th>
                <th scope="col" style="background-color: purple; color: #fafad2;">Total Price</th>
            </tr>
            </thead>
            <tbody>
            <?php echo create();?>
            </tbody>
        </table>
    </div>
</body>
</html>
