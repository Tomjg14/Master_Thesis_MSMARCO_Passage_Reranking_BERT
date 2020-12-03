<!DOCTYPE html>
<HTML lang="en-US">
	<HEAD>
		<title>Thesis Tom</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/consent.css">
	</HEAD>

	<BODY>
		
		<!-- Page Content -->
		<div id="page-content-wrapper">
			<main class="container" role="main">
			<div class="starter-template">
				<h1 id="consent" class="text-center"><strong>Consent Form</strong></h1>
				<p>You are being invited to participate in an online questionnaire as part of a master thesis. This master thesis is being done by Tom Janssen Groesbeek form the Radboud University, Nijmegen.
				The purpose of this online questionnaire is to collect more relevancy labels for query-passage pairs selected from the MSMARCO dataset. The collected data will be used to re-evaluate the performance of existing natural language processing algorithms used for ranking, such as BERT and BM25.
				Your participation in this study is entirely voluntary and you can withdraw at any time. You are free to omit any question.
				We believe there are no known risks associated with this research study; however, as with any online related activity the risk of a breach is always possible. To the best of our ability your answers in this study will remain confidential. We will minimize any risks by storing your emailaddress in a restricted database only for the duration of the study. Afterwards, your emailaddress will be replaced for a random identifier and only your assessments will be archived.
				In case you have any questions, you can send them to <a HREF="mailto:tomjg@hotmail.nl">tomjg@hotmail.nl</a>.
				Please fill in the following consent form<sup><a HREF="https://www.tudelft.nl/over-tu-delft/strategie/strategiedocumenten-tu-delft/integriteitsbeleid/human-research-ethics/template-informed-consent-form/" target="_blank">1</a></sup>.</p>
				<table id="consent-form" cellspacing="50">
					<tbody>
					<tr>
						<th><i>Please tick the appropriate boxes</i></th>
						<th class="input">Yes</th>
					</tr>
					<tr>
						<th>Taking part in the study</th>
						<th></th>
					</tr>
					<tr>
						<td>I have read and understood the study information dated 27/03/2020, or it has been read to me. I have been able to ask questions about the study and (in case of any) my questions have been answered to my satisfaction.</td>
						<td class="input"><input type="checkbox" name="consent" value="yes" /></td>
					</tr>
					<tr>
						<td>I consent to be a participant in this study and understand that I can refuse to answer questions and I can withdraw from the study at any time, without having to give a reason.</td>
						<td class="input"><input type="checkbox" name="consent" value="yes" /></td>
					</tr>
					<tr>
						<td>I understand that taking part in the study involves that data is collected in the form of a survey questionnaire completed by me the participant.</td>
						<td class="input"><input type="checkbox" name="consent" value="yes" /></td>
					</tr>
					<tr>
						<th>Use of the information in the study</th>
						<th></th>
					</tr>
					<tr>
						<td>I understand that personal information collected about me, will not be shared beyond the study team.</td>
						<td class="input"><input type="checkbox" name="consent" value="yes" /></td>
					</tr>
					<tr>
						<th>Future use and reuse of the information by others</th>
						<th></th>
					</tr>
					<tr>
						<td>I give permission for the assessments that I provide to be archived so it can be used for future research and learning.</td>
						<td class="input"><input type="checkbox" name="consent" value="yes" /></td>
					</tr>
						<td>I understand that personal information collected about me, will not be archived and that data collected from me will be anonymized.</td>
						<td class="input"><input type="checkbox" name="consent" value="yes" /></td>
					</tbody>
					<tr>
						<th>Results Study</th>
						<th></th>
					</tr>
					<tr>
						<td>Do you want to receive informations on the results of this study?</td>
						<td class="input"><input type="checkbox" name="contact" value="yes"/></td>
					<tr>
				</table>
			</main>
			<div class="col-md-12 text-center" id="buttons"> 
				<button class="btn btn-lg btn-success fp-buttons custom-button" id='prevBtn'>Previous</button>
				<button class="btn btn-lg btn-success fp-buttons custom-button" id="nextBtn" disabled>Next</button>
			</div>
			</div>
		</div>
		<!-- /#page-content-wrapper -->
		
		<script type="text/javascript" src="js/consent.js"></script>
		
		<!-- JQuery -->
		<script src="js/jquery.js"></script>
		
		<!-- Bootstrap Core Javascript -->
		<script src="js/bootstrap.min.js"></script>

	</BODY>
</HTML>