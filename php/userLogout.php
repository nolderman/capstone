<?php
unset($_COOKIE['email']); //destroy the email cookie
setcookie('email', null , time()-3600, '/'); //really make sure it is gone
header('Location: http://glados/capstone');		//redirect to the loginpage.html
?>