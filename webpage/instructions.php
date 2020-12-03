<!DOCTYPE html>
<HTML lang="en-US">
	<HEAD>
		<title>Thesis Tom</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/general.css">
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
					<h1 class='text-center'>Final Instructions</h1>
					<p>Unlike most questionnaires, you are not expected to answer the questions.
					Instead, one by one, multiple queries are displayed accompanied by 20 information passages.
					A query can best be understood as a request issued to a search engine like Google or Bing.
					The passages contain information and you are tasked to specify for each passage if it is relevant to the query.
					You can pick a degree of relevancy from 1 (not relevant) till 5 (perfectly relevant).
					For each query-passage pair, try to decide which degree fits the pair best.
					It may be the case that either the query or the passages do not make any sense or contain strange symbols.
					This is just the way the data was stored in the dataset, so do not worry.</p>
					
					<p>Futhermore, questions vary in difficulty, and the complexity differs for computers and for humans! 
					You will notice many near-duplicate results, this is not an error in the experimental setup but a property of the dataset (created from search engine results). 
					It is perfectly normal to see questions with only relevant answers, but also questions without any relevant answer. 
					Please evaluate each answer passage in isolation, i.e., do not take into account that you saw a near-duplicate answer before. 
					Try to be consistent, but you do not have to revisit previous answers to check the relevance degree you chose - that would be too time-consuming.</p>
					
					<p>Always try to give your best assessment.</p>
					
					<p><strong><u>Important Note</u>: Never manually go back and never refresh the page! In both cases data will be lost or stored incorrectly.
					When you done assessing a query, press the 'Submit Answers' button to store your input and continue with the questionnaire.
					If you are done assessing, press the 'Quit' button to store your final input and exit the questionnaire.
					When you press 'Quit', you can always return to provide us with more assessments.</strong></p>
					
					<p><strong>Again, it would be very much appreciated if you would <u>assess at least 6 queries</u>.</p></strong>
					
					<p class='text-center'>By clicking the "Next" button below you will start the questionnaire.</p>
				</div>
			</main>
			<div class="col-md-12 text-center" id='message-block'>
				<br>
					Loading questionnaire, please wait...
				<br>
			</div>
			<div class="col-md-12 text-center" id="button"> 
				<button class="btn btn-lg btn-success fp-buttons custom-button" type="submit" id="nextBtn">Next</button>
			</div>
			
		</div>
		<!-- /#page-content-wrapper -->
		
		<script>
			const messageDiv = document.getElementById('message-block');
			messageDiv.style.display = "none";
		</script>
		
		<script src='js/firebase_config.js'></script>
		<script src='js/auth_firebase.js'></script>
		<script src='js/firestore_instructions.js'></script>
		<script src='js/url_parameters.js'></script>
		<script src='js/firebase_check_apikey.js'></script>

		<!-- JQuery -->
		<script src="js/jquery.js"></script>
		
		<!-- Bootstrap Core Javascript -->
		<script src="js/bootstrap.min.js"></script>

	</BODY>
</HTML>