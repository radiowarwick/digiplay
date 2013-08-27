<?php
Output::set_template();

switch($_REQUEST["action"]) {
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
						<th class=\"length nowrap\">Length</th>
					</tr>
				</thead>";
			foreach($search["results"] as $track) {
				$track = Tracks::get($track);
				$return .= "<tr id=\"".$track->get_id()."\">
					<td class=\"icon\">".Bootstrap::glyphicon("music")."</td>
					<td class=\"artist\">".$track->get_artists_str()."</td>
					<td class=\"title\">".$track->get_title()."</td>
					<td class=\"album\">".$track->get_album()->get_name()."</td>
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