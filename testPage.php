<?php

require('loginStuff.php');
//require('functionFile.php');
date_default_timezone_set( 'America/New_York' );
connector();

$temp = $_POST['temp'];
$deviceAddr = $_POST['deviceaddr'];
$deviceID = $_POST['deviceid'];
$time = date('H:i:s');

$query="Insert Into temp(deviceid, deviceaddr, temp, time, date)
Values('$deviceID', '$deviceAddr', '$temp', '$time', CURDATE())";

$db->query($query);

//$query2="INSERT INTO devices(deviceaddr)
//VALUES('$device')";

//$db->query($query2);
?>
