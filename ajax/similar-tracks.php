<?php require_once("pre.php");

$search_str = preg_replace('/[^a-z0-9]+/i', ' ', trim(preg_replace('/\s*\([^)]*\)/', '', $_REQUEST["title"]." ".$_REQUEST["artist"])));
$similar_tracks = Search::tracks($search_str);

if($similar_tracks) echo json_encode(array("q" => $search_str, "tracks" => $similar_tracks["results"]));
?>