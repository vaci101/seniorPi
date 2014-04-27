<?php
require 'loginStuff.php'
?><html>
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>The future of oven monitoring!</title>

        <link rel="stylesheet" href="//secure.newdream.net/singlepage/_assets/css/site.css" />
</head>

<body>
        <div id="page-container">
		<?php
			if ( isset( $error ) ){
				echo $error;
			}// end if
		?>
		
		<form method="post" id="sign-in-form">
			<h3>Sign In:</h3>

			<fieldset>
				<label for="sign-in-email">Email:</label><input type="email" name="email" id="sign-in-email"/><br>
				<label for="sign-in-password">Password:</label><input type="password" name="password" id="sign-in-password"/><br>
				<Input type="submit" value="Sign In"/>

				<a href="#" id="sign-up-link">Need an account?</a>
			</fieldset>
		</form>

		<form method="post" id="sign-up-form">
			<h3>Sign Up:</h3>
			<fieldset>
				<label for="sign-up-email">Email:</label><input type="email" name="email" id="sign-up-email"/><br>
				<label for="sign-up-password">Password:</label><input type="password" name="password" id="sign-up-password"/><br>
				<label for="sign-up-password_confirm">Confirm Password:</label><input type="password" name="password_confirm" id="sign-up-password_confirm"/><br>
				<label for="sign-up-device-id">Device ID issued with Pi:</label><input type="text" name="device_id" id="sign-up-device-id"/><br>
				<Input type="submit" value="Sign Up"/>
				<a href="#" id="sign-in-link">Have an account? Sign in!</a>
			</fieldset>
		</form>

        </div>
</body>
</html>
