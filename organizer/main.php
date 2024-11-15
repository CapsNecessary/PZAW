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
					<h2 class='day-header'>
						<label>
							<span>Data:</span>
							<input type='date' name='$dateOfThisDay' readonly value='$dateOfThisDay'>
						</label>	
					</h2>
					<div class='tasks'>
			");
			if($user != ''){
				$q = mysqli_query( $c, "SELECT `id` FROM `users` WHERE user='$user'");
				$id = mysqli_fetch_row( $q )[ 0 ];
				for( $i=0; $i<sizeof( $daysToDisplay ); $i++ ){
					$day = $dateOfThisDay;
					$q = mysqli_query( $c, "SELECT `addition_date`, `title`, `content` FROM `entries` WHERE 'date'='$day' and 'user_id'='$id';");
					for( $j=0; $q->num_rows<$j; $j++ ){
						$r = mysqli_fetch_row( $q );
						$addDate=$r[0];
						$title=$r[0];
						$content=$r[0];
						print(
						"<div class='task'>
							<h3>$title<h3>
							
						");
					}
				};
			};
			print("
						<button onclick='addTask(`$dateOfThisDay`)' type='button'>+addTask</button>
					</div>
				</form>
			");
		}
	?>
</main>