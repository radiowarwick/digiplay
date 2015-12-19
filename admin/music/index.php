<?php

Output::set_title("Music Uploads");

$index = (isset($_REQUEST["i"])? explode(",",$_REQUEST["i"]) : array("title","artist","album"));
$limit = (isset($_GET["n"]) && is_numeric($_REQUEST["n"])? $_GET["n"] : 10);
$page = (isset($_REQUEST["p"]) && is_numeric($_REQUEST["p"])? $_REQUEST["p"] : 1);

MainTemplate::set_subtitle("See who has been uploading tracks");

$uploadStats = DigiplayDB::select("origin, count(origin) FROM audio GROUP BY origin ORDER BY count(origin) DESC", NULL, false);

	echo("
		<table class=\"table table-striped table-bordered\">
		<thead>
		<tr>
		<th></th>
		<th>Member</th>
		<th>Songs Uploaded</th>
		</tr>
		</thead>
		<tbody>
		");

$i = 0;

foreach ($uploadStats as $uploadStat) {

	$uploader = $uploadStat['origin'];
	$count = $uploadStat['count'];
	
	// Filter out old entries
	//if (preg_match('/^Web -/', $uploader)) continue;
	//if (preg_match('/^Dropbox -/', $uploader)) continue;
	//if (preg_match('/^Mp3/', $uploader)) continue;
	//if ($uploader == "") continue;

	// Print row
	$i++;
	echo("
		<tr>
			<td>".$i."</td>
			<td>".$uploader."</td>
			<td>".$count."</td>
		</tr>
	");
}

echo("</tbody>
		</table>
	");

?>
