const nextBtn = document.getElementById('nextBtn');

var quitBool = false;
var finishedBool = false;

var increments = 0;

const increment = firebase.firestore.FieldValue.increment(1);

nextBtn.addEventListener("click", e =>{
	e.preventDefault();
	
	const db = firebase.firestore();
	
	const usersRef = db.collection("users");
	
	const batchesRef = db.collection("batches");
	
	increments = 0;
	
	getNewQueries();
}); 

quitBtn.addEventListener("click", e => {
	e.preventDefault();
	
	quitBool = true;
	
	firebase.auth().signOut().then(function() {
		console.log("logging out");
	});
	
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
		window.location="questionnaire.php";
	}
}

function getRandomArbitrary(min, max) {
  return Math.floor(Math.random() * (max - min) + min);
}

async function getBatch() {
	let unassessed_query_ids = JSON.parse(localStorage.getItem("unassessed_query_ids"));
	if (unassessed_query_ids.length == 0) {
		var batches = JSON.parse(localStorage.getItem("batches"));
		if (batches.length == 200) {
			finishedBool = true;
			firebase.auth().signOut().then(function() {
				window.location="finished.php";
			});
		}
		const unassessed_docs = await getUnassessedDocs();
		const finished_docs = await getFinishedDocs();
		var batch1 = {};
		var batch2 = {};
		if (unassessed_docs.length < 2) {
			batch1 = finished_docs[getRandomArbitrary(0,Math.floor(finished_docs.length/2))];
			batch2 = finished_docs[getRandomArbitrary(Math.floor(finished_docs.length/2)+1,finished_docs.length)];
		} else {
			batch1 = unassessed_docs[0];
			batch2 = unassessed_docs[1];
		}
		var query_ids = Array();
		Array.prototype.push.apply(query_ids,Object.values(batch1.data()).slice(0,3));
		Array.prototype.push.apply(query_ids,Object.values(batch2.data()).slice(0,3));
		var prev_query_ids = JSON.parse(localStorage.getItem("query_ids"));
		localStorage.setItem("assessed_queries",JSON.stringify(prev_query_ids));
		localStorage.setItem("query_ids",JSON.stringify(query_ids));
		localStorage.setItem("unassessed_query_ids",JSON.stringify(query_ids));
		Array.prototype.push.apply(batches,[batch1.id,batch2.id]);
		localStorage.setItem("batches",JSON.stringify(batches));
		incrementAssessments(batch1.id);
		incrementAssessments(batch2.id);
	} else {
		window.location="questionnaire.php";
	}
}

async function getNewQueries() {
	await getBatch();
}