<?php
if (isset($_POST['pp-num'])){
    if( empty($_POST['pp-num'])
        || empty($_POST['pp-owner'])
    ){
        echo '<script>alert("Please Fiil in the Blanks...");location="buyer.php?page=home&button=Make Payment&type=cc"</script>';
    }else{
        $now = new DateTime();
        $now=$now->format('Y-m-d');
        $sql="CALL make_payment('".$_SESSION['nickname']."', '".$now."', 'PAYPAL' , '".$_POST['pp-owner']."', '".$_POST['pp-num']."', 1);";
        $result=mysqli_query($conn,$sql) or die("Error2");
        $dosya = fopen("dashboard.txt","a+");
		$today= new DateTime();
		fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Payment is made:  \nnickname = ".$_SESSION["nickname"]."  \n" );
		fclose($dosya);
		echo '<script>alert("Payment Made Successfully");location="buyer.php?page=home"</script>';
    }
}else{
    echo '
	<div style="width:99%;" >
	<form method="post" action="#">
    <div class="form-group">
        <input type="text" name="pp-num" style="margin-top: 10px" class="adp" placeholder="Paypal Payment Number">
        <small id="pp-info" class="form-text text-muted" style="font-weight: bold">You should send payment via Paypal , this page only for report.</small>
    </div>
    <div class="form-group">
        <input type="text" name="pp-owner" style="margin-top: 10px" class="adp" placeholder="Paypal Account Owner Name">
    </div>
    <input type="submit" name="cc-pay" class="adp" style="font-weight: bold; font-size: larger; background-color: purple; color: #fafad2" value="Make Payment">
</form>
</div>
';
}
?>

