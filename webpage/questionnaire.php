<!DOCTYPE html>
<HTML lang="en-US">
	<HEAD>
		<title>Thesis Tom</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="css/general.css">
		<link rel="stylesheet" type="text/css" href="css/questionnaire.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
		<script type="text/javascript" src="js/url_parameters.js"></script>
		<script type="text/javascript" src="js/questionnaire.js"></script>
		<script type="text/javascript" src="js/local_storage.js"></script>
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
		
		<div id="page-content-wrapper">
			<div id= "header" class="container carousel-overlay text-center">
			</div>
	
			<main class="container" role="main">
			<div class="starter-template">
			<form name="questionnaire">
				<table id="query_table" cellspacing="50">
					<tbody>
						<script>
							// dir.php loads all files
							var unassessed_queries = JSON.parse(localStorage.getItem("unassessed_query_ids"));
							var query_id = unassessed_queries[Math.random() * unassessed_queries.length | 0]
							var file_path = 'files/sampled_files/' + query_id + '.json';
							showQuery(file_path);
							showPassages(file_path);
							updateArray(query_id);
						</script>
					</tbody>
				</table>
				<div class="col-md-12 text-center">
					<div class="col-md-12 text-center" id='message-block2'>
						<br>
						Saving data, please wait...
						<br>
					</div>
					<button class="btn btn-lg btn-success fp-buttons" type="submit" id="submit">Submit Answers</button>
				</div>
			</form>
			</div>
			</main>
		</div>
		
		<script>
			//Handling Contact Form
			const messageDiv = document.getElementById('message-block2');
			messageDiv.style.display = "none";
		</script>
		
		<script src='js/firebase_config.js'></script>
		<script src='js/firestore_questionnaire.js'></script>
		<script src='js/firebase_check_login_questionnaire.js'></script>

		<!-- JQuery -->
		<script src="js/jquery.js"></script>
		
		<!-- Bootstrap Core Javascript -->
		<script src="js/bootstrap.min.js"></script>

	</BODY>
</HTML>