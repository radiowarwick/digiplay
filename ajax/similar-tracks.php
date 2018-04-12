<?php 

if(!Session::is_group_user("Importer")) {
	http_response_code(401);
	echo(json_encode(array("error" => "you are not a music importer")));
}
else {
	$search_str = preg_replace('/[^a-z0-9]+/i', ' ', trim(preg_replace('/\s*\([^)]*\)/', '', $_REQUEST["title"]." ".$_REQUEST["artist"])));
	$similar_tracks = Search::tracks($search_str);

	if($similar_tracks)
		echo(json_encode(array("response" => "fail", "q" => $search_str, "tracks" => $similar_tracks["results"])));
	else
		echo(json_encode(array("response" => "success")));
}

?>