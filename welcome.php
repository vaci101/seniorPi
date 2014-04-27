<?php
require 'loginStuff.php';
?>
<html>
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<script src="javaStuff.js"></script>
	<script src="jquery.js"></script>
	
        <title>The future of oven monitoring!</title>

        <link rel="stylesheet" href="//secure.newdream.net/singlepage/_assets/css/site.css" />
</head>

<body>
        <div id="page-container">

		<h1> Raspberry Pi driven oven monitoring</h1>
		<!-- display section -->
                <header>
			<h2>Your user ID = <?php
				echo $_SESSION['user_id'] . " (User: " . $_SESSION['named_user_id'] . ")" . "<br>";
			?>Your device ID = <?php
				echo $_SESSION['deviceid'] . " (Owner: " . $_SESSION['deviceid_named'] . ")" .  "<br>";
			?></h2>
			<textarea rows="6" cols="50" id="printArea">
			</textarea>
			<h2>Click the button below to view the most recent reading ABOVE 20 deg. F!</h2>
                </header>

		<!-- interface section -->
                <div id="main">

			<button id="test" onclick="testFunction( <?php echo $_SESSION['finaltemp']; ?>);">Print most recent temp</button>

			<form method="post" action="testing.php">
				<Input Type="submit" name="testing" value="Get most recent temp">
			</form><br>
			
			<!-- threshold interface -->
			<form method="post" action="thresholdPage.php">
				Threshold Temp (F): <input type="text" size="5" id="threshtemp" name="threshold"><br>
				Device to interface with: <input type="text" size="5" id="interfaceID" name="interfaceID"><br>
				<Input Type="submit" Name="thresholdSubmit" id="threshsend" Value="Set Threshold Temp">

			<script>
		                $( document ).ready(function() {
                		        $( "#threshsend" ).click(function( e ){
						e.preventDefault();
                                		var send = $("#threshtemp").val();
						var send2 = $("#interfaceID").val();
		                                $.post( "thresholdPage.php", { threshold: send, interfaceID: send2 } )
                		                        .done(function( data ){
                                	                alert( data );
                                        	});
                        		});
                		}); 
		        </script>
			</form><br>


			<!-- Add new deviceID to account -->
			<form method="post" action="addID.php">
				New deviceID: <input type="text" size="5" name="newID"><br>
				<input type="submit" name="addid" value="Add the ID to your account">
			</form><br>

			<!-- multiple device check interface -->
			<form method="post" action="multiPage.php">
				<Input Type="submit" id="multisubmit" value="Check multiple devices"><br>
			
				<script>
					$( document ).ready(function(){
						$( "#multisubmit" ).click(function( e ){
							e.preventDefault();
							$.get( "multiPage.php", function( data ){
								alert( data );
							});
						});
					});
				</script>
			</form>



			<!-- query interface -->
                        <form method="get" action="queryPage.php">
                                deviceID to query: <input type="text" size="5" id="IDquery"><br>
				<Input Type="submit" Name="querySubmit" id="fullquerySubmit" Value="View table contents">

				<script>
                                        $( document ).ready(function(){
                                                $( "#fullquerySubmit" ).click(function( e ){
                                                        e.preventDefault();
                                                	var send1 = $("#IDquery").val();
	                                                $.post( "queryPage.php", { deviceID: send1 })
        	                                                .done(function( data ){
                	                                        alert( data );
                                                        });
                                                });
                                        });
                                </script>
                        </form>

			<form method="get" action="logoutPage.php">
				<Input Type="submit" Name="logoutSubmit" Value="Logout">
			</form>

                </div>
        </div>
</body>
</html>

