var wavesurfer = Array();

function wv_create(id) {
	wavesurfer[id] = Object.create(WaveSurfer);
	var options = {
    	container     : document.querySelector('#waveform'+id),
    	renderer	  : 'SVG'
    };

	wavesurfer[id].init(options);
	wavesurfer[id].setVolume(0);
	wavesurfer[id].container = '#'+$('#'+options.container.id).parents('.audio-player').attr('id');
	wavesurfer[id].on('loading', function(p) { wv_loading(wavesurfer[id], p); });
	wavesurfer[id].on('ready', function() { wv_ready(wavesurfer[id]); });
	wavesurfer[id].on('play', function() {
		$(wavesurfer[id].container).find('.playpause').find('span').removeClass('glyphicon-play').addClass('glyphicon-pause');
	});
	wavesurfer[id].on('pause', function() {
		$(wavesurfer[id].container).find('.playpause').find('span').removeClass('glyphicon-pause').addClass('glyphicon-play');
	});
	wavesurfer[id].on('stop', function() {
		$(wavesurfer[id].container).find('.playpause').find('span').removeClass('glyphicon-pause').addClass('glyphicon-play');
	});

	var lastFire = 0;
	wavesurfer[id].on('progress', function() { 
		if(Date.now() - lastFire > 50) {
			wv_progress(wavesurfer[id]);
			lastFire = Date.now();
		}
	});
	$('#waveform'+id).find('svg').css('opacity', '0');

	return wavesurfer[id];
}

function wv_loading(wv, percent) {
	$(wv.container).find('.progress').find('.progress-bar').css('width', percent*100+'%');
  	if(Math.round(percent * 10) == 0) {
  		$(wv.container).find('button').attr('disabled', 'disabled');
  		$(wv.container).find('small').html('00:00.00 / 00:00.00');
		$(wv.container).find('svg').fadeTo('fast', 0, function() { $(wv.container).find('.progress').removeClass('active').fadeIn('fast'); });
  	} else if (percent == 1) {
  		$(wv.container).find('.progress').addClass('active');
  	} else if (percent >= 100) {
  		$(wv.container).find('.progress').removeClass('active').fadeOut('fast', function() { $(wv.container).find('svg').fadeTo('fast', 1); });  		
  	}
}

function wv_ready(wv) {
	wv.fireEvent('progress');
	$(wv.container).find('.duration').html(formatTime(wv.timings()[1]));
	$(wv.container).find('button').removeAttr('disabled');
	$(wv.container).find('.playpause').click(function() { 
		wv.playPause();
	});
	$(wv.container).find('.stop').click(function() { 
		wv.stop();
		$(wv.container).find('.glyphicon-pause').removeClass('glyphicon-pause').addClass('glyphicon-play');
		wv_progress(wv);
	});
}

function wv_progress(wv) {
	t = wv.timings(); 
	$(wv.container).find('.elapsed').html(formatTime(t[0]));
	$(wv.container).find('.remain').html(formatTime(t[1] - t[0]));
}

function formatTime(sec) {
	a = Math.floor(sec / 60); b = Math.floor(sec - (a * 60)); c = Math.floor((sec - Math.floor(sec))*100);
	return ((a < 10) ? ('0' + a) : a)+':'+((b < 10) ? ('0' + b) : b)+'.'+((c < 10) ? ('0' + c) : c);
}