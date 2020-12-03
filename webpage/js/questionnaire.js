function readTextFile(file, callback) {
	var rawFile = new XMLHttpRequest();
	rawFile.overrideMimeType("application/json");
	rawFile.open("GET", file, true);
	rawFile.onreadystatechange = function() {
		if (rawFile.readyState === 4 && rawFile.status == "200") {
						callback(rawFile.responseText);
		}
	}
	rawFile.send(null);
}

function boldString(str, find) {
	var str2find = find.toLowerCase();
    var re = new RegExp('\\b' + str2find + '\\b', 'g');
    var result =  str.replace(re, '<b>'+str2find+'</b>');
    re = new RegExp('\\b' + str2find.toUpperCase() + '\\b', 'g')
    result =  result.replace(re, '<b>'+str2find.toUpperCase()+'</b>');
	re = new RegExp('\\b' + str2find.split("")[0].toUpperCase() + str2find.split("").slice(1).join("") + '\\b', 'g')
    result = result.replace(re, '<b>'+(str2find.split("")[0].toUpperCase() + str2find.split("").slice(1).join(""))+'</b>');
    return result;
}

function showQuery(file_path) {
	let header = document.getElementById('header');
	header.innerHTML = '';
	readTextFile(file_path, function(text) {
		var data = JSON.parse(text);
		var query_string = data[0]['query_text'];
		var headerH1 = document.createElement("h1");
		var queryText = document.createTextNode(query_string);
		headerH1.appendChild(queryText);
		headerH1.class = 'carousel-title';
		header.appendChild(headerH1);
	});
}

function makeButton(type,name,id) {
	var inputButton = document.createElement("input");
	inputButton.type = type;
	inputButton.name = name;
	inputButton.id = id;
	inputButton.style.margin = "0 10px 0 10px"
	return inputButton
}

function showPassages (file_path) {
	var passage_ids = [];
	var viewParameterValue = getUrlVars()["view"];
	let table = document.getElementById("query_table").getElementsByTagName('tbody')[0];
	table.innerHTML = '';
	readTextFile(file_path, function(text) {
		var data = JSON.parse(text);
		var query_string = data[0]['query_text'];
		for(var i = 0; i < data.length; i++) {
			var rij = table.insertRow(-1);
			
			var buttonDiv = document.createElement("div")
			buttonDiv.className = "relevance_buttons"
			
			var label1 = document.createElement("label");
			label1.innerText = "1";
			let inputButton1 = makeButton("radio",data[i]['passage_id'],"1");
			label1.appendChild(inputButton1);
			
			var label2 = document.createElement("label");
			label2.innerText = "2";
			let inputButton2 = makeButton("radio",data[i]['passage_id'],"2");
			label2.appendChild(inputButton2)
			
			var label3 = document.createElement("label");
			label3.innerText = "3";
			let inputButton3 = makeButton("radio",data[i]['passage_id'],"3");
			label3.appendChild(inputButton3);
			
			var label4 = document.createElement("label");
			label4.innerText = "4";
			let inputButton4 = makeButton("radio",data[i]['passage_id'],"4");
			label4.appendChild(inputButton4);
			
			var label5 = document.createElement("label");
			label5.innerText = "5";
			let inputButton5 = makeButton("radio",data[i]['passage_id'],"5");
			label5.appendChild(inputButton5);
			
			let question = document.createElement("h5");
			question.innerText = "On a scale from 1 to 5, how relevant is this passage?";
			buttonDiv.appendChild(question);
			buttonDiv.appendChild(label1)
			buttonDiv.appendChild(label2)
			buttonDiv.appendChild(label3)
			buttonDiv.appendChild(label4)
			buttonDiv.appendChild(label5)
			let scaleDiv = document.createElement("div");
			scaleDiv.className = "scale"
			let badLabel = document.createElement("label");
			badLabel.innerText = "not relevant";
			badLabel.className = "bad_label";
			badLabel.style.marginRight = "60px";
			let perfectLabel = document.createElement("label");
			perfectLabel.innerText = "perfectly relevant";
			perfectLabel.className = "perfect_label";
			perfectLabel.style.marginLeft = "60px";
			scaleDiv.appendChild(badLabel);
			scaleDiv.appendChild(perfectLabel);
			buttonDiv.appendChild(scaleDiv);
			
			var numberingParagraph = document.createElement("p");
			numberingParagraph.innerHTML = i+1;
			
			var queryParagraph = document.createElement("p");
			queryParagraph.id = data[i]['query_id'];
			queryParagraph.innerHTML = "Query: " + query_string;
			
			queryTerms = query_string.split(" ");
			
			passage_text = data[i]['passage_text']
			
			//for (let term of queryTerms) {
				//passage_text = boldString(passage_text, term);
			//}
			
			var passageParagraph = document.createElement("p");
			passageParagraph.id = data[i]['passage_id'];
			passage_ids.push(data[i]['passage_id']);
			passageParagraph.innerHTML = "Passage: " + passage_text;
			
			
			var relevanceLabel = data[i]['relevance_label'];
			if (viewParameterValue) {
				if (relevanceLabel == 1.0) {
					if (viewParameterValue == "true") {
						passageParagraph.style.color = 'green';
					}
					passageParagraph.className = 'relevant'
				} else {
					if (viewParameterValue == "true") {
						passageParagraph.style.color = 'red';
					}
					passageParagraph.className = 'irrelevant'
				}
			} else {
				if (relevanceLabel == 1.0) {
					passageParagraph.className = 'relevant';
				} else {
					passageParagraph.className = 'irrelevant';
				}
			}
			
			var cel0 = rij.insertCell(0);
			cel0.style.verticalAlign = "top";
			cel0.align = "left";
			cel0.className = "numbering";
			var cel1 = rij.insertCell(1);
			cel1.cellspacing = "5%";
			cel1.style.verticalAlign = "top";
			cel1.align = "left";
			var cel2 = rij.insertCell(2);
			cel2.id = 'input_button';
			cel2.style.verticalAlign = "top";
			
			//cel1.innerHTML = data[i]['passage_text'];
			cel0.appendChild(numberingParagraph);
			cel1.appendChild(queryParagraph);
			cel1.appendChild(passageParagraph);
			cel2.appendChild(buttonDiv);

		}
		localStorage.setItem("passage_ids", JSON.stringify(passage_ids));
	});
}