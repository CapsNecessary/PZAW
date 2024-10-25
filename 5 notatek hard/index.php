<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>5 Notatek - Hard</title>
	<!-- JQuery-ui -->
	<script src="../libraries/jquery-ui-1.14.0/external/jquery/jquery.js" ></script>
	<link rel="stylesheet" href="../libraries/jquery-ui-1.14.0/jquery-ui.min.css" />
	<script src="../libraries/jquery-ui-1.14.0/jquery-ui.min.js" ></script>
	<!-- My Styles -->
	<link rel="stylesheet" href="../css/5-notes-hard.css"/>
	<script src="../js/5-notes-hard.js"></script>
	<!-- PHP -->
	<?php
		const numberOfNotes=5;
		
		$c=mysqli_connect('localhost','root','','5_notes_hard');
		print("<script>
			addEventListener('DOMContentLoaded', _init);

			function _init(){
		");
		if(isSet($_POST["username"]) && $_POST["username"] != ''){
			$user=$_POST['username'];
			$q=mysqli_query($c,"SELECT `id` FROM `users` WHERE user='$user';");
			printf("
				const user='$user';
			
				let l=document.querySelectorAll('.username').length;
				for(let i=0; i<l; i++) document.querySelectorAll('.username')[i].value=user;
				for(let i=0; i<%s; i++) if(typeof notes[i] == 'string') document.querySelectorAll('.note')[i].value=notes[i];
			", numberOfNotes);
			if($q->num_rows==1){
				$user_id=mysqli_fetch_row($q)[0];
				$q=mysqli_query($c,"SELECT `content`, `n` FROM `notes` WHERE user_id='$user_id';");
				while($r=mysqli_fetch_row($q)){
					$content=$r[0];
					$n=$r[1];
					print("notes[$n] = '$content';");
				}
					
			}
			else mysqli_query($c,"INSERT INTO `users`(`user`) VALUES ('$user');");
		}
		print("
				$('.accordion').accordion({
					activate: function(_e,u){ submit(u.oldPanel[0]); }
				});

				let f=document.querySelectorAll( '.noteForm' ).length;
				for(let i=0; i<f; i++){
					const form = document.querySelectorAll( '.noteForm' )[i];

					form.addEventListener( 'submit', e=>{
						e.preventDefault();
						submit(form);
					});
				}
			}
			</script>
		");
	?>
</head>
<body>
	<header>
		<div class="left-header"><h1>5 Notatek - Hard</h1></div>
		<div class="right-header">
			<svg id="user-svg">
				<path
					id="path160"
					style="fill: var(--accent); stroke-width:0;"
					d="M 15,5 C 9.5,5 5,9.5 5,15 c 0,2.5 1,5 3,7 -8,2 -8,8 -8,8 h 30 c 0,0 -1,-5.5 -8,-8 2,-2 3,-4 3,-7 C 25,9.5 20.5,5 15,5 Z"
				/>
			</svg>
			<input id="user" class="username" type="text" placeholder="Twoja nazwa użytkownika" disabled/>
			<button onclick="logIn()">Zaloguj się</button>
		</div>
	</header>
	<main>
		<div class="accordion">
			<!--
			<h2>Notatka $i</h2>
			<form class="noteForm" name='note-form-$i' method='post'>
				<textarea class='note' name="note-i" id="i" rows="10" cols="50"></textarea>
				<button class="undo" onclick="undo(i)">Wycowaj zmiany</button>
				<button class="deleteNote" onclick="deleteNote(i)">Wyczyść</button>
				<button class="save" onclick="save(i)">Zapisz</button>
				<input class="username" name="username" type="hidden"/>
			</form>
			-->
			<?php
				$notes=array("","","","","");
				
				if(isSet($_POST["username"]) && $_POST["username"] != ''){
					$user=$_POST['username'];
					$q=mysqli_query($c,"SELECT `id` FROM `users` WHERE user='$user';");
					if($q->num_rows>0){
						$user_id=mysqli_fetch_row($q)[0];
						for($i=0; $i < numberOfNotes; $i++){
							if(isSet($_POST["note-$i"])){
								//#region Saving
								$q=mysqli_query( $c, "SELECT n FROM notes AS N JOIN users AS U ON N.user_id=$user_id WHERE N.n=$i;" );
								$text=$_POST["note-$i"];
								if($text != ''){
									if($q->num_rows>0) mysqli_query( $c, "UPDATE notes SET content = '$text' WHERE n = '$i' AND user_id = '$user_id';");
									else mysqli_query( $c, "INSERT INTO notes( user_id, content, n ) VALUES ( '$user_id', '$text', '$i' );" );
								}
								else mysqli_query( $c, "DELETE FROM `notes` WHERE n = '$i' AND user_id = '$user_id';" );
								//#endregion
							}
							$q=mysqli_query( $c, "SELECT content FROM notes AS N JOIN users AS U ON N.user_id=$user_id WHERE N.n=$i;" );
							if($q->num_rows>0) $notes[$i]=mysqli_fetch_row( $q )[0];
						}
					}
				}
				// generating from template
				for ($i=0; $i < numberOfNotes; $i++){
					$note=$notes[$i];
					printf(
						"<h2>Notatka %s</h2>
						<form class='noteForm' name='note-form-$i' method='post'>
							<textarea class='note' name='note-$i' id='$i' rows='10' cols='50'>$note</textarea>
							<button class='undo' onclick='undo($i)'>Wycowaj zmiany</button>
							<button class='deleteNote' onclick='deleteNote($i)'>Wyczyść</button>
							<button class='save' onclick='save($i)'>Zapisz</button>
							<input class='username' name='username' type='hidden'/>
						</form>",
						$i+1
					);
				}
				
			?>
		</div>
	</main>
	<footer>Praca Kacpra Koniecznego</footer>
	<dialog id="login-form">
		<form action="" name="login" method="post">
			<fieldset>
				<legend>Zaloguj się</legend>
				<label for="username">Twoja nazwa użytkownika:</label><br/>
				<input type="text" id="username" name="username" required>
			</fieldset>
		</form>
	</dialog>
	<dialog id="message"></dialog>
	<?php
		mysqli_close($c);
	?>
</body>
</html>