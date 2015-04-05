<?php
require_once 'connect.php';

Search($connection);

function Search($connection){
	
	$array = array(); //array we are going to give back to the search
	$searchInput = $_GET['searchInput'];
	
	$sql = "SELECT f_name, l_name, email FROM user WHERE f_name LIKE '%{$searchInput}%' OR l_name LIKE '%{$searchInput}%' OR email LIKE '%{$searchInput}%'";
	$result= $connection->query($sql);
	

	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		if(isset($row['f_name'])){
			$array[] = $row['f_name'];
		}
		if(isset($row['l_name'])){
			$array[] = $row['l_name'];
		}
		if(isset($row['email'])){
			$array[] = $row['email'];
		}
	}
	$jsonArray = json_encode($array);
	echo $jsonArray;
	return $jsonArray;
}
?>