<?php
	$year = substr( $shownDate, 0, 4 );
	$month = substr( $shownDate, 5, 2 );
	$daysToDisplay = array();
	for( $i=0; $i < date( 'N', strtotime( "$year-$month-01" ) ) -1; $i++){
		$day = date( 'Y-m-d', strtotime( ( +$i +1 -date( 'N', strtotime( "$year-$month-01" ) ) )." day", strtotime( "$year-$month-01" ) ) );
		array_push( $daysToDisplay, $day );
	}
	for( $i = 1; $i <= cal_days_in_month( 0, $month, $year ); $i++ ){
		$I = str_pad( $i, 2, '0', STR_PAD_LEFT );
		array_push( $daysToDisplay, "$year-$month-$I" );
	}
	$lastDayOfMonth = date( 'Y-m-t', strtotime( $shownDate ) );
	for( $i=0; $i < sizeof( $daysToDisplay )%7; $i++){
		$day = date( 'Y-m-d', strtotime( ( $i +1 )." day", strtotime( $lastDayOfMonth ) ) );
		array_push( $daysToDisplay, $day );
	}
	for( $i = 0; $i < sizeof( $daysToDisplay ); $i++ ){
		$isThisDayInCurrentMonth = substr( $daysToDisplay[ $i ], 5, 2 ) == $month;
		$class = $isThisDayInCurrentMonth ? "current-month" : "different-month";
		$day = $daysToDisplay[$i];
		print("
			<div class='day $class' method='POST' id='$day'>
				<h2 class='day-header'>
					<label>
						<span>Data:</span>
						<input type='date' name='$day' readonly value='$day'>
					</label>	
				</h2>
				<div class='tasks'>
		");
		if($user != ''){
			$q = mysqli_query( $c, "SELECT `id` FROM `users` WHERE user='$user'");
			$id = mysqli_fetch_row( $q )[ 0 ];
			$q = mysqli_query( $c, "SELECT `addition_date`, `title`, `content` FROM `entries` WHERE `date`='$day' and `user_id`='$id';");
			$editSVG; $delSVG;
			if( $q->num_rows>0 ){
				$editSVG=inlineSVGFromFile( "../images/edit.svg" );
				$delSVG=inlineSVGFromFile( "../images/del.svg" );
			};
			for( $j=0; $q->num_rows>$j; $j++ ){
				$r = mysqli_fetch_row( $q );
				$addDate=$r[0];
				$title=$r[1];
				$content=$r[2];
				print(
				"<div class='task'>
					<h3>$title</h3>
					<button class='task-svg' onclick='editTask( $day )'>$editSVG</button>
					<button class='task-svg' onclick='delTask( $day )'>$delSVG</button>
					<textarea readonly>$content</textarea>
					<input type='hidden' id='addDate-$day' value='$addDate'>
				</div>
				");
			};
		};
		print("
					<button onclick='addTask(`$day`)' type='button'>+addTask</button>
				</div>
			</div>
		");
	}
?>