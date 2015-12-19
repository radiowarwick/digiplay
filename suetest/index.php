<?php
Output::set_title("Sue");
MainTemplate::set_subtitle("Song Countdown for the Masses!");

$trash = Files::get_by_id(3, "dir");

$latestsue = DigiplayDB::select("log.track_title, log.track_artist, log.audioid, log.datetime, audio.length_smpl FROM log INNER JOIN audio ON log.audioid = audio.id WHERE log.location = 0 ORDER BY log.datetime DESC LIMIT 5");
//var_dump($latestsue);
$timeremaining = ($latestsue[0]['length_smpl'] / 44100) - (time() - $latestsue[0]['datetime']);
//echo time()." - ".$latestsue[0]['datetime']." = ".(time() - $latestsue[0]['datetime']);
//var_dump($timeremaining);

echo("
<div class=\"row\">
	<div class=\"col-sm-12\">
		<a href=\"".LINK_ABS."music\">
			<div class=\"panel panel-info\">
				<div class=\"panel-heading\">
					<div class=\"row dashboard-stamp\">
						<div class=\"col-xs-5\">
						</div>
						<div class=\"col-xs-7\">
							<h2>".$latestsue[0]['track_title']." - ".$latestsue[0]['track_artist'].":</h2><h2><span id=\"song-time-left\">".$timeremaining."</span> remaining</h2>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>");

echo("
<script>
	$( document ).ready(function() {
	    setInterval(function() {
	    	var timeremaining = $( '#song-time-left' ).html()
	    	timeremaining -= 0.01;
	    	console.log($( '#song-time-left' ).html());
	    	$( '#song-time-left' ).html(timeremaining.toFixed(2));
		}, 10);
	});
</script>
");
?>
