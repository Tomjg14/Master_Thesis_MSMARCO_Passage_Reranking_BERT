var checkboxElems = document.querySelectorAll("input[name='english']");

for (var i = 0; i < checkboxElems.length; i++) {
	checkboxElems[i].addEventListener("click", allChecked);
}
			
function allChecked(e){
	var checkedBoxes = [];
	for (var i = 0; i< checkboxElems.length; i++) {
		if (checkboxElems[i].checked) {
			checkedBoxes.push(checkboxElems[i]);
			localStorage.setItem("english_level",checkboxElems[i].value)
			console.log(localStorage.getItem("english_level"));
		}
	}
	if (checkedBoxes.length == 1) {
		document.getElementById("nextBtn").disabled = false;
	} else {
		document.getElementById("nextBtn").disabled = true;
	}
}

document.getElementById('nextBtn').addEventListener('click', event => {
	window.location = 'registration.php';
});

document.getElementById('prevBtn').addEventListener('click', event => {
	window.location = "consent_form.php";
});