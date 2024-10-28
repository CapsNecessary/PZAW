<?php
	date_default_timezone_set( 'Europe/Warsaw' );
	$shownDate;
	if( !isSet( $_COOKIE[ "shownDate" ] ) ){
		setcookie( "shownDate", date( 'Y-m-d' ), time() +7 *24 *3600 *1000 );
		$shownDate = date( 'Y-m-d' );
	}
	else $shownDate = $_COOKIE[ "shownDate" ];
	if( isSet( $_POST["CON"] ) ){
		session_start();
		$c=mysqli_connect( "localhost", "root", "", "organizer" );
		if( isSet( $_POST[ "shownDate" ] ) ){
			setcookie( "shownDate", $_POST[ "shownDate" ], time() +7 *24 *3600 *1000 );
			$shownDate = $_POST[ "shownDate" ];
		}
		mysqli_close( $c );
	}
	else{	
		include("head.php");
		include("body.php");
	}
?>