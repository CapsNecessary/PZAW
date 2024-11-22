<?php
	date_default_timezone_set( 'Europe/Warsaw' );
	$addDatetime=date( 'Y-m-d H:i:s' );
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
	
	function inlineSVGFromFile( $svg ){
		$con = file_get_contents( $svg );
		$matches;
		preg_match( '/(<path.*?\/>)/ms', $con, $matches );
		$path = preg_replace( '/ id.*?".*?"/ms', "", $matches[0] );
		$path = preg_replace( '/sodipodi.*\/>/ms', "/>", $path );
		$path = preg_replace( '/\#000000/ms', "var(--text)", $path );
		preg_match( '/(?:viewBox=")(.*?)(?:")/ms', $con, $matches );
		$viewbox = $matches[1];
		preg_match( '/(.*)\/(.*?)\.svg/ms', $svg, $matches );
		$class = $matches[2];
		return "<svg class='$class'
			viewBox='$viewbox'
			preserveAspectRatio='xMidYMid'>$path</svg>";
	}
?>