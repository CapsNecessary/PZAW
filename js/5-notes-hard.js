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
	const t=form.querySelectorAll("textarea")[0];
	if( t.value != '' ) notes[t.id] = t.value;
	fetch(
		form.action, {
			method: "post",
			body: new FormData(form)
		}
	);
}