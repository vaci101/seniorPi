<?php
require 'password_compat/lib/password.php';
session_start();

connector();

if ( 'POST' == $_SERVER['REQUEST_METHOD'] ){
	$error = FALSE;

	//sign up
	if ( isset( $_POST['password_confirm'] ) ){
		$result = juice_sign_up( $_POST['email'], $_POST['password'], $_POST['password_confirm'], $_POST['device_id'] );
		emailAuth( $_POST['email'] );
	}
	else //sign in
	{
		$result = juice_authenticate( $_POST['email'], $_POST['password'] );
	}

	if ( basename( $_SERVER['SCRIPT_FILENAME'] ) == 'index.php' ){
		if ( $result == TRUE ){
			device_id_set( $_POST['email'] );
			user_id_set( $_POST['email'] );
			header( 'Location: welcome.php' );
			exit;
		}
	}
	else{
		//device_id_set( $_POST['email'] );
		//user_id_set( $_POST['email'] );
		//exit;
	}
}
elseif ( isset( $_GET['logout'] ) ){ //logout
	juice_logout();
	header( 'Location: index.php' );
	exit;
}


function connector(){
	global $db;

	$db = new mysqli('mysql.glauser.octopodgames.com', 'smglauser', '6hq-W37-m6B-sz9', 'glauser_octopodgames');

	if ($db->connect_errno > 0){
		die( 'Unable to connect to database [' . $db->connect_error . ']' );
	}
}

function emailAuth( $username ){
	global $db;

	$md = ( md5( $username ) );
	$message = "Please follow the link to verify your account:\n";
	$message .= "http://glauser.octopodgames.com/authCode.php/?authkey=$md";
	$subject = "Your verification code has arrived!";

	mail( $username, $subject, $message );

	$query="UPDATE users SET authkey='$md' WHERE username = '$username'";

	$stmt = $db->prepare( $query );
	$stmt->execute();

	header( 'Location: index.php' );
	exit;
}

function authQuery( $authkey ){
	global $db;

	$query = "UPDATE users SET authenticated=1 WHERE authkey = '$authkey'";

	$stmt = $db->prepare( $query );
	$stmt->execute();

	//header( 'Location: index.php' );
	//exit;
}

function device_id_set( $username ){
	global $db;

	$query = "SELECT * FROM devices where user = ?";
	$stmt = $db->prepare( $query );
	$stmt->bind_param( 's', $username );
	$stmt->execute();
	$res = $stmt->get_result();

	foreach($res as $row){
		$_SESSION['deviceid'] = $row['deviceid'];
		$_SESSION['deviceid_named'] = $row['user'];
	}
}

function user_id_set( $username ){
	global $db;

	$query = "SELECT * FROM users where username = ?";
	$stmt = $db->prepare( $query );
	$stmt->bind_param( 's', $username );
	$stmt->execute();
	$res = $stmt->get_result();

	foreach($res as $row){
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['named_user_id'] = $row['username'];
	}
}

function juice_sign_up( $username, $password, $password_confirm, $device_id ){
	global $db;

	if ( $password_confirm !== $password ){
		echo 'passwords do not match' . "<br>";
		return FALSE;
	}

	//check if user already exists
	$query = 'SELECT * FROM users where username = ?';
	$stmt = $db->prepare( $query );
	$stmt->bind_param( 's', $username );
	$stmt->execute();
	$res = $stmt->get_result();
	if ( $row = $res->fetch_assoc() ){
		echo 'duplicate account detected' . "<br>";	
		return FALSE;
	}

	$hashed = password_hash( $password, PASSWORD_DEFAULT);

	//no duplicate name
	$query = 'INSERT INTO users ( username, password ) VALUES ( ?, ? )';
	$stmt = $db->prepare( $query );
	$stmt->bind_param( 'ss',
	    $_POST['email'],
	    $hashed);
	$stmt->execute();

	//update users table with deviceid
	$query2 = 'UPDATE users SET deviceaddr = ? WHERE username = ?';
	$stmt = $db->prepare( $query2 );
	$stmt->bind_param( 'ss', $device_id, $username );
	$stmt->execute();

	//insert deviceid and username into devices
	$query3 = 'INSERT INTO devices ( deviceid, user ) VALUES ( ?, ? )';
	$stmt = $db->prepare( $query3 );
	$stmt->bind_param( 'ss', $device_id, $username );
	$stmt->execute();

	return TRUE;
}

