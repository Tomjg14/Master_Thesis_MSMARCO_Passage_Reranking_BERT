<!DOCTYPE html>
<HTML lang="en-US">
	<HEAD>
		<title>Thesis Tom</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/general.css">
	</HEAD>

	<BODY>
		
		<!-- Page Content -->
		<div id="page-content-wrapper">
			<main class="container" role="main">
				<div class="starter-template">
					<h1 class="text-center"><strong>Welcome</strong></h1>
					<p>My name is Tom Janssen Groesbeek and I am a data science student at the Radboud University, Nijmegen.
					Currently, I am working on my master thesis which is on machine reading comprehension and question answering.
					More specifically, I am interested in a dataset often used in question answering research named MSMARCO Ranking dataset.
					The dataset contains queries (questions) and many passages, which may or may not contain relevant information related to each query.
					The dataset contains only a few passages labeled as truly relevant for each query; my research requires a more complete set of labels. 
					If you continue below, you will help me fill in the gaps on currently unlabeled passages.
					More information can be found on the following pages.</p>
					<p class="text-center">Please access this questionnaire on a <u>computer</u> via either <u>Firefox or Chrome</u>.</p>
					<p class="text-center">This webpage and questionnaire is in English, so do not let your browser translate it to another language.</p>
					<p class="text-center" id="thanks">Thanks in advance!</p>
				</div>
			</main>
			<div class="col-md-12 text-center"> 
				<p>Please click the "Get Started" button if you did not participate before:</p>
				<a class="btn btn-lg btn-success fp-buttons custom-button" href="information_page.php">
					<span class="fa fa-check"></span> Get Started
				</a>
			</div>
			<div class="col-md-12 text-center"> 
				<p>Otherwise, sign-in to continue where you left off:</p>
				<a class="btn btn-lg btn-success fp-buttons custom-button" href="reauthenticate.php">
					<span class="fa fa-check"></span> Sign-In
				</a>
			</div>
		</div>
		<!-- /#page-content-wrapper -->
		
		<!-- JQuery -->
		<script src="js/jquery.js"></script>
		
		<!-- Bootstrap Core Javascript -->
		<script src="js/bootstrap.min.js"></script>

	</BODY>
</HTML>