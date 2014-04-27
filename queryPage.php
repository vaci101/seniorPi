<?php
require 'loginStuff.php';

connector();

$deviceID = $_POST['deviceID'];

//fullQuery($_SESSION['deviceid']);
fullQuery($deviceID);
?>
~                              
