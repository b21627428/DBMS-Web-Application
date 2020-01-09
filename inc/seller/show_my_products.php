<html>
<?php

function create_table(){
    $conn=mysqli_connect('localhost','root','','bns');
    $sql="SELECT brand , name , stock , price ,product_img_path FROM product , stock_product WHERE product_id = id AND seller_nickname =  '".$_SESSION['nickname']."';";
    $result=mysqli_query($conn,$sql) or die("Error1");
    $output="";
    if (mysqli_num_rows($result) > 0) {
        $i=0;
        while($row = mysqli_fetch_assoc($result)) {
			$output.= "<tr>
				<td><a href='seller.php?page=show&brand=".$row['brand']."&name=".$row['name']."&seller=".$_SESSION['nickname']."'><img width='120px' height='120px' src='".$row['product_img_path']."'></a></td>
				<td>".$row["brand"]."</td>
				<td>".$row["name"]."</td>
				<td>".$row["stock"]."</td>
				<td>".$row["price"]."₺</td>
				</tr>";
        }
        return $output;
    }
    else{
        echo '<script>alert("Couldn\'t find any record");location="seller.php?page=profile&type=profile"</script>';
      
    }
}
?>
<table class="table">
  <thead>
    <tr >
      <th scope="col" style="background-color: purple; color: #fafad2;">#</th>
      <th scope="col" style="background-color: purple; color: #fafad2;">Brand</th>
      <th scope="col" style="background-color: purple; color: #fafad2;">Name</th>
      <th scope="col" style="background-color: purple; color: #fafad2;">Stock</th>
        <th scope="col" style="background-color: purple; color: #fafad2;">Price</th>
    </tr>
  </thead>
  <tbody>
    <?php echo create_table();?>
  </tbody>
</table>
</html>