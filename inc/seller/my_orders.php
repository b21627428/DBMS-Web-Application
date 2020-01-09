<?php
function create_table(){
    $conn=mysqli_connect('localhost','root','','bns');
    $sql="SELECT m.payment_date,
       m.payment_time,
       pe.name AS 'CustomerName', 
       pe.lastname ,
       pe.address ,
       pe.phone ,
       pr.brand ,
       pr.name ,
       m.amount
  
FROM my_orders as m, person as pe, product as pr 
WHERE m.product_id = pr.id AND m.buyer_nickname = pe.nickname  AND seller_nickname =  '".$_SESSION['nickname']."' ORDER BY payment_date,payment_time;";
    $result=mysqli_query($conn,$sql) or die("Error1");
    $output="";
    if (mysqli_num_rows($result) > 0) {
        $i=0;
        while($row = mysqli_fetch_assoc($result)) {
			$output.= "<tr>
			
				<td>".$row["payment_date"]."</td>
				<td>".$row["payment_time"]."</td>				
				<td>".$row["CustomerName"]."</td>
				<td>".$row["lastname"]."</td>
				<td>".$row["address"]."</td>
				<td>".$row["phone"]."</td>
				<td>".$row["brand"]."</td>
				<td>".$row["name"]."</td>
				<td>".$row["amount"]."</td>
				
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
      <th scope="col" style="background-color: purple; color: #fafad2;">Date</th>
      <th scope="col" style="background-color: purple; color: #fafad2;">Time</th>
      <th scope="col" style="background-color: purple; color: #fafad2;">Name</th>
      <th scope="col" style="background-color: purple; color: #fafad2;">Lastname</th>
        <th scope="col" style="background-color: purple; color: #fafad2;">Address</th>
        <th scope="col" style="background-color: purple; color: #fafad2;">Phone</th>
        <th scope="col" style="background-color: purple; color: #fafad2;">Brand</th>
        <th scope="col" style="background-color: purple; color: #fafad2;">Name</th>
        <th scope="col" style="background-color: purple; color: #fafad2;">Amount</th>


    </tr>
  </thead>
  <tbody>
    <?php echo create_table();?>
  </tbody>
</table>
</html>