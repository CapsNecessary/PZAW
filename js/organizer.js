const authorizationForm = document.createElement("form");

addEventListener( "DOMContentLoaded", _init );

function _init(){
	// fill authorizationForm
	authorizationForm.method="POST";
	const con=document.createElement("input");
	con.name="con";
	con.id="con";
	con.type="text";
	con.value="on";
	authorizationForm.appendChild( con );
	const shownDate = document.getElementById( "shownDate" ).cloneNode( true );
	shownDate.id = "shownDateCopy";
	authorizationForm.appendChild( shownDate );
	
	const today = new Date().toJSON().slice( 0, 10 );
	const currentDate = document.getElementById("currentDate");
	currentDate.value = today;
	
	const form = document.getElementById( "shownDateForm" );
	form.addEventListener( "submit", e => e.preventDefault() );
	const logInForm = document.getElementById("login-form");
	logInForm.addEventListener( "submit", e => e.preventDefault() );
	
	updateColorScheme();
	window.matchMedia( " ( prefers-color-scheme: dark ) " ).addEventListener( "change", updateColorScheme );
	
	window.addEventListener( "beforeunload", ()=>{
		const form = authorizationForm;
		form.querySelector("#con").value="terminated";
		fetch(
			form.action, {
				method: "post",
				body: new FormData( form )
			}
		)
	} );
	
	const message = document.getElementById("message");
	if( message.innerHTML != '' ) message.showModal();
}

function updateColorScheme(){
	const fav = document.getElementById("fav");
	const colorScheme = getColorScheme();
	if(colorScheme){
		fav.href = "../images/fav/darkMode-fav.ico";
	}
	else fav.href = "../images/fav/lightMode-fav.ico";
}
function getColorScheme(){
	if(!window.matchMedia){
		console.error(`"window.matchMedia" is undefined.`);
		return 0;
	}
	else return window.matchMedia( " ( prefers-color-scheme: dark ) " ).matches;
}

function updateDate(){
	const form = authorizationForm;
	form.getElementById( "shownDateCopy" ).value = document.getElementById( "shownDate" ).value;
	fetch(
		form.action, {
			method: "post",
			body: new FormData( form )
		}
	).then(
		e=>{
			const clone = e.clone();
			if( !e.ok ) console.log( e.staus );
			e.text().then( (content)=>{ document.querySelector("body").innerHTML = content; });
			console.log( clone.text() );
		}
	);
}

function logIn(){
	const modal = document.getElementById("login-popup");
	modal.showModal();
}

function addTask( day ){
	console.log(day);
}

function validateLogIn(){
	if( document.getElementById("username").value == "" ) document.getElementById("login-status").innerHTML="Nazwa użytkownika jest pusta!";
	else if( document.getElementById("password").value != document.getElementById("password_check").value ) document.getElementById("login-status").innerHTML="Hasła się nie zgadzają!";
	else{
		const logInForm = document.getElementById("login-form");
		fetch(
			logInForm.action, {
				method: "post",
				body: new FormData( logInForm )
			}
		).then(
			function (e){
				if( !e.ok ) console.log( e.staus );
				e.text().then( (content)=>{ document.querySelector( "body" ).innerHTML = content; });
			}
		);
		const dialog = document.getElementById("login-popup");
		dialog.close();
	}
}

function editTask( edit ){
	const dialog = document.getElementById( "task" );
	const task = edit.parentNode;
	dialog.showModal();
	const day = task.parentNode.parentNode;
	const tasks=day.querySelectorAll( ".task" );
	let i=0;
	for( ; i<tasks.length; i++ ) if( tasks[i] == task ) break;
	const date = day.querySelectorAll( '.date' )[0];
	const taskAnchor = `document.querySelectorAll( '#${ date.value } task' )[${ i }]`;
	dialog.querySelectorAll( ".del" )[0] .parentNode.onclick = `delTask( ${ taskAnchor } )`;
	const title = day.querySelectorAll( '.task-title' )[0];
	const content = day.querySelectorAll( '.task-content' )[0];
	const addTime = day.querySelectorAll( '.task-addTime' )[0];
	document.getElementById( "task-title" ).value = title.value;
	document.getElementById( "task-content" ).value = content.value;
	document.getElementById( "task-id" ).value = task;
	document.getElementById( "task-date" ).value = date.value;
	document.getElementById( "task-addTime" ).value = addTime.value;
}

function updateTask(){
	
}

function delTask( del ){
	const task = del.parentNode;
	
}