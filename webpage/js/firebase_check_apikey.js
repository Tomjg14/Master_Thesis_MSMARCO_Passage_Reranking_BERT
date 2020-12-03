window.onload = checkApiKey;

function checkApiKey() {
	document.getElementById("nextBtn").disabled = true;
	let url_keys = Object.keys(getUrlVars());
	var redirect = false;
	if (url_keys.length !== 4) {
		window.alert("Did not access website via redirect link send in email. Please register and then continue via the link send via email.");
		window.location = 'consent_form.php';
	} else {
		const expected_keys = [ "apiKey", "oobCode", "mode", "lang"];
		expected_keys.forEach(key => {
			let key_index = url_keys.indexOf(key);
			if (key_index < 0) {
				redirect = true;
			}
		});
		if (redirect === true) {
			window.alert("Did not access website via redirect link send in email. Please register and then continue via the link send via email.");
			window.location = 'consent_form.php';
		} else {
			const db = firebase.firestore();
	
			const userCollectionRef = db.collection('users');
	
			const consentAnswer = localStorage.getItem("consent");
			const contactAnswer = localStorage.getItem("contact");
			const englishLevel = localStorage.getItem("english_level");
			
			if ((consentAnswer == null || consentAnswer == undefined) || (contactAnswer == null || contactAnswer == undefined) || (englishLevel == null || englishLevel == undefined)){
				firebase.auth().onAuthStateChanged(function(user) {
					if (user) {
						user.delete().then(function() {
							window.alert("You tried to open the redirect link via another device than the one on which you entered your emailaddress. Please make sure to open the link on the same device, preferebly a computer or laptop.")
							window.location = 'consent_form.php';
						}).catch(function() {
							console.log("could not delete user");
						});
					}
				});
			} else {
				firebase.auth().onAuthStateChanged(function(user) {
					if (user) {
						userCollectionRef.doc(user.uid).set({email: user.email, consent: consentAnswer, contact: contactAnswer, english: englishLevel}).then(function() {
						});
						document.getElementById("nextBtn").disabled = false;
					} else {
						console.log("not logged in");
					}
			});
			}
		}
	}
}