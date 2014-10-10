var wavesurfer = Array();

function wv_create(id) {
	wavesurfer[id] = Object.create(WaveSurfer);
	var options = {
		id			: id,
		container	: document.querySelector('#waveform'+id),
		waveColor	: '#ccc',
		progressColor: '#555',
		loaderColor	: 'green',
		cursorcolor	: 'navy',
		hideScrollbar: true,
		height: 95
	};

	wavesurfer[id].init(options);
	wavesurfer[id].setVolume(0);
	wavesurfer[id].container = '#'+$('#'+options.container.id).parents('.audio-player').attr('id');
	wavesurfer[id].on('loading', function(p) { wv_loading(wavesurfer[id], p); });
	wavesurfer[id].on('ready', function() { wv_ready(wavesurfer[id]); });
	wavesurfer[id].on('play', function() { });
	wavesurfer[id].on('pause', function() { });
	wavesurfer[id].on('finish', function() { wavesurfer[id].stop(); })
	wavesurfer[id].on('stop', function() { 
		$(wavesurfer[id].container).find('.glyphicon-pause').removeClass('glyphicon-pause').addClass('glyphicon-play');
		wv_progress(wavesurfer[id]); 
	});

	var lastFire = 0;
	wavesurfer[id].on('progress', function(t) { 
		if(Date.now() - lastFire > 50) {
			wv_progress(wavesurfer[id], t);
			lastFire = Date.now();
		}
	});
	
	$('#waveform'+id).find('wave').css('opacity', '0');

	return wavesurfer[id];
}

function wv_loading(wv, percent) {
	$(wv.container).find('.progress').find('.progress-bar').css('width', percent+'%');
  	if(Math.round(percent * 10) == 0) {
  		$(wv.container).find('button').attr('disabled', 'disabled');
		$(wv.container).find('wave').fadeTo('fast', 0, function() { $(wv.container).find('.progress').removeClass('active').fadeIn('fast'); });
  	} else  {
  		$(wv.container).find('.progress').addClass('active');
  	}
}

function wv_ready(wv) {
	wv.seekTo(0);
	wv.fireEvent('progress');
	$(wv.container).find('.progress').removeClass('active').fadeOut('fast', function() { $(wv.container).find('wave').fadeTo('fast', 1); }); 
	$(wv.container).find('.duration').html(formatTime(wv.getDuration()));
	$(wv.container).find('button').removeAttr('disabled');
	$(wv.container).find('.playpause').on('click', function() { 
		if(wv.backend.isPaused()) {
			$(wv.container).find('.glyphicon-play').removeClass('glyphicon-play').addClass('glyphicon-pause');
		} else {
			$(wv.container).find('.glyphicon-pause').removeClass('glyphicon-pause').addClass('glyphicon-play');	
		}
		wv.playPause();
	});
	$(wv.container).find('.stop').on('click', function() { 
		wv.stop();
		wv_progress(wv);
	});
	$(wv.container).find('.zoom').on('click', function() {
		wv.toggleScroll();
		vocal_markers(wv);
	})

	wv.timeline = Object.create(WaveSurfer.Timeline);
    wv.timeline.init({
        wavesurfer: wv,
        container: wv.params.container
    });
	wv.setVolume(1);

	vocal_markers(wv);
}

function wv_progress(wv, t) {
	if (t > 0.9999) { wv.fireEvent('finish'); }
	$(wv.container).find('.elapsed').html(formatTime(wv.getCurrentTime()));
	$(wv.container).find('.remain').html(formatTime(wv.getDuration() - wv.getCurrentTime()));
}

function vocal_markers(wv) {
	if(typeof $(wv.container).data('vocal-start') !== 'undefined') {
		if(typeof wv.vocal_start !== 'undefined') { wv.vocal_start.remove() }
		wv.vocal_start = wv.addRegion({
			start: $(wv.container).data('vocal-start'),
			end: $(wv.container).data('vocal-end'),
			drag: false,
			resize: false,
			color: 'rgba(200,100,100,0.1)'
		});
	}
}

function formatTime(sec) {
	a = Math.floor(sec / 60); b = Math.floor(sec - (a * 60));
	return ((a < 10) ? ('0' + a) : a)+':'+((b < 10) ? ('0' + b) : b);
}