# Firebase

In order to collect the data I needed for my thesis, I had to set up a website with database. On this website people could then sign-up and assess many different query-passages pairs. Because I did not know the amount of people that would sign-up and also because the number of pairs that should be assessed was numerous, it was clear that a large part of my website should be generic. In order to fullfill this need, I decided to use Google's mobile app development platform named [Firebase](https://firebase.google.com/). On this page you will find more information on what Firebase is and how I used it to be able to gather and store user input. 

Page Content:
- [What is Firebase?](#what-is-firebase)
- [How did I use Firebase for my Thesis?](#how-did-i-use-firebase-for-my-thesis)
    - [Configuration](#configuration)
    - [Authentication](#authentication)
    - [Firestore](#firestore)

## What is Firebase?

As mentioned above, the short answer to this question is that Firebase is a mobile app development platform. There are many other articles written on Firebase and what it exactly is and how you can use it. There is even a [Youtube channel](https://www.youtube.com/user/Firebase) on how to use Firebase, which I often consulted while working on my thesis. Here I just want to explain why and how I used Firebase for my thesis. That is why I will not go into much detail. If you do want all the details on Firebase I suggest you read the [Medium](https://medium.com/) article by [Doug Stevenson](https://medium.com/firebase-developers/what-is-firebase-the-complete-story-abridged-bcc730c5f2c0). This article covers all the tools Firebase offers and it is also a very funny piece to read.

I view Firebase more as a toolkit to help me build functionalities for websites and apps. If I want people to be able to sign-up or sign-in with my website or app, I could build this functionality from scratch by myself. But I could also use Firebase authentication tools to skip the part where you write the entire code yourself and you simple incorporate familiar authentication processes. Like making it possible for users to sign-up with their Facebook credentials. 

Likewise, Firebase also offers database services, which can be used to store and collect data. The [Firebase Realtime Database](https://firebase.google.com/products/realtime-database/) and [Cloud Firestore](https://firebase.google.com/products/firestore/) can both be used for this purpose. For my thesis I made use of Firestore as it was a better fit for my project. Both have different pro and cons, so it is wise to compare both services before you pick one to use. 

Firestore is a [Google Cloud product](https://cloud.google.com/firestore/), but listed at Firebase as it provides resources to make direct access to data in your app possible. This means that you do not need to implement any interface to communicate between Google Cloud and your app. Interfacing is done by Firebase and you just need to connect your application to Firestore for it to work.

To conclude, Firebase can be seen as a toolkit that can be utilized to implement the needed functionalities for your application. You do not need to write these functionalities from scratch and as Firestore is a Google Cloud product you can store a lot of user data on the Cloud with ease. Next I will go into more details on how I used Firebase for my thesis. 

## How did I use Firebase for my Thesis?

**Important Note: This guide is on how to use Firebase with your website, so not a mobile app.**

As part of my thesis, I needed to build a website on which users could provide me their assessments. The subjects were tasked to assess the relevancy of specific query-passage pairs, pairs which you are likely to see when you enter a query on Google or Bing and a list of document snippets are returned. The question to the users was: How relevant (on a level from 1 (totally irrelevant) to 5 (perfectly relevant)) do you grade the passage (snippet) to the query? 

This exact wording of the imagined website gave me the impression that I would need some sort of database in which I store user credentials, query passage pairs and the user input. I had build websites before, but never this complicated. Mostly by just making use of HTML and CSS. Maybe some PHP or Javascript. But I had never build an entire database. let alone combine it with a website and make it work.

I did know about Firebase thanks to a collegue from a previous job. The institue for which I worked had a stand at a conference and we had to build a online quiz with leaderboard. Visitors to our stand would then be able to participate in this quiz and afterwards compare their results to other visitors of the conference. I remembered that he made use of Firebase to incorporate authentication and a realtime leaderboard. 

This online quiz was very similar to my online survey and this is why I considered Firebase in the first place. After reading the short introduction on their own [webpage](https://firebase.google.com/) and following some instruction videos on their [Youtube channel](https://www.youtube.com/user/Firebase). I noticed that it would not be that difficult to get started and it would definitely mean that I would not have to write my own authentication and database functionality. 

In the following subsections I will go into more details on how I actually implemented several Firebase tools. Code is provided as well.

### Configuration

So you have started with your website and now you want to incorporate Firebase into your project. Before you start using Firebase you will need to create a Firebase project on [firebase.google.com](https://firebase.google.com/). Best way to get started is by following their own [guide](https://firebase.google.com/docs/web/setup?hl=nl). Steps 1 and 2 will help you start a project on Firebase and register your app with it. Then step 3 helps you add the necessary code to your project to connect your website to your project at Firebase. I did not use Firebase Hosting for my website and did not use any tools like a bundler. So I followed the instructions under *From the CDN* at step 3 to initialize Firebase with my website. 

On the specific webpage I want to make use of Firebase I added the following lines to make use of the Firebase toolkit:

```html
<BODY>
	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js"></script>
	
	<!-- TODO: Add SDKs for Firebase products that you want to use
	https://firebase.google.com/docs/web/setup#available-libraries -->
		
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-database.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-firestore.js"></script>
	
	<!-- REST OF CODE BELOW -->
</BODY>
```

I added links to **firebase-auth.js**, **firebase-database.js**, and **firebase-firestore.js** as those are the firebase products I needed for my project. On [https://firebase.google.com/docs/web/setup#available-libraries](https://firebase.google.com/docs/web/setup#available-libraries) you can find the other available libraries and how to add them to your project.

Next you will need to initialize Firebase in you project:

```javascript
// Your web app's Firebase configuration
var firebaseConfig = {
  apiKey: "api-key",
  authDomain: "project-id.firebaseapp.com",
  databaseURL: "https://project-id.firebaseio.com",
  projectId: "project-id",
  storageBucket: "project-id.appspot.com",
  messagingSenderId: "sender-id",
  appId: "app-id",
  measurementId: "G-measurement-id",
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
```

You will need to enter your Firebase configuration settings, more information can be found on [https://firebase.google.com/docs/web/setup?hl=nl#config-object](https://firebase.google.com/docs/web/setup?hl=nl#config-object).

For readability, I included this code in a seperate javascript file named **firebase_configuration.js** and added a link to it on the webpage I want to make use of Firebase like this:

```html
<BODY>
	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js"></script>
	
	<!-- TODO: Add SDKs for Firebase products that you want to use
	https://firebase.google.com/docs/web/setup#available-libraries -->
		
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-database.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-firestore.js"></script>
	
	<!-- REST OF CODE -->
	
	<script src='js/firebase_config.js'></script>
</BODY>
```

Now you can start making use of the different Firebase products in your project.

### Authentication

The first Firebase product I incorporated in my project was authentication. I decided to make use of the [Email Link Authentication](https://firebase.google.com/docs/auth/web/email-link-auth?hl=nl) option offered by Firebase. It is a one time online survey and I did not want my users to go through the hassle of having to create an account with username and password, but at the same time I wanted to add some sort of check to prevent users participate more than once. With Email Link Authentication, users provide their email and a specific one-time verification link is send to their email. When they click on that link, they will be forwarded to your website and also be authenticated with Firebase. This means that their email will be stored on Firebase and a special user ID will be allocated to them. This ID can then be used as a special marker across your project to identify the specific user. There are other authentication options, like making use of Facebook or Google. But this expects that users have an account with those services. I was not sure if my users would have those types of accounts or if they were willing to sign-up using those accounts. For my project, the Email Link option sufficed.

#### Email Link Authentication

To include Email Link Authentication you will need to ask your users for their email. Below is a simple example of how to add this to your website. You have an input element and a button on which the user can press to submit their email and it is all part of a form with the action set to pass the submitted email to a javascript function that will make use of Firebase.

![simple user input layout](https://github.com/Tomjg14/Master_Thesis_MSMARCO_Passage_Reranking_BERT/blob/master/instructions/firebase/simple_email_input.PNG "simple user input example")

```html
<form action="javascript:emailLinkLogin(document.getElementById('email').value)">
	<label for="email"><b>Email</b></label>
	<input type="text" placeholder="Enter Email" id="email" required>
	<div id="btn">
		<button class="btn btn-lg btn-success fp-buttons custom-button" type="submit" id="register">Register</button>
	</div>
</form>
```

I put the function in another javascript file named **emailLinkLogin.js**.

```html
<BODY>
	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js"></script>

	<!-- TODO: Add SDKs for Firebase products that you want to use
	https://firebase.google.com/docs/web/setup#available-libraries -->
		
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-database.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-firestore.js"></script>
 
	<!-- OTHER HTML CODE HERE -->
	
	<form action="javascript:emailLinkLogin(document.getElementById('email').value)">
		<label for="email"><b>Email</b></label>
		<input type="text" placeholder="Enter Email" id="email" required>
		<div id="btn">
			<button class="btn btn-lg btn-success fp-buttons custom-button" type="submit" id="register">Register</button>
		</div>
	</form>
	
	<!-- OTHER HTML CODE HERE -->
		
	<script src='js/firebase_config.js'></script>
	<script src='js/emailLinkLogin.js'></script>
	
	<!-- OTHER IMPORTANT SCRIPTS HERE -->

</BODY>
```

The javascript file looks like this:

```javascript
function emailLinkLogin(email) {
	
	var actionCodeSettings = {
		url: 'http://website.to.which.you.want.to.redirect.user.with.the.link.com',
		handleCodeInApp: true
	}
	
	firebase.auth().fetchSignInMethodsForEmail(email)
	.then(function(signInMethods) {
		if (signInMethods.indexOf(firebase.auth.EmailAuthProvider.EMAIL_LINK_SIGN_IN_METHOD) != -1) {
			// Email has already been used.

		} else {
			firebase.auth().setPersistence(firebase.auth.Auth.Persistence.LOCAL)
			.then(function() {
				firebase.auth().sendSignInLinkToEmail(email, actionCodeSettings)
				.then(function() {
					window.localStorage.setItem('emailForSignIn', email);
					// Link has been send.

				})
				.catch(function(error) {
					// Handle Errors here.
				});
			})
			.catch(function(error) {
				// Handle Errors here.
			});
		}
	})
	.catch(function(error) {
		// SAY SOMETHING LIKE: That e-mail address is invalid. Please enter a valid address.
	});
	
}
```

The first thing that you need to construct is the `ActionCodeSettings` object, which provides Firebase with instructions on how to construct the email link send to users. The following fields are set:
  * `url`: The deep link to embed and any additional state to be passed along. The link's domain has to be whitelisted in the Firebase Console list of authorized domains, which can be found by going to the Sign-in method tab under your project (Authentication -> Sign-in method).
  * `handleCodeInApp`: Set to true. The sign-in operation has to always be completed in the app unlike other out of band email actions (password reset and email verifications). This is because, at the end of the flow, the user is expected to be signed in and their Auth state persisted within the app.

More information on ActionCodeSettings can be found at the [Passing State in Email Actions](https://firebase.google.com/docs/auth/web/passing-state-in-email-actions?hl=nl#passing_statecontinue_url_in_email_actions) section.

Next, for my website I wanted to check if the user already register with the submitted email. This is why I added `firebase.auth().fetchSignInMethodsForEmail(email)` which returns an array with the possible methods for authentication for the current user. If the user did not register before with this email, this array should be empty. This line in the code checks if this array is indeed empty:

```javascript
if (signInMethods.indexOf(firebase.auth.EmailAuthProvider.EMAIL_LINK_SIGN_IN_METHOD) != -1){
}
``` 

If the user did not register before, we move on to set the [Authentication State Persistence](https://firebase.google.com/docs/auth/web/auth-state-persistence). You could either specify whether a signed in user should be indefinitely persisted until explicit sign out, cleared when the window is closed or cleared on page reload.

I decided to set the state persistence to `LOCAL`, which indicates that the state will be persisted even when the browser window is closed or the activity is destroyed in React Native. An explicit sign out is needed to clear the state. The other options are: `SESSION` (only persist in current session or tab until closed) and `NONE` (state stored in memory and cleared when window/activity is refreshed). 

Next you want to send the authentication/verification link to the user's email, and save the user's email in case the user completes the email sign-in on the same device. This all happens in this part of the code:

```
firebase.auth().sendSignInLinkToEmail(email, actionCodeSettings)
  .then(function() {
    // The link was successfully sent. Inform the user.
    // Save the email locally so you don't need to ask the user for it again
    // if they open the link on the same device.
    window.localStorage.setItem('emailForSignIn', email);
  })
  .catch(function(error) {
    // Some error occurred, you can inspect the code: error.code
  });
```

#### Complete Sign-in Process

It seems as if we are finished, but we are only halfway there. The user will now have submitted their email. The email is checked, persistence is set and the authentication link is send to the submitted email. The user will then check their inbox for the link and click on it. By clicking on the link, they will be redirected to the url you set in the `ActionCodeSettings`. This webpage that you set, will need to complete the sign-in process.

The page to which I redirect the users look somewhat like this:

```html
<BODY>
	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js"></script>

	<!-- TODO: Add SDKs for Firebase products that you want to use
	https://firebase.google.com/docs/web/setup#available-libraries -->
		
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-database.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-firestore.js"></script>
	
	<!-- HTML CODE HERE --> 
		
	<script src='js/firebase_config.js'></script>
	<script src='js/auth_firebase.js'></script>
	<script src='js/firestore_instructions.js'></script>
	<script src='js/url_parameters.js'></script>
	<script src='js/firebase_check_apikey.js'></script>

	<!-- OTHER SCRIPTS -->

</BODY>
```

Again I have added the links to the necessary Firebase products in the top. In the bottom I have added some custom javascript files of my own. The **auth_firebase.js** includes the code to complete the sign-in process. It looks like this:

```javascript
if (firebase.auth().isSignInWithEmailLink(window.location.href)) {
	var email = window.localStorage.getItem('emailForSignIn');
	if (!email) {
		email = window.prompt('Please provide your email for confirmation');
	}
	
	firebase.auth().signInWithEmailLink(email, window.location.href)
	.then(function(result) {
		window.localStorage.removeItem('emailForSignIn');
	})
	.catch(function(error) {
		// Something went wrong
	});
}
```

The function `isSignInWithEmailLink(window.location.href)` will check if the current url does indeed contain the correct parameters provided when redirected via the authentication link. If the user sign-in on the same device, the submitted email is stored in the local storage so we retrieve that to avoid asking for their email again. Otherwise, the user will be asked to submit their email again to be able to sign-in. If indeed the user is redirected via the authentication link and the email is present, the sign-in process is started with the function `signInWithEmailLink(email, window.location.href)`. If nothing goes wrong the user will be authenticated and we can remove their email from local storage. The user's email will now be stored in Firebase with a unique user ID. 

#### Sign-out User

Depending on the state persistence you set, you might want to include the option for users to sign-out. Luckily this is not that complicated to include. Let's say you have added a **sign-out** button, then you want to add the following code to your project: 

```javascript
quitBtn.addEventListener("click", e => {
	e.preventDefault();
	
	quitBool = true;
	
	firebase.auth().signOut().then(function() {
		console.log("logging out");
	});
	
});
```

The line `firebase.auth().signOut()` will sign out the user. 

#### Re-Authenticate User

If you now want to reauthenticate via `Email Link` again, similar code as before suffices:

```javascript
function emailLinkLogin(email) {
	
	var actionCodeSettings = {
		url: 'http://website.to.redirect.to.com',
		handleCodeInApp: true
	}
	
	firebase.auth().fetchSignInMethodsForEmail(email)
	.then(function(signInMethods) {
		if (signInMethods.indexOf(firebase.auth.EmailAuthProvider.EMAIL_LINK_SIGN_IN_METHOD) != -1) {
			firebase.auth().sendSignInLinkToEmail(email, actionCodeSettings)
			.then(function () {
				window.localStorage.setItem('emailForSignIn', email);
			})
			.catch(function(error) {
				// Some error occurred.
			});
		} else {
			// Email was not verified before.
		}
	})
	.catch(function(error) {
		// That e-mail address is invalid.
	});
	
}
```

Again, you check if the user submitted the email before, by retrieving the signInMethods for the provided email. However, this time, if the returned array is not empty, you continue with sending a new authentication link to that email. You specify the `ActionCodeSettings` and save the submitted email in the local storage. 

#### Get the currently signed-in user

Above instructions make clear how to authenticate an user with `Email Link Authentication`, how to complete this authentication process when the link has been clicked, how to sign-out any authenticated user and then how to reauthenticate an user with the same authentication method. What is still missing is how to check if an user is authenticated to prevent any unauthorized users from entering specific webpages.

This can be done by setting an observer on the Auth object itself:

```javascript
firebase.auth().onAuthStateChanged(function(user) {
  if (user) {
    // User is signed in.
  } else {
    // No user is signed in.
  }
});
```

By adding this script to your webpage, firebase will check if an user is currently signed in. If no user is signed in, you could redirect the current visitor away from the current webpage. This means that visitors should be authenticated to visit that specific webpage. More information can be found [here](https://firebase.google.com/docs/auth/web/manage-users?hl=nl).

### Firestore

Aside from the Authentication tools offered by Firebase, I also made use of [Firestore](https://firebase.google.com/docs/firestore):

*"Cloud Firestore is a flexible, scalable database for mobile, web, and server development from Firebase and Google Cloud Platform. Like Firebase Realtime Database, it keeps your data in sync across client apps through realtime listeners and offers offline support for mobile and web so you can build responsive apps that work regardless of network latency or internet connectivity. Cloud Firestore also offers seamless integration with other Firebase and Google Cloud Platform products, including Cloud Functions."*

There are 4 steps to using Firestore:
1. **Integrate the Cloud Firestore SDKs** Quickly include clients via Gradle, CocoaPods, or a script include.
2. **Secure your data** Use Cloud Firestore Security Rules or Identity and Access Management (IAM) to secure your data for mobile/web and server development, respectively.
3. **Add Data** Create documents and collections in your database.
4. **Get Data** Create queries or use realtime listeners to retrieve data from the database.

The following subsections will go into more detail on how I used Firestore in my project. Code is included.

#### Integrate Firestore

Before you integrate Firestore into your project, you will need to create a database within the Firebase console. Navigate to the **Database** section of the Firebase console and follow the database creation workflow. When you create a database you must select the starting mode for your Cloud Firestore Security Rules. These rules decide who is allowed to read and write data to the database. Find more information at the [Secure your data](https://firebase.google.com/docs/firestore/quickstart#secure_your_data) section. To get started, select test mode. This mode allows anyone to read and overwrite your data, so you should change this when you are finished with your project.

After selecting a starting mode, you are asked to select a location for your database. This is your project's *default Google Cloud Platform (GCP) resource location*. This location is where your data is stored for GCP services that require a location setting, like Cloud Firestore. Therefore it is wise to read the [documentation](https://firebase.google.com/docs/firestore/locations#default-cloud-location) on this topic. If you are not able to select a location, then your project already has a default GCP resource location. After selecting a location you are done creating a database.

Next you will need to set up your development environment. I already explained how to initialize Firebase within your web project. These instructions can also be found [here](https://firebase.google.com/docs/web/setup). It is important that you include the following libraries in your project for Firestore to be integrated:

```html
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-firestore.js"></script>
```

Again, you will need to initialize Firebase within your code:

```javascript
// Initialize Cloud Firestore through Firebase
firebase.initializeApp({
  apiKey: '### FIREBASE API KEY ###',
  authDomain: '### FIREBASE AUTH DOMAIN ###',
  projectId: '### CLOUD FIRESTORE PROJECT ID ###'
});

var db = firebase.firestore();
```

By then calling `var db = firebase.firestore()` you can set a variable to an instance of your database which you can use to store and collect data within your project.

#### Secure Data

Make sure to set your security rules for your database to determine who is authorized to read and write data from your database. You can start with the test mode, but it is wise to eventually change these to your own set of rules. It could be wise to only let authorized users be allowed to read data.
More information can be found [here](https://firebase.google.com/docs/firestore/quickstart#secure_your_data). 

Example: 

```
// Allow read/write access on all documents to any user signed in to the application
service cloud.firestore {
  match /databases/{database}/documents {
    match /{document=**} {
      allow read, write: if request.auth.uid != null;
    }
  }
}
```

#### Add Data

Data is stored in so called `Documents`, which are stored in `Collections`. Cloud Firestore creates collections and documents implicitly the first time you add data to the document. You do not need to explicitly create collections and documents. But you could do so manually within the Firebase console and then add data to these documents.

Either way, you add data the following way:

```javascript
var db = firebase.firestore();

db.collection("COLLECTION_NAME").add({
	key: "value",
	other_key: "other_value",
	another_key: 2
})
.then(function(docRef) {
	console.log("Document written with ID: ", docRef.id);
})
.catch(function(error) {
	console.error("Error adding document: ", error);
});
```

This way, if you have not manually created the collection, you create a new collection and add a document to it. You could now add another document to this collection, but this time with different key-value pairs than the first document. Documents in a collection can contain different sets of information.

For my thesis it was important to store data per user, so I made use of the user ID to store data in the database. To be able to do this, I used `firebase.auth()` to retrieve the current user's ID and use that ID as a document ID:

```javascript
firebase.auth().onAuthStateChanged(function(user) {
	if (user) {
		db.collection("users").doc(user.uid).set({
		}).then(function() {
			// Do something after data has been added
		});
	}
})
```

Instead of using `add` I made use of the `set` function, which achieves the same in this case.

#### Get Data

There are two ways to retreive data stored in Cloud Firestore. Either of these methods can be used with documents, collections of documents or the results of queries:
  * Call a method to get the data.
  * Set a listener to receive data-change events.

Here I will only explain how to use a method to get your data. More information on how to get data can be found [here](https://firebase.google.com/docs/firestore/query-data/get-data).

To retrieve the contents of a single document you can use `get()`:

```javascript
var docRef = db.collection("COLLECTION_NAME").doc("DOCUMENT_ID");

docRef.get().then(function(doc) {
    if (doc.exists) {
        console.log("Document data:", doc.data());
    } else {
        // doc.data() will be undefined in this case
        console.log("No such document!");
    }
}).catch(function(error) {
    console.log("Error getting document:", error);
});
```

You can also retrieve all documents from the collection: 

```javascript
db.collection("COLLECTION_NAME").get().then((querySnapshot) => {
    querySnapshot.forEach((doc) => {
        console.log(`${doc.id} => ${doc.data()}`);
    });
});
```

Or retrieve a subset of the documents with one request by querying documents in a collection. For example, by making use of `where()` to query for all of the documents that meet a certain condition, then use `get()` to retrieve the results:

```javascript
db.collection("cities").where("capital", "==", true)
    .get()
    .then(function(querySnapshot) {
        querySnapshot.forEach(function(doc) {
            // doc.data() is never undefined for query doc snapshots
            console.log(doc.id, " => ", doc.data());
        });
    })
    .catch(function(error) {
        console.log("Error getting documents: ", error);
    });
```

In the above example you query for all cities that are a capital. You can find more information on how to perform queries [here](https://firebase.google.com/docs/firestore/query-data/queries#collection-group-query).

For my thesis I primarily made use of Firestore to store user input and did not really need to retrieve data for my website to work. 

[Repository Homepage](https://tomjg14.github.io/Master_Thesis_MSMARCO_Passage_Reranking_BERT/)
