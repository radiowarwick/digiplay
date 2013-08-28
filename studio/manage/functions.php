<?php
Output::set_template();

switch($_REQUEST["action"]) {
	case "now-next":
		$json = file_get_contents(Config::get_param("now-next-api"));
		$json = json_decode($json);
		$return = "<div class=\"col-sm-6 navbar-brand\">On now: <span id=\"on-now\">".$json[0]->name."</span></div>
			<div class=\"col-sm-6 navbar-brand\">On next: <span id=\"on-next\">".$json[1]->name."</span></div>";
		echo $return;
		break;
	case "info-content":
		echo(Config::get_param("info-content"));
		break;
	case "search":
		$query = $_REQUEST['q'];
		$index = (isset($_REQUEST['i'])? $_REQUEST["i"] : "title artist album");

		if($query) $search = Search::tracks($query,$index,50);

		if($search["results"]) {
			$return = "<table class=\"table table-striped\" cellspacing=\"0\">
				<thead>
					<tr>
						<th class=\"icon\"></th>
						<th class=\"artist\">Artist</th>
						<th class=\"title\">Title</th>
						<th class=\"album\">Album</th>
						<th class=\"length\">Length</th>
					</tr>
				</thead>";
			foreach($search["results"] as $track) {
				$track = Tracks::get($track);
				$return .= "<tr id=\"".$track->get_id()."\">
					<td class=\"icon\">".Bootstrap::glyphicon("music")."</td>
					<td class=\"artist nowrap\">".$track->get_artists_str()."</td>
					<td class=\"title nowrap\">".$track->get_title()."</td>
					<td class=\"album nowrap\">".$track->get_album()->get_name()."</td>
					<td class=\"length nowrap\">".Time::format_succinct($track->get_length())."</td>
				</tr>";
			}
			$return .= "</table>";
			if($search["total"] > 50) $return .= "<span class=\"result-limit\">Only showing top 50 results out of ".$search["total"]." total.  Try a more specific search.</span>";
			echo($return);
		} else {
			echo("<h3>No results found, or your search term was too generic.  <br />Try a different search query.</h3>");
		}
		break;
}
?>