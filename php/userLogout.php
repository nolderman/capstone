<?php
unset($_COOKIE['email']);
setcookie('email', null , time()-3600, '/');
header('Location: http://glados/capstone');		//redirect to the loginpage.html
?>