const notes=[];

function logIn(){
	const modal = document.getElementById("login-form");
	modal.showModal();
}

function undo(i){
	const note = document.querySelectorAll("textarea")[i];
	note.value=notes[i] != null ? notes[i] : "";
	save(i);
}

function deleteNote(i){
	document.querySelectorAll("textarea")[i].value="";
	save(i);
}

function save(i){ submit(document.querySelectorAll(".noteForm")[i]); }

function submit(form){
	fetch(
		form.action, {
			method: "post",
			body: new FormData(form)
		}
	);
}