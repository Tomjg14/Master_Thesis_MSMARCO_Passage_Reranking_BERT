window.onload = checkLogin;
function checkLogin() {
	var auth = firebase.auth()
	auth.onAuthStateChanged(user => {
		if(!user) {
			window.alert("You need to register before you can participate. If you already did, but something went wrong, then please report this in an email to tomjg@hotmail.nl");
			window.location = 'consent_form.php'; 
		}
	});
}