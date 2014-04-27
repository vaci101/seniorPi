<?php
require 'loginStuff.php';

connector();

recentQuery1( $_SESSION['deviceid'] );
header( 'Location: welcome.php' );
exit;
?>
