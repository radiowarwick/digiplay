<?php 

if(Session::is_user()){

	$query = $_REQUEST['q'];

	if($query) {
		// if(Session::is_group_user('Audiowalls Admin')) {
		$search = Search::tracks($query);
		$tracks = $search["results"];
		// }

		$jinglesearch = Search::jingles($query);
		$jingles = $jinglesearch["results"];
		$advertsearch = Search::adverts($query);
		$advert = $advertsearch['results'];

		$track_results = [];
	}

	foreach ($jingles as $jingleid){
		$jingle = Jingles::get($jingleid);

		$track = ["id" => $jingleid, "title" => $jingle->get_title(), "artist" => $jingle->get_artists_str(), "album" => $jingle->get_album()->get_name(), "length" => $jingle->get_length_formatted(), "length2" => $jingle->get_length()];
		$track_results[] = $track;
	}

	foreach ($adverts as $advertid){
		$advert = Advertss::get($advertid);

		$track = ["id" => $advertid, "title" => $advert->get_title(), "artist" => $advert->get_artists_str(), "album" => $advert->get_album()->get_name(), "length" => $advert->get_length_formatted(), "length2" => $advert->get_length()];
		$track_results[] = $track;
	}

	// if(Session::is_group_user('Audiowalls Admin'))
	// {
	foreach($tracks as $track_id) {
		$track = Tracks::get($track_id);

		$t = ["id" => $track_id, "title" => $track->get_title(), "artist" => $track->get_artists_str(), "album" => $track->get_album()->get_name(), "length" => $track->get_length_formatted(), "length2" => $track->get_length()];
		$track_results[] = $t;
	}
	// }

	echo json_encode($track_results);
}

?>