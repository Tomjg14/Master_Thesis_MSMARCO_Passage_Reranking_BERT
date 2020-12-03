function emailLinkLogin(email) {
	
	var actionCodeSettings = {
		url: 'http://rure.cs.ru.nl/tomjg/Master_Thesis_CoAs_BM25_BERT/webpage/instructions.php',
		handleCodeInApp: true
	}
	
	firebase.auth().fetchSignInMethodsForEmail(email)
	.then(function(signInMethods) {
		if (signInMethods.indexOf(firebase.auth.EmailAuthProvider.EMAIL_LINK_SIGN_IN_METHOD) != -1) {
			let messageDiv = document.getElementById('message-block');
			messageDiv.innerHTML = '';
			var messagePar = document.createElement("p");
			messagePar.innerHTML = '<br><strong>Email has already been used.</strong>';
			messageDiv.appendChild(messagePar);
		} else {
			firebase.auth().setPersistence(firebase.auth.Auth.Persistence.LOCAL)
			.then(function() {
				firebase.auth().sendSignInLinkToEmail(email, actionCodeSettings)
				.then(function() {
					window.localStorage.setItem('emailForSignIn', email);
					let messageDiv = document.getElementById('message-block');
					messageDiv.innerHTML = '';
					var messagePar = document.createElement("p");
					messagePar.innerHTML = '<br><strong>Link has been send.</strong>';
					messageDiv.appendChild(messagePar);
				})
				.catch(function(error) {
					window.alert(error.message);
					console.log(error.code);
				});
			})
			.catch(function(error) {
				// Handle Errors here.
				console.log(error.code);
				console.log(error.message);
				window.alert(error.message);
			});
		}
	})
	.catch(function(error) {
		let messageDiv = document.getElementById('message-block');
		messageDiv.innerHTML = '';
		var messagePar = document.createElement("p");
		messagePar.innerHTML = '<br><strong>That e-mail address is invalid.<br> Please enter a valid address.</strong>';
		messageDiv.appendChild(messagePar);
	});
	
}