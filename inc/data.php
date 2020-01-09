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
			width:20%;
        }
		
        .right {
			width:75%;
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
<?php 
function get_Table_Names(){
	
	$output = "";
	$conn = mysqli_connect("localhost","root","","bns");
	$sql = "show tables";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0){
		$type = $_SESSION["type"];
		while($row = mysqli_fetch_assoc($result)){
			if(isset($type)){
				$output.= "<p> <a href='$type.php?page=data&tableName=".$row["Tables_in_bns"]." ' > ".$row["Tables_in_bns"]."</a></p>";
				
			}else{
				$output.= "<p> <a href='guest.php?page=data&tableName=".$row["Tables_in_bns"]." ' > ".$row["Tables_in_bns"]."</a></p>";	
			}
			
			
		} 
		
	}	
	return $output;
}
function get_Table($tableName){
	$conn=mysqli_connect("localhost","root","","bns");	
	$columns= mysqli_query($conn,"SHOW COLUMNS FROM ".$tableName." ");
	$columnsArray = array();
	$output="";
	if(mysqli_num_rows($columns)>0){
		
		$output.= '
		<table class="table">
			<thead style="background-color:purple;color:#fafad2;"> 
				<tr >
					<td>Field</td>
					<td>Type</td>
					<td>Null</td>
					<td>Key</td>
					<td>Default</td>
					<td>Extra</td>
				</tr>
			</thead>
			<tbody>
		';
		
		while($row = mysqli_fetch_assoc($columns)){
			$output.= " 
						<tr>
						<td style='width:100px;'>".$row["Field"]."</td>
						<td>".$row["Type"]."</td>
						<td>".$row["Null"]."₺</td>
						<td>".$row["Key"]."</td>
						<td>".$row["Default"]."</td>
						<td>".$row["Extra"]."</td>

					";
			array_push($columnsArray,$row["Field"]);
		}
		
		$output.='
			<tbody>
		</table >
		<table class="table">
			<thead style="background-color:purple;color:#fafad2;">				<tr>';
					
				foreach($columnsArray as $column){
					$output.="<td>".$column."</td>";
				}
				
				
				
				$output.='</tr>
			</thead>
			<tbody>
		';
			$rows = mysqli_query($conn,"SELECT * FROM ".$tableName.";");
			if(mysqli_num_rows($rows)>0){
				$i = 1;
				while($row = mysqli_fetch_assoc($rows)){
				
					$output.= " 
						<tr>
						";
						foreach($columnsArray as $column){
							$output.="<td>".$row[$column]."</td>";
						}
						
						
						$output.="</tr";
					
				}
				
			}
		
		$output.="
			</tbody>
		</table>";
		
		
		
		
		
		
	}
	return $output;
}
?>

<div class="row" style="margin-left:70px;">
    <div  class="column left" >
			
             <p scope="col" style="font-size:20px;border-radius:10px; border:1px solid purple;text-align:center; background-color: purple; color: #fafad2;">Table Name</p>
            <div style="margin-left:40px;">
            <?php
				echo get_Table_Names();
			?>
			</div>
			
</div>
<?php
if(isset($_GET["tableName"])){

			echo'	<div class="column right" > 
        
             <p scope="col" style="font-size:20px;border-radius:10px; border:1px solid purple;text-align:center; background-color: purple; color: #fafad2;">Content</p>
            <div style="margin-left:40px;">';
            
				
			echo get_Table($_GET["tableName"]);
				
				
			echo'
			</div>
    
	
	</div>';
	
	
}


?>
</div>
</body>
</html>

