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
					<h1 class="text-center"><strong>Participant Information</strong></h1>
					<p>Below you find more detailed information about the reason for my research, what work is expected of the participants and what data will be collected.
					Please read this page carefully.</p>
					
					<h3 class="text-center">MSMARCO Dataset</h3>
					<p><a HREF="https://github.com/microsoft/MSMARCO-Passage-Ranking" target="_blank">MSMARCO</a> stands for Microsoft Machine Reading Comprehension.
					It is a large scale dataset focused on machine reading comprehension, question answering, passage ranking, keyphrase extraction, and conversational search studies (<a HREF='files/msmarco_dataset.pdf' target="_blank">paper</a>).
					The dataset is created by sampling and anonymizing <a HREF="https://bing.com" target="_blank">bing.com</a> usage logs.
					This means that the queries in the dataset were issued by users of Bing; they can be messy: just a couple of terms or full of spelling/grammar mistakes.
					The passages in the dataset represent short text examples of webpages presented on the query result page of Bing.
					These passages, which initially were retrieved by Bing, might contain relevant information to the entered query.
					Currently, each query has only a few passages marked as relevant.
					Our hypothesis is that, for each query, the dataset contains more relevant passages</p>
					
					<h3 class="text-center">Task Participants</h3>
					<p>As state-of-the-art algorithms are being tested on the dataset, future research could benefit from a dataset with more relevancy labels.
					Therefore you are asked to help gather more query-passage pair relevancy data.
					You will be presented numerous queries and for each query, 20 passages are presented.
					Please take your time to read every passage carefully, as you are asked to grade them on their degree of relevancy compared to the query.
					You can take as many time as you like to label each passage and as long as you are on the passage labeling page you are able to alter your answers.
					When you are done labeling the 20 passages for a given query, you can submit your answers by pressing the 'Submit Answers' button.
					A new query will be selected as well as 20 passages.
					You may assess as many queries as you like, as the number of queries presented is endless.
					When you are done, please press the <u>'Quit' button</u> to ensure that your data is properly stored.</p>
					
					<h3 class="text-center">Data Collection</h3>
					<p>The main goal of this online assessment is to collect query-passage relevancy labels.
					Therefore, from each participant, we collect the relevancy grade for each of the 20 passages per query.
					We thus keep track of the query, the passage, and the label provided by the participant.
					In order to ensure that participants do not participate multiple times, and to correctly store any input, we ask for the participants email.
					A verification link will be send to their email, from which the participants can access the online assessment.
					Their email will be stored for the entire duration of the study.
					When the study is finished, we will anonymize the collected data by replacing all the email addresses with randomized identifiers.
					The provided labels will be archived for use in possible future research.
					Finally, we also ask each participant to indicate their level of English.
					The queries and passages are in English and might contain complex language.
					An indication of the level of English from each participant would help us process participants answers when anomalies appear.
					This data will be stored for the duration of the study and also archived when the study is finished.</p>
					
					<h3 class="text-center">Important Notes</h3>
					<p>Please be sure to <strong><u>free up enough time</u></strong> to start with the assessments. We would like you to read and assess every query-passage pair with care.
					Also keep in mind to <strong><u>always press the 'Submit Answers' and 'Quit' button</u></strong>, to ensure that assessments are properly stored.
					<strong><u>One round of assessments (20 passages) takes approximately 5 minutes</u></strong>. After each round you get the possibility to quit.
					If you decide to quit, you can always return to help us gather more assessments.
					It would be very much appreciated if you would <strong><u>assess at least 6 queries</u></strong>, which takes approximately <strong><u>30 minutes</u></strong>.
					On the next page you will find a consent-form. Please fill this form, before you start with assessing.</p>
				</div>
			</main>
			<div class="col-md-12 text-center" id="buttons"> 
				<a class="btn btn-lg btn-success fp-buttons custom-button" href="index.php">
					<span class="fa fa-check"></span> Previous
				</a>
				<a class="btn btn-lg btn-success fp-buttons custom-button" href="consent_form.php">
					<span class="fa fa-check"></span> Next
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