<header>
	<h1>Kalendarz</h1>
	<div>
		<label>
			<span>Dzisiejsza data:</span>
			<input id='currentDate' type='date' readonly>
		</label>
	</div>
	<form id="shownDateForm" method="POST">
		<label>
			<span>Data pokazywana:</span>
			<?php print( "<input id='shownDate' name='shownDate' type='date' value='$shownDate' onchange='updateDate()'>" ); ?>
		</label>
	</form>
	<?php			
		if( isSet( $_POST[ "username" ] ) && $_POST[ "username" ] != ''){
			$user=$_POST[ "username" ];
			$password=$_POST[ "password" ];
			$q = mysqli_query( $c, "SELECT `password` FROM `users` WHERE `user` = '$user'" );
			if( $q->num_rows == 0 ){
				mysqli_query( $c, "INSERT INTO `users`( `user`, `password` ) VALUES ( '$user', '$password' )" );
				$message="Utworzono nowe konto!";
			}
			else if( $_POST[ "password" ] != mysqli_fetch_row( $q )[0] ){
				$message="Nieprawidłowe hasło!";
				$user="";
			}
		}
	?>
	<div id="user-status">
		<svg id="user-svg"
			viewBox="0 5 30 25"
			preserveAspectRatio="xMidYMid"
		>
			<path
				style="fill: var(--text); stroke-width:0;"
				d="M 15,5 C 9.5,5 5,9.5 5,15 c 0,2.5 1,5 3,7 -8,2 -8,8 -8,8 h 30 c 0,0 -1,-5.5 -8,-8 2,-2 3,-4 3,-7 C 25,9.5 20.5,5 15,5 Z"
			/>
		</svg>
		<?php
			print("<input id='user' class='username' type='text' placeholder='Twoja nazwa użytkownika' value='$user' readonly/>");
		?>
		<button onclick="logIn()">Zaloguj się</button>
	</div>
</header>
<main>
<?php include( "main.php" ) ?>
</main>
<footer>Praca Kacpra Koniecznego</footer>
<dialog id="task">
	<div class='task'>
		<h3><input type="text" id="task-title" onclick="updateTask()"></h3>
		<?php
			print( "<button class='task-svg' onclick='message( `Task is already being edited` )'> ");
			print( inlineSVGFromFile( "../images/edit.svg" ) );
			print( "</button>" );
		?>
		<?php
			print( "<button class='task-svg' onclick='delTask()'> ");
			print( inlineSVGFromFile( "../images/del.svg" ) );
			print( "</button>" );
		?>
		<textarea id='task-content' onclick="updateTask()"></textarea>
		<label>
			Data wydarzenia:
			<input type="date" name="task-day" id="task-date" onclick="updateTask()">
		</label>
		<label>
			Data dodania:
			<input type="datetime-local" name="task-addTime" id="task-addTime" readonly>
		</label>
		<input type="hidden" name="type" id="type">
		<input type="hidden" name="task-id" id="task-id">
	</div>
</dialog>
<dialog id="login-popup">
	<form method="post" id="login-form">
		<fieldset>
			<legend>Zaloguj</legend>
			<label>
				<span>Twoja nazwa użytkownika:</span>
				<input type="text" name="username" id="username" placeholder="Twoja nazwa użytkownika" required>
			</label>
			<label>
				<span>Hasło:</span>
				<input type="password" name="password" id="password" placeholder="Hasło">
			</label>
			<label>
				<span>Ponownie wpisz hasło:</span>
				<input type="password" name="password_check" id="password_check" placeholder="Hasło">
			</label>
			<button type="submit" onclick="validateLogIn()">Zaloguj się</button>
			<span id="login-status"></span>
			<input type="hidden" name="con" id="login-con" value="log">
		</fieldset>
	</form>
</dialog>
<?php
	print("<dialog id='message'>$message</dialog>");
?>