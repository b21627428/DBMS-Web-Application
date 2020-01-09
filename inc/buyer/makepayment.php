   	
	<div  class="MyContent" style="margin-left:70px;" >
        <ul style="margin-top:10px;" class="nav nav-tabs">
            <li style="margin-top:10px;"><a href="buyer.php?page=makepayment&type=eft">EFT</a></li>
            <li style="margin-top:10px;"><a href="buyer.php?page=makepayment&type=cc">Credit Card</a></li>
            <li style="margin-top:10px;"><a href="buyer.php?page=makepayment&type=pp">Paypal</a></li>
            <li style="margin-top:10px;"><a href="buyer.php?page=bucket">Return Bucket</a></li>
        </ul>
    </div><div style="margin-left:70px;"> 
<?php
if(isset($_GET['type'])){
    $type=$_GET['type'];
    switch ($type){
        case 'cc':
            include("cc.php");
            break;
        case 'pp':
            include("pp.php");
            break;
        case 'eft':
        default:
        include("eft.php");
    }
}else{
	include("cc.php");
}
?>	</div>

