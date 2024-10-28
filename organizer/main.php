<main>
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
			$dateOfThisDay = $daysToDisplay[$i];
			print("
				<form class='day $class' method='POST' id='$dateOfThisDay'>
					<label class='day-header'>
						<span>Data:</span>
						<input type='date' name='$dateOfThisDay' readonly value='$dateOfThisDay'>
					</label>
					<div class='tasks'>
			");
			if($user != '') for( $i=0; $i<sizeof( $daysToDisplay ); $i++ ){
				
			};
			print("
						<button onclick='addTask(`$dateOfThisDay`)' type='button'>+addTask</button>
					</div>
				</form>
			");
		}
	?>
</main>