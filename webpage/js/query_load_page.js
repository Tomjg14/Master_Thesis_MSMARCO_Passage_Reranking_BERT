function writeProgress(){
	let progress = JSON.parse(localStorage.getItem("progress"));
	let paragraph = document.getElementById('progress');
	if (progress == 1) {
		paragraph.innerHTML = 'Progress: You have assessed ' + progress +' query.';
	} else {
		paragraph.innerHTML = 'Progress: You have assessed ' + progress +' queries.';
	}
}

function writeReminder() {
	let progress = localStorage.getItem("progress");
	let reminder = document.getElementById('reminder');
	if (progress > 5) {
		reminder.innerHTML = ''
		reminder.innerHTML = 'Reminder: You can quit anytime you like. The more queries you assess the better! But keep in mind to press the "Submit Answers" or "Quit" button in order for your answers to be saved.'
	} else {
		reminder.innerHTML = ''
		reminder.innerHTML = 'Reminder: your answers are saved only when you press the "Submit Answers" button on the query page. When you submit your answers, there is no going back. So take your time assessing each query-passage combination.'
	}
}

