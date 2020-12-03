<!DOCTYPE html>
<HTML lang="en-US">
	<HEAD>
		<title>Thesis Tom</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/query_load_page.css">
		<script src="js/query_load_page.js"></script>
		<script src='dir.php'></script>
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
					<h2 class="text-center"><strong>Loading Query...</strong></h2>
					<p id="progress">
						<script>
						writeProgress();
						</script>
					</p>
					<p id="reminder">
						<script>
						writeReminder();
						</script>
					</p>
					<p class="text-center">Click "Next" to continue.</p>
				</div>
			</main>
			<div class="col-md-12 text-center" id='next-button'> 
				<button class="btn btn-lg btn-success fp-buttons custom-button" type="submit" id="nextBtn">Next</button>
			</div>
			<main class="container" role="main">
				<div id="quitDiv">
				<p><strong>Click "Quit" if you feel you are done assessing.</strong></p>
				<p><strong>You can always return to continue where you left off.</strong></p>
				<button class="btn btn-lg btn-danger fp-buttons custom-button" id="quitBtn">Quit</button>
				</div>
			</main>
		</div>
		<!-- /#page-content-wrapper -->
		
		<script>
		let progress = localStorage.getItem("progress");
		var quitBtn = document.getElementById("quitDiv");
		if (progress > 0) {
			quitBtn.style.display = "inline-block";
		} else {
			quitBtn.style.display = "none";
		}
		</script>
		
		<script src='js/firebase_config.js'></script>
		<script src='js/auth_firebase.js'></script>
		<script src='js/firestore_query_load_page.js'></script>
		<script src='js/url_parameters.js'></script>
		<script src='js/firebase_check_login_query_load_page.js'></script>
		
		<!-- JQuery -->
		<script src="js/jquery.js"></script>
		
		<!-- Bootstrap Core Javascript -->
		<script src="js/bootstrap.min.js"></script>

	</BODY>
</HTML>