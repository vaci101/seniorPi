<?php

require('loginStuff.php');
connector();
date_default_timezone_set( 'America/New_York' );

	global $db;

        $authkey = $_GET['authkey'];

        $query1="SELECT * FROM users WHERE authkey = '$authkey' LIMIT 1";

	$stmt = $db->prepare( $query1 );
        $stmt->execute();
        $res = $stmt->get_result();

        foreach($res as $row){
		if ( $row['authkey'] = $authkey ){
			authQuery( $authkey );
			//echo $row['authkey'];
		}
        }

?>
<html>
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <script src="javaStuff.js"></script>
        <script src="jquery.js"></script>

        <title>Authentication Success!</title>

        <link rel="stylesheet" href="//secure.newdream.net/singlepage/_assets/css/site.css" />
</head>

<body>
        <div id="page-container">
		<h2>Your account is now authenticated, please log in with the following link</h2><br>
		<a href="http://glauser.octopodgames.com/index.php"> Click to login </a>
	</div>

</body>

</html>
