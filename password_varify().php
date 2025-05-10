<?php
 include 'db_connection.php';
 
 $password = 'adminpassword'; // Replace with your desired password
 $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
 echo $hashedPassword;
 


?>