function juice_authenticate( $username, $password ){
	global $db;

	$query = 'SELECT * FROM users WHERE username = ?';
	$stmt = $db->prepare( $query );
	$stmt->bind_param( 's', $username );
	$stmt->execute();
	$res = $stmt->get_result();

	if ( ! $row = $res->fetch_assoc() ){
		return FALSE;
	}
	else{
		if ( $row['authenticated'] == '0' ){
			return FALSE;
		}
	}

	if ( ! password_verify( $password, $row['password'] ) ){
		return FALSE;
	}

	$_SESSION['username'] = $username;	
	user_id_set( $username );
	return TRUE;
}

function juice_logout(){
	$_SESSION['user_id'] = FALSE;
	unset( $_SESSION['user_id'] );
	$_SESSION['username'] = FALSE;
	unset( $_SESSION['username'] );
	$_SESSION['deviceid'] = FALSE;
        unset( $_SESSION['deviceid'] );
	$_SESSION['device_named'] = FALSE;
        unset( $_SESSION['device_named'] );
	$_SESSION['user_id'] = FALSE;
        unset( $_SESSION['user_id'] );
	$_SESSION['named_user_id'] = FALSE;
        unset( $_SESSION['named_user_id'] );
	session_destroy();
}

function tempQuery($deviceNum){
        global $db;
        global $finaltemp;
        date_default_timezone_set( 'America/New_York' );

        $query="SELECT temp FROM temp WHERE deviceid='$deviceNum'
        ORDER BY date DESC, time DESC LIMIT 1";

        $stmt = $db->prepare( $query );
        $stmt->execute();
        $res = $stmt->get_result();

        foreach($res as $row){
                echo "Current temp reading: " . $row['temp'] . "\n";
                $finaltemp = $row['temp'];
		//$_SESSION['finaltemp'] = $row['temp'];
        }
}

function recentQuery($deviceNum){
        global $db;
        date_default_timezone_set( 'America/New_York' );

        $query="SELECT * FROM temp WHERE deviceid='$deviceNum'
        ORDER BY date DESC, time DESC LIMIT 1";

        $stmt = $db->prepare( $query );
        $stmt->execute();
        $res = $stmt->get_result();

        foreach($res as $row){
                echo $row['id'] .' | '. $row['deviceid'] .' | '. $row['temp'] .' | '. $row['time'] .' | '. $row['date'] . "\n";
                $finalTemp = $row['temp'];
		$_SESSION['finaltemp'] = $row['temp'];
        }

}

function fullQuery($deviceNum){
        global $db;
        date_default_timezone_set( 'America/New_York' );

        $query="SELECT * FROM temp WHERE deviceid='$deviceNum'
        ORDER BY date DESC, time DESC";

        $stmt = $db->prepare( $query );
        $stmt->execute();
        $res = $stmt->get_result();

        foreach($res as $row){
                echo $row['deviceid'] .' | '. $row['temp'] .' | '. $row['date'] .' | '. $row['time'] . "\n";
		//$_SESSION['templist'] .= $row['temp'];
        }
}

function deviceQuery($deviceNum){
        global $db;
        date_default_timezone_set( 'America/New_York' );

        $query="SELECT * FROM devices
        WHERE deviceid='$deviceNum'";

        $stmt = $db->prepare( $query );
        $stmt->execute();
        $res = $stmt->get_result();

        foreach($res as $row){
                echo $row['deviceid'] . ' | ' . $row['user'] . ' | ' . $row['threshold'] . "\n";
        }

}

function recentQuery1($deviceNum){
        global $db;
        date_default_timezone_set( 'America/New_York' );

        $query="SELECT * FROM temp WHERE deviceid='$deviceNum'
        ORDER BY date DESC, time DESC LIMIT 1";

        $stmt = $db->prepare( $query );
        $stmt->execute();
        $res = $stmt->get_result();

        foreach($res as $row){
                $finalTemp = $row['temp'];
                $_SESSION['finaltemp'] = $row['temp'];
        }

}

function addQuery( $deviceid, $username ){
	global $db;
	date_default_timezone_set( 'America/New_York' );

	$query="INSERT INTO devices ( deviceid, user )
		VALUES ( ?, ? )";

	$stmt = $db->prepare( $query );
	$stmt->bind_param( 'ss', $deviceid, $username );
	$stmt->execute();
}

function multiQuery( $username ){
	global $db;
	date_default_timezone_set( 'America/New_York' );

	$query="SELECT distinct D.user, D.deviceid, T.deviceid
		FROM devices D, temp T
		WHERE D.deviceid = T.deviceid AND
		D.user = '$username'";

	$stmt = $db->prepare( $query );
        $stmt->execute();
        $res = $stmt->get_result();

	foreach($res as $row){
		recentQuery( $row['deviceid'] );
	}
}

?>
