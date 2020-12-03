<!DOCTYPE html>
<HTML lang="en-US">
	<HEAD>
		<title>Thesis Tom</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/registration.css">
	</HEAD>

	<BODY>
		<!-- The core Firebase JS SDK is always required and must be listed first -->
		<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js"></script>

		<!-- TODO: Add SDKs for Firebase products that you want to use
		https://firebase.google.com/docs/web/setup#available-libraries -->
		
		<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js"></script>
		<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-database.js"></script>
		<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-firestore.js"></script>

		<!-- Page Content -->
		<div id="page-content-wrapper">
			<main class="container" role="main">
				<div class="starter-template">
					<h1 class='text-center'><strong>Re-Authenticate</strong></h1>
					<p>On this page you can continue with the assessments where you left off.
					This is only possible if you have previously registered with an existing email adress.
					If you did not do so then please go to the following webpage:  <a href="http://rure.cs.ru.nl/tomjg/Master_Thesis_CoAs_BM25_BERT/webpage/information_page.php">information_page.php</a> </p>
					<p class='text-center'>If you encounter any problems, please contact me via <a HREF="mailto:tomjg@hotmail.nl">tomjg@hotmail.nl</a>.</p>
				</div>
			</main>
			<div class="col-md-12 text-center" id='contact-form'> 
				<form action="javascript:emailLinkLogin(document.getElementById('email').value)">
					<label for="email"><b>Email</b></label>
					<input type="text" placeholder="Enter Email" id="email" required>
					<div id="btn">
					<button class="btn btn-lg btn-success fp-buttons custom-button" type="submit" id="register">Sign-In</button>
					</div>
				</form>
			</div>
			<div class="col-md-12 text-center" id='message-block'>
				<br>
			</div>
		</div>
		<!-- /#page-content-wrapper -->

		<script>
			//Handling Contact Form
			const contactDiv = document.getElementById('contact-form');
			const messageDiv = document.getElementById('message-block');
			contactDiv.style.display = "block";
			messageDiv.style.display = "none";
		</script>
		
		<script>
			//Handling Contact Form
			document.getElementById('register').addEventListener('click', event => {
				const contactDiv = document.getElementById('contact-form');
				const messageDiv = document.getElementById('message-block');
				contactDiv.style.display = "block";
				messageDiv.style.display = "block";
			});
		</script>
		
		<script src='js/firebase_config.js'></script>
		<script src='js/reauthenticate.js'></script>
		
		<!-- JQuery -->
		<script src="js/jquery.js"></script>
		
		<!-- Bootstrap Core Javascript -->
		<script src="js/bootstrap.min.js"></script>
		
	</BODY>
</HTML>