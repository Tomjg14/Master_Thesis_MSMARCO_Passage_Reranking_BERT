const btn = document.getElementById('nextBtn');

var increments = 0;

const increment = firebase.firestore.FieldValue.increment(1);

btn.addEventListener("click", e =>{
	e.preventDefault();
	
	increments = 0;
	
	countUsers();
	
	const messageDiv = document.getElementById('message-block');
	const button = document.getElementById('nextBtn');
	messageDiv.style.display = "block";
	button.style.display = 'none';
}); 

async function getUsers() {
	const snapshot = await firebase.firestore().collection("users").get()
	return snapshot.docs.map(doc => doc.data());
}

async function getFinishedDocs() {
	const snapshot = await firebase.firestore().collection("batches").where("assessments",">",2).get()
	return snapshot.docs.sort(function(a,b){return a.data()['created'].toDate() - b.data()['created'].toDate()})
}

async function getAssessedDocs() {
	const snapshot = await firebase.firestore().collection("batches").where("assessments",">",0).where("assessments","<",3).get()
	return snapshot.docs.sort(function(a,b){return a.data()['created'].toDate() - b.data()['created'].toDate()})
}

async function getUnassessedDocs(){
	const snapshot = await firebase.firestore().collection("batches").where("assessments","==",0).get()
	//return snapshot.docs.map(doc => doc.data());
	return snapshot.docs.sort(function(a,b){return a.data()['created'].toDate() - b.data()['created'].toDate()})
}

async function incrementAssessments(doc_id) {
	await firebase.firestore().collection("batches").doc(doc_id).update({"assessments": increment}).then(() => {increments = increments + 1;});
	if (increments == 2) {
		window.location="query_load_page.php";
	}
}

function getRandomArbitrary(min, max) {
  return Math.floor(Math.random() * (max - min) + min);
}

async function onInit() {
	var docs = await getUnassessedDocs();
	var query_ids = Array();
	var batches = Array();
	if (docs.length < 2) {
		docs = await getAssessedDocs();
		if (docs.length < 2) {
			docs = await getFinishedDocs();
		}
	}
	const first_two_docs = docs.slice(0,2);
	first_two_docs.forEach(function(doc) {
		Array.prototype.push.apply(query_ids,Object.values(doc.data()).slice(0,3));
		Array.prototype.push.apply(batches,doc.id);
		localStorage.setItem("query_ids",JSON.stringify(query_ids));
		localStorage.setItem("unassessed_query_ids",JSON.stringify(query_ids));
		localStorage.setItem("batches",JSON.stringify(batches));
		incrementAssessments(doc.id);
	});
	localStorage.setItem("progress",JSON.stringify(0));
}

async function afterInit() {
	const assessed_docs = await getAssessedDocs();
	const unassessed_docs = await getUnassessedDocs();
	const finished_docs = await getFinishedDocs();
	var batch1 = {};
	var batch2 = {};
	if (assessed_docs.length < 2) {
		if (unassessed_docs.length < 2) {
			batch1 = finished_docs[getRandomArbitrary(Math.floor(finished_docs.length/2)+1,finished_docs.length)];
			batch2 = finished_docs[getRandomArbitrary(0,Math.floor(finished_docs.length/2))];
		} else {
			batch1 = unassessed_docs[0];
			batch2 = unassessed_docs[1];
		}
	} else {
		if (unassessed_docs.length == 0) {
			batch1 = assessed_docs[0];
			batch2 = assessed_docs[1];
		} else {
			batch1 = assessed_docs[0];
			
			var options = [5,5];
			var probability = options.map((v,i) => Array(v).fill(i)).reduce((c,v) => c.concat(v), []);
			
			var pos = probability[Math.floor((Math.random() * probability.length))];
			if (pos == 0) {
				batch2 = assessed_docs[1];
			} else {
				batch2 = unassessed_docs[0];
			}
		}
	}

	var query_ids = Array();
	Array.prototype.push.apply(query_ids,Object.values(batch1.data()).slice(0,3));
	Array.prototype.push.apply(query_ids,Object.values(batch2.data()).slice(0,3));
	localStorage.setItem("query_ids",JSON.stringify(query_ids));
	localStorage.setItem("unassessed_query_ids",JSON.stringify(query_ids));
	localStorage.setItem("progress",JSON.stringify(0));
	localStorage.setItem("batches",JSON.stringify([batch1.id,batch2.id]));
	incrementAssessments(batch1.id);
	incrementAssessments(batch2.id);
}

async function countUsers() {
	const users = await getUsers();
	if (users.length == 0) {
		await onInit();
	} else {
		await afterInit();
	}
}