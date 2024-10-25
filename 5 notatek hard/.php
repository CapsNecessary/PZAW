<?php
	const numberOfNotes = 5;
	$c=mysqli_connect('localhost','root','','5_notes_easy');
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
			let f=document.querySelectorAll( '.noteForm' ).length;
			for(let i=0; i<f; i++){
				const form = document.querySelectorAll( '.noteForm' )[i];
				console.log(i, form);
				form.addEventListener( 'submit', e=>{
					e.preventDefault();
					submit(form);
				});
			}
		}
		</script>
	");
?>

<?php
	$notes = array();
	if(isSet($_POST["username"]) && $_POST["username"] != ''){
		$user=$_POST['username'];
		$q=mysqli_query($c,"SELECT `id` FROM `users` WHERE user='$user';");
		if($q->num_rows>0){
			$user_id=mysqli_fetch_row($q)[0];
			for($i=0; $i < numberOfNotes; $i++){
				$q=mysqli_query( $c, "SELECT content FROM notes AS N JOIN users AS U ON N.user_id=$user_id WHERE N.n=$i;" );
				if($q->num_rows>0) array_push( $notes, mysqli_fetch_row( $q )[0] );
				else array_push( $notes, "");
			}
		}
		else $notes = array("", "", "", "", "");
	}
	else $notes = array("", "", "", "", "");
	for ($i=0; $i < numberOfNotes; $i++){
		$note=$notes[$i];
		print(
			"<form class='noteForm' name='note-form-$i' method='post'>
				<textarea class='note' name='note-$i' id='$i' rows='10' cols='50'>$note</textarea>
				<button class='undo' onclick='undo($i)'>Wycowaj zmiany</button>
				<button class='deleteNote' onclick='deleteNote($i)'>Wyczyść</button>
				<button class='save' onclick='save($i)'>Zapisz</button>
				<input class='username' name='username' type='hidden'/>
			</form>"
		);
	}
	if(isSet($_POST["username"]) && $_POST["username"] != ''){
		$user=$_POST['username'];
		$q=mysqli_query($c,"SELECT `id` FROM `users` WHERE user='$user';");
		if($q->num_rows>0){
			$user_id=mysqli_fetch_row($q)[0];
			for($i=0; $i < numberOfNotes; $i++){
				if(isSet($_POST["note-$i"])){
					$q=mysqli_query( $c, "SELECT n FROM notes AS N JOIN users AS U ON N.user_id=$user_id WHERE N.n=$i;" );
					$text=$_POST["note-$i"];
					if($q->num_rows>0) mysqli_query( $c, "UPDATE notes SET content = '$text' WHERE n = $i AND user_id = '$user_id';");
					else mysqli_query( $c, "INSERT INTO notes( user_id, content, n ) VALUES ( '$user_id', '$text', '$i' );" );
				}
			}
		}
?>