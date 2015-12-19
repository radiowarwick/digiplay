<?php 

if(Session::is_user()){

	$query = $_REQUEST['q'];

	if($query) {
	$search = Search::tracks($query);
	$tracks = $search["results"];
	$jinglesearch = Search::jingles($query);
	$jingles = $jinglesearch["results"];
	// $advertsearch = Search::adverts($query);
	// $advert = $advertsearch['results'];
	$count = 1;
	}

	foreach ($jingles as $jingleid){
		$jingle = Jingles::get($jingleid);
		echo "<div style=\"background-color: rgb(18,137,192);\" id=\"track-draggable-".$count."\" class=\"ui-draggable dps-aw-item\" data-dps-aw-style=\"118\" data-dps-audio-id=\"".$jingle->get_id()."\">
		<span class=\"text\">".$jingle->get_title()."</span>
		</div><br>";
	}

	// foreach ($adverts as $advertid){
	// 	$advert = Advertss::get($advertid);
	// 	echo "<div style=\"background-color: orange;\" id=\"track-draggable-".$count."\" class=\"ui-draggable dps-aw-item\" data-dps-aw-style=\"118\" data-dps-audio-id=\"".$advert->get_id()."\">
	// 	<span class=\"text\">".$advert->get_title()."</span>
	// 	</div><br>";
	// }

	foreach($tracks as $track_id) {
		$track = Tracks::get($track_id);
		echo "<div style=\"background-color: rgb(18,137,192);\" id=\"track-draggable-".$count."\" class=\"ui-draggable dps-aw-item\" data-dps-aw-style=\"118\" data-dps-audio-id=\"".$track->get_id()."\">
		<span class=\"text\">".$track->get_title()."</span>
		</div><br>";
		$count = $count + 1;
	}

}

?>