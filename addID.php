<?php
require 'loginStuff.php';

connector();

        $addDevice = $_POST['newID'];
        $username = $_SESSION['named_user_id'];

	addQuery( $addDevice, $username );

	header( 'Location: welcome.php' );
	exit;
?>

