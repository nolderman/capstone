<?php
require_once 'connect.php';

Search($connection);

function Search($connection){
	
	$array = array(); //array we are going to give back to the search
	$searchInput = $_GET['searchInput'];
	
	$sql = "SELECT uID,f_name, l_name, email FROM user WHERE f_name LIKE '%$searchInput%' OR l_name LIKE '%$searchInput%' OR email LIKE '%$searchInput%'";
	$result= $connection->query($sql);
	

	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		if(isset($row['f_name'])){
			if(isset($row['l_name'])){ //if the first name and last name were found put them both together in the search
				$array[] = $row['f_name']." ".$row['l_name'];
				//$array[] =  "{'f_name':'".$row['f_name']."','uID':".$row['uID']."}";
				
			}else{
				$array[] = $row['f_name']; //just put the first name in the search
			}
		}
	}
	$jsonArray = json_encode($array);
	echo $jsonArray;
	return $jsonArray;
}
?>