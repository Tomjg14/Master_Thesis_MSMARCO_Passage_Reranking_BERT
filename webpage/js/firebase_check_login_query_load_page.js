window.onload = checkLogin;
function checkLogin() {
	document.getElementById("nextBtn").disabled = true;
	let url_keys = Object.keys(getUrlVars());
	var redirect = false;
	const db = firebase.firestore();
	var auth = firebase.auth()
	const userCollectionRef = db.collection('users');
	if (url_keys.length != 4) {
		//Did not enter page via standard redirect link
		if (url_keys.length == 1) {
			//Entered page via reauthentication page with user session stored
			firebase.auth().onAuthStateChanged(function(user) {
				console.log(quitBool);
				if (quitBool) {
					console.log("succesfull log out");
					window.location = "exit_message.php";
				} else if (user) {
					userCollectionRef.doc(user.uid).get().then(function(doc) {
						if (doc.exists) {
							localStorage.setItem("unassessed_query_ids",doc.data()['unassessed_queries']);
							localStorage.setItem("progress",doc.data()['progress']);
							localStorage.setItem("batches",doc.data()['batches']);
							document.getElementById("nextBtn").disabled = false;
						} else {
							console.log("no such document");
						}
					}).catch(function(error) {
						console.log(error.message);
					});
				} else {
					console.log("not logged in");
				}
			});
		} else {
			//Entered page via either instructions page or from questionnaire
			var unassessed_queries = localStorage.getItem("unassessed_query_ids");
			var progress = localStorage.getItem("progress");
			var batches = localStorage.getItem("batches");
			auth.onAuthStateChanged(user => {
				if (quitBool) {
					console.log("succesfull log out");
					window.location = "exit_message.php";
				}
				else if (finishedBool) {
					window.location = "finished.php"
				}
				else if(!user) {
					window.alert("You need to register before you can participate. If you already did, but something went wrong, then please report this in an email to tomjg@hotmail.nl");
					window.location = 'consent_form.php'; 
				}
				else if(user) {
					userCollectionRef.doc(user.uid).update({
						unassessed_queries: unassessed_queries,
						progress: progress,
						batches: batches
					})
					.then(function() {
						console.log("Document successfully updated!");
						document.getElementById("nextBtn").disabled = false;
					})
					.catch(function(error) {
						console.error("Error updating document: ", error);
					});
				}
			});
		}			
	} else {
		// entered via redirect link send from reauthentication
		const expected_keys = [ "apiKey", "oobCode", "mode", "lang"];
		expected_keys.forEach(key => {
			let key_index = url_keys.indexOf(key);
			if (key_index < 0) {
				redirect = true;
			}
		});
		if (redirect === true) {
			window.alert("Did not access website via redirect link send in email. Please sign in and then continue via the link send via email.");
			window.location = 'reauthenticate.php';
		} else {
			firebase.auth().onAuthStateChanged(function(user) {
				console.log(quitBool);
				if (quitBool) {
					console.log("succesfull log out");
					window.location = "exit_message.php";
				} else if (user) {
					userCollectionRef.doc(user.uid).get().then(function(doc) {
						console.log("logged in");
						if (doc.exists) {
							localStorage.setItem("unassessed_query_ids",doc.data()['unassessed_queries']);
							localStorage.setItem("progress",doc.data()['progress']);
							localStorage.setItem("batches",doc.data()['batches']);
							writeProgress();
							document.getElementById("nextBtn").disabled = false;
						} else {
							console.log("no such document");
						}
					}).catch(function(error) {
						console.log(error.message);
					});
				} else {
					console.log("not logged in");
				}
			});
		}
	}
}