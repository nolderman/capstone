<?php
//Strips the input to reduce hacking 
function test_input($data) {
  $data = trim($data);  //strip unnecessary chars
  $data = stripslashes($data); //remove backslashes
  $data = htmlspecialchars($data);//convert special characters to HTML entities
  return $data;
}
?>