<?php
Output::set_title("Studio Playout");
Output::add_stylesheet(LINK_ABS."css/studio.css");
MainTemplate::set_body_class("playout");

Output::add_script(LINK_ABS."js/observer.js");
Output::add_script(LINK_ABS."js/wavesurfer.js");
Output::add_script(LINK_ABS."js/webaudio.js");
Output::add_script(LINK_ABS."js/drawer.js");
Output::add_script(LINK_ABS."js/drawer.svg.js");
Output::add_script(LINK_ABS."js/studio_player.js");

if(isset($_REQUEST["key"])) {
	$location = Locations::get_by_key($_REQUEST["key"]);
	$key = $_REQUEST["key"];
} else {
	if(isset($_REQUEST["location"])) {
		$location = Locations::get_by_id($_REQUEST["location"]);
		$key = $location->get_key();
	} else {
		exit("No location specified!");
	}
}

if(!isset($_REQUEST["mode"])) $mode = "mp3";
else $mode = $_REQUEST["mode"];

echo("
<script>
	var key = 'key=".$key."&';
	var timers = [];
	var connect_timeout;
	var websocket;
	var connection = false;

	function disableLoad() {
		$('.load').attr('disabled', 'disabled');
	}

	function enableLoad(url) {
		$('.load').removeAttr('disabled');	
		$('.load').attr('data-url', url);
	}

	$(function (){
		setInterval(function() {
			$.ajax({
				url: 'functions.php?'+key+'action=check-next',
				dataType: 'json'
			}).done(function(data) {
				if(data.response == 'true') {
					enableLoad('".LINK_ABS."audio/get/'+data.md5+'.".$mode."?'+key);
				} else {
					disableLoad();
				}
			})
		}, 1000);
	});
</script>
<div class=\"wrap\">
	<div class=\"container\">
		<div class=\"row\">
			<div class=\"col-md-7\" id=\"players\">");
for($p = 1; $p <= 3; $p++) {
	echo("
				<div class=\"player\">
					<div class=\"panel panel-default\">
						<script> 
							$(function () { 
								player".$p." = wv_create('player".$p."');

								$('#player".$p." .load').on('click', function(e) { 
									disableLoad();
									player".$p.".load($(this).attr('data-url'));
									$.ajax({
										url: 'functions.php?'+key+'action=load-player',
										dataType: 'json'
									}).done(function(data) {
										console.log(data);
										$('#player".$p."').find('.title').html(data.title);
										$('#player".$p."').find('.artist').html(data.artist);
									});
								});

								$('.timemode button').on('click', function() {
									if($(this).html() == 'ELAPSED') {
										$(this).html('REMAIN');
										$(this).parents('.transport').find('.elapsed').hide();
										$(this).parents('.transport').find('.remain').show();
									} else {
										$(this).html('ELAPSED');
										$(this).parents('.transport').find('.remain').hide();
										$(this).parents('.transport').find('.elapsed').show();
									}
								})
							}); 
						</script>
						<div class=\"panel-heading\">
							<span>Player ".$p."</span>
						</div>
						<div class=\"panel-body\">
							<div class=\"row audio-player\" id=\"player".$p."\">
								<div class=\"col-xs-12\">
									<div class=\"row meta\">
										<div class=\"col-xs-9\">
											<span class=\"title\">Title</span><br />
											<span class=\"artist\">Artist</span>
										</div>
										<div class=\"col-xs-3\">
											<button class=\"btn btn-info btn-block load\" disabled>Load</button>
											<button class=\"btn btn-warning btn-block load\" disabled>Log</button>
										</div>
									</div>
									<div id=\"waveformplayer".$p."\" class=\"waveform\">
										<div id=\"progress-div\" class=\"progress progress-striped\">
											<div class=\"progress-bar\">
											</div>
										</div>
									</div>
									<div class=\"transport\">
										<div class=\"row\">
											<div class=\"col-xs-4\">
												<button class=\"btn btn-danger stop\" disabled>
													".Bootstrap::glyphicon("stop")."
												</button>
												<button class=\"btn btn-success playpause\" disabled>
													".Bootstrap::glyphicon("play")."
												</button>
											</div>
											<div class=\"col-xs-5\">
												<span class=\"elapsed time\" style=\"display: none\">00:00.00</span>
												<span class=\"remain time\">00:00.00</span>
											</div>
											<div class=\"col-xs-3 timemode\">
												<span>Time mode:</span><br />
												<button class=\"btn btn-primary btn-sm btn-block\">REMAIN</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>");
}

echo("
				<div class=\"panel panel-default sustainer\">
					<h2>Sue stuff here</h2>
				</div>
			</div>
			<div class=\"col-md-5\">
				<h1>lol audiowalls</h1>
			</div>
		</div>
	</div>
</div>");

?>