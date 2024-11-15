<?php
	date_default_timezone_set( 'Europe/Warsaw' );
	$c=mysqli_connect( "localhost", "root", "", "organizer" );
	$shownDate;
	$user="";
	$message="";
	$password="";
	if( !isSet( $_COOKIE[ "shownDate" ] ) ){
		setcookie( "shownDate", date( 'Y-m-d' ), time() +7 *24 *3600 *1000 );
		$shownDate = date( 'Y-m-d' );
	}
	else $shownDate = $_COOKIE[ "shownDate" ];
	if( isSet( $_POST[ "con" ] ) ){
		if( $_POST[ "con" ] == "on" ){	
			session_start();
			if( isSet( $_POST[ "shownDate" ] ) ){
				setcookie( "shownDate", $_POST[ "shownDate" ], time() +7 *24 *3600 *1000 );
				$shownDate = $_POST[ "shownDate" ];
			}
		}
		else if( $_POST[ "con" ] == "log" ) include("body.php");
		else session_destroy();
	}
	else{	
		include("head.html");
		print("<body class='style-0'>");
		include("body.php");
		print("</body>\n</html>");
	}
	mysqli_close( $c );
	
	inlineSVGFromFile( "../images/edit.svg" );
	function inlineSVGFromFile( $svg ){
		$con=file_get_contents( $svg );
		$matches;
		preg_match( '/(<path.*?\/>)/ms', $con, $matches, PREG_UNMATCHED_AS_NULL );
		var_dump( $matches );
		print_r( $matches );
	}
?>