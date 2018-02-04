<?php 

if(Session::is_user()){

	$query = $_REQUEST['q'];

	if($query) {
		if(Session::is_group_user('Audiowalls Admin')) {
			$search = Search::tracks($query);
			$tracks = $search["results"];
		}

		$jinglesearch = Search::jingles($query);
		$jingles = $jinglesearch["results"];
		$advertsearch = Search::adverts($query);
		$advert = $advertsearch['results'];

		$count = 1;
		$track_results = [];
	}

	foreach ($jingles as $jingleid){
		$jingle = Jingles::get($jingleid);
		// echo "<div style=\"background-color: rgb(18,137,192);\" id=\"track-draggable-".$count."\" class=\"ui-draggable dps-aw-item\" data-dps-aw-style=\"118\" data-dps-audio-id=\"".$jingle->get_id()."\">
		// <span class=\"text\">".$jingle->get_title()."</span>
		// </div><br>";

		$track = ["id" => $jingleid, "title" => $jingle->get_title(), "artist" => $jingle->get_artists_str(), "album" => $jingle->get_album()->get_name(), "length" => $jingle->get_length_formatted()];
		$track_results[] = $track;

		$count + 1;
	}

	foreach ($adverts as $advertid){
		$advert = Advertss::get($advertid);
		// echo "<div style=\"background-color: orange;\" id=\"track-draggable-".$count."\" class=\"ui-draggable dps-aw-item\" data-dps-aw-style=\"118\" data-dps-audio-id=\"".$advert->get_id()."\">
		// <span class=\"text\">".$advert->get_title()."</span>
		// </div><br>";

		$track = ["id" => $advertid, "title" => $advert->get_title(), "artist" => $advert->get_artists_str(), "album" => $advert->get_album()->get_name(), "length" => $advert->get_length_formatted()];
		$track_results[] = $track;

		$count + 1;
	}

	if(Session::is_group_user('Audiowalls Admin'))
	{
		foreach($tracks as $track_id) {
			$track = Tracks::get($track_id);
			// echo "<div style=\"background-color: rgb(18,137,192);\" id=\"track-draggable-".$count."\" class=\"ui-draggable dps-aw-item\" data-dps-aw-style=\"118\" data-dps-audio-id=\"".$track->get_id()."\">
			// <span class=\"text\">".$track->get_title()."</span>
			// </div><br>";

			$t = ["id" => $track_id, "title" => $track->get_title(), "artist" => $track->get_artists_str(), "album" => $track->get_album()->get_name(), "length" => $track->get_length_formatted()];
			$track_results[] = $t;

			$count + 1;
		}
	}

	echo json_encode($track_results);
}

?>