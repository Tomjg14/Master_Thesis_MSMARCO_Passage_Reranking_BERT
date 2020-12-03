//var checkboxElems = document.querySelectorAll("input[type='checkbox']");
var checkboxElems = document.querySelectorAll("input[name='consent']");

var contactBool = false;

for (var i = 0; i < checkboxElems.length; i++) {
	checkboxElems[i].addEventListener("click", allChecked);
}
			
function allChecked(e){
	var checkedBoxes = [];
	for (var i = 0; i< checkboxElems.length; i++) {
		if (checkboxElems[i].checked) {
			checkedBoxes.push(checkboxElems[i]);
		}
	}
	if (checkedBoxes.length == 6) {
		document.getElementById("nextBtn").disabled = false;
	} else {
		document.getElementById("nextBtn").disabled = true;
	}
}

var checkboxContactElement = document.querySelector("input[name='contact']");

checkboxContactElement.addEventListener("click", checked);

function checked(e) {
	if (checkboxContactElement.checked) {
		console.log("checked");
		contactBool = true;
	} else {
		console.log("not checked");
		contactBool = false;
	}
}

document.getElementById('nextBtn').addEventListener('click', event => {
	localStorage.setItem("contact",contactBool);
	localStorage.setItem("consent",true);
	window.location = 'english_level.php';
});

document.getElementById('prevBtn').addEventListener('click', event => {
	window.location = "information_page.php";
});