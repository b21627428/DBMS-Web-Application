<?php
if (isset($_POST['cc-num'])){
    if( empty($_POST['cc-num'])
        || empty($_POST['cc-ownername'])
        || empty($_POST['cc-lasttime'])
        || empty($_POST['cc-cvc'])
        ){
        echo '<script>alert("Please Fiil in the Blanks...");location="buyer.php?page=home&button=Make Payment&type=cc"</script>';
    }else{
        $now = new DateTime();
        $now=$now->format('Y-m-d');
        $sql="CALL make_payment('".$_SESSION['nickname']."', '".$now."', 'CREDITCARD' , '".$_POST['cc-ownername']."', '".$_POST['cc-num']."', '".$_POST['cc-cvc']."');";
        $result=mysqli_query($conn,$sql) or die("Error2");
        $dosya = fopen("dashboard.txt","a+");
		$today= new DateTime();
		fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Payment is made:  \nnickname = ".$_SESSION["nickname"]."  \n" );
		fclose($dosya);
		echo '<script>alert("Payment Made Successfully");location="buyer.php?page=home"</script>';
    }
}else{
    echo '
	<div style="width:99%">
	<form method="post" action="#">
    <div class="form-group">
        <input type="text" name="cc-num" style="margin-top: 10px" class="adp" placeholder="Credit Card Number">
    </div>
    <div class="form-group">
        <input type="text" name="cc-ownername" class="adp" placeholder="Credit Card Owner Name">
    </div>
    <div class="form-group">
        <input type="month" class="adp" name="cc-lasttime">
    </div>
    <div class="form-group">
        <input type="text" class="adp" name="cc-cvc" placeholder="Card Validation Code (CVC)">
    </div>
    <input type="submit" name="cc-pay" class="adp" style="font-weight: bold; font-size: larger; background-color: purple; color: #fafad2" value="Make Payment">
    </form>
	</div>
	';
}
?>










