<?php
if (isset($_POST["eft-num"])){
    if( empty($_POST['eft-num'])
    || empty($_POST['eft-owner'])
    ){
        echo '<script>alert("Please Fiil in the Blanks...");location="buyer.php?page=home&button=Make Payment&type=cc"</script>';
    }else{
        $now = new DateTime();
        $now=$now->format('Y-m-d');
        $sql="CALL make_payment('".$_SESSION['nickname']."', '".$now."', 'EFT' , '".$_POST['eft-owner']."', '".$_POST['eft-num']."', 1);";
        $result=mysqli_query($conn,$sql) or die("Error2");
        $dosya = fopen("dashboard.txt","a+");
		$today= new DateTime();
		fwrite($dosya , "".$today->format("Y-m-d H-i-s")." Payment is made:  \nnickname = ".$_SESSION["nickname"]."  \n" );
		fclose($dosya);
		echo '<script>alert("Payment Made Successfully");location="buyer.php?page=home"</script>';
    }
}else{
    echo '<div style="width:99%">
	<form method="post" action="#">
   <div class="form-group">
        <input type="text" name="eft-num" style="margin-top: 10px" class="adp" placeholder="EFT Number">
        <small id="eft-info" class="form-text text-muted" style="font-weight: bold">You should send payment via eft , this page only for report.</small>
    </div>
     <div class="form-group">
        <input type="text" name="eft-owner" style="margin-top: 10px" class="adp" placeholder="EFT Owner">
    </div>
    <input type="submit" name="eft-pay" class="adp" style="font-weight: bold; font-size: larger; background-color: purple; color: #fafad2" value="Make Payment">
</form>
</div>


';
}
?>