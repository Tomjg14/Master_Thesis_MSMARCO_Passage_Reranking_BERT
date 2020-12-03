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
				<h1 id="consent" class="text-center"><strong>Your English Level</strong></h1>
				<p>Before you can start with the questionnaire, we would like to get an indication of your level of English. Below you can check a level on a scale from 1 (Beginner) to 9 (Very Advanced). Please check the appropiate level and press "Next" to continue to the final instructions<sup><a HREF="https://www.londonschool.com/level-scale/" target="_blank">1</a></sup>.</p>
				<p class="text-center"><i>Please tick the appropriate box</i></p>
				<table id="consent-form" cellspacing="50">
					<tbody>
					<tr>
						<th>Level</th>
						<th>Class Level</th>
						<th>Description</th>
						<th>CEFR Level</th>
						<th></th>
					</tr>
					<tr>
						<td>1</td>
						<td>Beginner</td>
						<td>I do not speak any English.</td>
						<td></td>
						<td class="input"><input type="radio" name="english" value="1" /></td>
					</tr>
					<tr>
						<td>2</td>
						<td>Elementary</td>
						<td>I can say and understand a few things in English.</td>
						<td>A1/2</td>
						<td class="input"><input type="radio" name="english" value="2" /></td>
					</tr>
					<tr>
						<td>3</td>
						<td>Pre-intermediate</td>
						<td>I can communicate simply and understand in familiar situations but only with some difficulty.</td>
						<td>A2</td>
						<td class="input"><input type="radio" name="english" value="3" /></td>
					</tr>
					<tr>
						<td>4</td>
						<td>Low Intermediate</td>
						<td>I can make simple sentences and can understand the main points of a conversation but need much more vocabulary.</td>
						<td>B1</td>
						<td class="input"><input type="radio" name="english" value="4" /></td>
					</tr>
					<tr>
						<td>5</td>
						<td>Intermediate</td>
						<td>I can speak and understand reasonably well and can use basic tenses but have problems with more complex grammar and vocabulary.</td>
						<td>B1</td>
						<td class="input"><input type="radio" name="english" value="5" /></td>
					</tr>
						<td>6</td>
						<td>Upper Intermediate</td>
						<td>I can communicate without much difficulty but still make quite a lot of mistakes and misunderstand sometimes.</td>
						<td>B2</td>
						<td class="input"><input type="radio" name="english" value="6" /></td>
					<tr>
						<td>7</td>
						<td>Pre-advanced</td>
						<td>I speak and understand well but still make mistakes and sometimes people do not understand me clearly.</td>
						<td>C1</td>
						<td class="input"><input type="radio" name="english" value="7"/></td>
					</tr>
					<tr>
						<td>8</td>
						<td>Advanced</td>
						<td>I speak and understand very well but sometimes have problems with unfamiliar situations and vocabulary.</td>
						<td>C2</td>
						<td class="input"><input type="radio" name="english" value="8"/></td>
					</tr>
					<tr>
						<td>9</td>
						<td>Very Advanced</td>
						<td>I speak and understand English completely fluently.</td>
						<td>C2</td>
						<td class="input"><input type="radio" name="english" value="9"/></td>
					</tr>
					</tbody>
				</table>
			</main>
			<div class="col-md-12 text-center" id="buttons"> 
				<button class="btn btn-lg btn-success fp-buttons custom-button" id='prevBtn'>Previous</button>
				<button class="btn btn-lg btn-success fp-buttons custom-button" id="nextBtn" disabled>Next</button>
			</div>
			</div>
		</div>
		<!-- /#page-content-wrapper -->
		
		<script type="text/javascript" src="js/english.js"></script>
		
		<!-- JQuery -->
		<script src="js/jquery.js"></script>
		
		<!-- Bootstrap Core Javascript -->
		<script src="js/bootstrap.min.js"></script>

	</BODY>
</HTML>