// DATABASE STORAGE CODE BELOW

const form = document.querySelector('form');

var quitBool = false;

form.addEventListener('submit', e => {
	e.preventDefault();
	
	var answersAdded = 0;
	
	var passage_ids = JSON.parse(localStorage.getItem("passage_ids"));

	const query_text = document.getElementById('header').innerText;
	
	const db = firebase.firestore();
	
	const userCollectionRef = db.collection('users');
	
	firebase.auth().onAuthStateChanged(function(user) {
		if (user) {
			if (Array.isArray(passage_ids) && passage_ids.length) {
				for(var i = 0; i < passage_ids.length; i++) {
					var passage_id = passage_ids[i];
					let passage_text = document.getElementById(passage_id).innerText;
					let msmarco_label = document.getElementById(passage_id).className;
					if ($("input:radio[name='"+passage_id+"']").is(":checked") == true) {
						let user_input = document.querySelector('input[name="'+passage_id+'"]:checked').id;
						userCollectionRef.doc(user.uid).collection(query_id).doc(passage_id.toString()).set({
							subject_label: user_input,
							msmarco_label: msmarco_label,
							query: query_text,
							passage: passage_text,
							query_id: query_id,
							passage_id: passage_id
						}).then(function() {
							answersAdded = answersAdded + 1;
							if (answersAdded == passage_ids.length) {
								window.location="query_load_page.php";
							}
						});
					} else {
						userCollectionRef.doc(user.uid).collection(query_id).doc(passage_id.toString()).set({
							subject_label: "no_input",
							msmarco_label: msmarco_label,
							query: query_text,
							passage: passage_text,
							query_id: query_id,
							passage_id: passage_id
						}).then(function() {
							answersAdded = answersAdded + 1;
							if (answersAdded == passage_ids.length) {
								window.location="query_load_page.php";
							}
						});
					}
				}
			}
		} else {
			console.log("user not logged in");
		}
	})
	
	const messageDiv = document.getElementById('message-block2');
	const button = document.getElementById('submit');
	messageDiv.style.display = "block";
	button.style.display = 'none';
	
});