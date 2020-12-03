if (firebase.auth().isSignInWithEmailLink(window.location.href)) {
	var email = window.localStorage.getItem('emailForSignIn');
	if (!email) {
		email = window.prompt('Please provide your email for confirmation');
	}
	
	firebase.auth().signInWithEmailLink(email, window.location.href)
	.then(function(result) {
		window.localStorage.removeItem('emailForSignIn');
	})
	.catch(function(error) {
		window.alert(error.message);
		window.location = 'consent_form.php';
	});
}
