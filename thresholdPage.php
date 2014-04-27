<?php

require('loginStuff.php');
connector();
date_default_timezone_set( 'America/New_York' );

	$temp = $_POST['threshold'];
	$device_id = $_POST['interfaceID'];
	//$device_id = $_SESSION['deviceid'];

	//placeholder 1 for deviceid
	$query="UPDATE devices SET threshold='$temp'
	WHERE deviceid = '$device_id'";

	$db->query($query);

	//placeholder 1 for deviceid
	tempQuery($device_id);

	echo "Current threshold set: " . $temp . "\n";

	if ( $temp <= $finaltemp ){
		echo "it's preheated";
	}	
	else{
		echo "it's NOT preheated";
	}
	//echo "\n";

	//placeholder 1 for deviceid
	//deviceQuery($device_id);


?>

