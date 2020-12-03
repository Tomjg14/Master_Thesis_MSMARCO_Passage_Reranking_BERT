// Your web app's Firebase configuration
var firebaseConfig = {
	apiKey: "AIzaSyBggdpcFkMAHFRRjNbYeoj8_I1eTcTkHHk",
	authDomain: "master-thesis-website.firebaseapp.com",
	databaseURL: "https://master-thesis-website.firebaseio.com",
	projectId: "master-thesis-website",
	storageBucket: "gs://master-thesis-website.appspot.com",
	messagingSenderId: "555484346882",
	appId: "1:555484346882:web:442a27c5ba688be505851b"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
		
var database = firebase.database();

document.addEventListener("DOMContentLoaded", event => {
	const app = firebase.app();
	
	const db = firebase.firestore();
});
