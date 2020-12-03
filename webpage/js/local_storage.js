function updateArray(query_id) {
	var unassessed_queries = JSON.parse(localStorage.getItem("unassessed_query_ids"));
	unassessed_queries.splice( unassessed_queries.indexOf(query_id), 1 );
	localStorage.setItem("unassessed_query_ids",JSON.stringify(unassessed_queries));
	var progress = JSON.parse(localStorage.getItem("progress"));
	progress = progress + 1;
	localStorage.setItem("progress",JSON.stringify(progress));
}