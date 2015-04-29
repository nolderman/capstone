<?php
require_once 'php/connect.php';
require_once 'php/sessionStatus.php';

    function GetImageExtension($imagetype)
     {
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/gif': return '.gif';
           case 'image/jpeg': return '.jpg';
           case 'image/png': return '.png';
           default: return false;
       }
     }
      


if (!empty($_FILES["uploadedimage"]["name"])) {

    $file_name=$_FILES["uploadedimage"]["name"];
    $temp_name=$_FILES["uploadedimage"]["tmp_name"];
    $imgtype=$_FILES["uploadedimage"]["type"];
    $ext= GetImageExtension($imgtype);
    $imagename=date("d-m-Y")."-".time().$ext;
    $target_path = "uploads/profile_images/".$imagename;

    $uID = $_SESSION['uID'];
     

	if(move_uploaded_file($temp_name, $target_path)) {

	 //    $query_upload="INSERT into 'images_tbl' ('images_path','submission_date') VALUES
		// ('".$target_path."','".date("Y-m-d")."')";
		$sql="UPDATE user SET picture='$imagename' WHERE uID=$uID";

		if ($connection->query($sql) === TRUE) {
		    echo "Record updated successfully";
		} else {
		    echo "Error updating record: " . $connection->error;
		}
	     
	}else{
	 
	   exit("Error While uploading image on the server");
	}
  
}
 header("Location: profile.php");

if (isset($_GET['editName'])) {
    editGroupName($connection, $_POST['editName']);
}

function editGroupName($connection, $newName)
{
    $gID = $_GET['gID'];
    
    $sql    = "UPDATE groups SET g_name='$newName' WHERE gID=$gID";
    $result = $connection->query($sql);
    
    header("Location: ../group.php?gID=$gID"); //go back to the group page
}
?>