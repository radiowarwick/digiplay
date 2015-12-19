<?php

Output::add_stylesheet(LINK_ABS."css/audiowall.css");
Output::set_title("Audiowall");
MainTemplate::set_body_class("audiowall");

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

echo("
		<script>
			var key = 'key=".$key."&';
			var timers = [];
			var connect_timeout;
			var websocket;
			var connection = false;

			function startWebsocket() {
				console.log('Starting websocket...');
				websocket = new WebSocket('".Configs::get_system_param("websocket_uri")."');
				websocket.onopen = function(e) { onOpen(e) };
				websocket.onclose = function(e) { onClose(e) };
				websocket.onmessage = function(e) { onMessage(e) };
				websocket.onerror = function(e) { onError(e) };
			}

			function onOpen(e) {
				connection = true;
				console.log('Websocket connection established.');
				websocket.send(JSON.stringify({'ident':'".$key."'}));
				clearTimeout(connect_timeout);
				$.each(timers, function(i, v) { clearInterval(v); });
				timers = [];
			}

			function onClose(e) {
				connection = false;
				console.log('Websocket connection lost.');
				connect_timeout = setTimeout('setIntervals()', 5000);
				setTimeout('startWebsocket()',5000);
			}

			function onMessage(e) {
				data = JSON.parse(e.data);
				switch(data.channel) {
					case 't_audiowall':
						break;
				}
			}

			function onError(e) {
				console.log(e);
			}

			function setIntervals() {
				if(timers.length == 0) {
					console.log('Setting manual refresh intervals.');
					timers.push(setInterval('reloadAudiowall()', 30000));
				}
			}

			$(function() {
				connect_timeout = setTimeout('setIntervals()', 5000);
				startWebsocket();
				window.oncontextmenu = function(event) {
  					event.preventDefault();
    				event.stopPropagation();
    				return false;
				};

				$(document).keydown(function(e) {
					if((111 < e.which) && (e.which < 123)) e.preventDefault();
				});
			});

		</script>
");

if(isset($_GET["id"])) $set = AudiowallSets::get_by_id($_GET["id"]);
else $set = AudiowallSets::get_by_id($location->get_config("station_aw_set")->get_val());

if(isset($_GET["page"])) $wall = $set->get_walls()[$_GET["page"]-1];
else $wall = $set->get_walls()[0];

$items = $wall->get_items();

echo("<div class=\"row\">");
for($i = 0; $i < 12; $i++) {
	echo("
		<div class=\"col-xs-4\">");
	if(isset($items[$i])) {
		$item = $items[$i];
		$style = $item->get_style();
		$audio = $item->get_audio();
		echo("
			<div class=\"btn btn-block disabled\" data-item-id=\"".$item->get_id()."\" style=\"color: ".$style->get_foreground_rgb()."; background: ".$style->get_background_rgb()."; border-color: ".$style->get_accent_rgb()."\">
				<div class=\"buffer\"></div>
				<div class=\"progress\"></div>
				".$item->get_text()."<br />".$audio->get_length_formatted()."
				<script>
				$(function() {
					$('[data-item-id=".$item->get_id()."]').find('.disabled').show();
					item".$item->get_id()." = new Audio('".LINK_ABS."audio/get/".$audio->get_id().".wav');
					item".$item->get_id().".buffercheck = setInterval(function() { 
						if(item".$item->get_id().".buffered.length == 1) {
							buffered = (item".$item->get_id().".buffered.end(0) / item".$item->get_id().".duration).toFixed(3) * 100;
						 	$('[data-item-id=".$item->get_id()."]').find('.buffer').css('width', (100-buffered)+'%');

						 	if(buffered == 100) { clearInterval(item".$item->get_id().".buffercheck) }
						} 
					}, 50);

					$('[data-item-id=".$item->get_id()."]').bind('click', function() {
						if($('[data-item-id=".$item->get_id()."]').hasClass('disabled')) return false;
						if(item".$item->get_id().".paused == true || item".$item->get_id().".currentTime == 0) {
							item".$item->get_id().".play();
							item".$item->get_id().".progressupdate = setInterval(function() { 
								progress = (item".$item->get_id().".currentTime / item".$item->get_id().".duration).toFixed(3) * 100;
					 			$('[data-item-id=".$item->get_id()."]').find('.progress').css('width', progress+'%');
						}, 50);
						} else {
							item".$item->get_id().".pause();
							item".$item->get_id().".currentTime = 0;
							clearInterval(item".$item->get_id().".progressupdate);
							$('[data-item-id=".$item->get_id()."]').find('.progress').css('width', 0);
						}
					});

					item".$item->get_id().".onended = function() { 
						clearInterval(item".$item->get_id().".progressupdate);
						$('[data-item-id=".$item->get_id()."]').find('.progress').css('width', 0); 
					}

					item".$item->get_id().".oncanplay = function() { $('[data-item-id=".$item->get_id()."]').removeClass('disabled'); }
				});
				</script>

			</div>");
	} else {
		echo("
			<div class=\"well\"></div>
		");
	}
	echo("</div>");
	if(((($i+1) % 3) == 0) && ($i < 9)) echo("</div><div class=\"row\">");
}
echo("</div>");

?>