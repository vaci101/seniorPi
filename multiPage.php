<?php
require 'loginStuff.php';

connector();

	$addDevice = $_POST['newID'];
	$username = $_SESSION['named_user_id'];
	

	multiQuery( $_SESSION['named_user_id'] );

?>
~        
