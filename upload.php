<?php

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo '<script>alert("Sorry, your file was not uploaded.");location="'.$_GET['type'].'.php?page=profile&type=profile"</script>';
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $conn=mysqli_connect('localhost','root','','bns');
            $path="uploads/";
            $path.=$_FILES['fileToUpload']['name'];
            $sql = "UPDATE person SET profile_img_path='$path' WHERE nickname = '".$_GET['nickname']."';";
            $result=mysqli_query($conn,$sql) or die("Error");
			$dosya = fopen("dashboard.txt","a+");
			$today= new DateTime();
			fwrite($dosya,"".$today->format("Y-m-d H-i-s")." Upload Profile Photo : \nnickname = ".$_GET["nickname"]." \n");
			fclose($dosya);
            echo '<script>alert("The file has been uploaded");location="'.$_GET['type'].'.php?page=profile&type=profile"</script>';
        } else {
            echo '<script>alert("Sorry, there was an error uploading your file.");location="'.$_GET['type'].'.php?page=profile&type=profile"</script>';
        }
    }

?>