var wavesurfer = Array();

function wv_create(id, url) {
	wavesurfer[id] = Object.create(WaveSurfer);
	var options = {
		container	: document.querySelector('#waveform'+id),
		renderer	: 'SVG'
	};

	wavesurfer[id].init(options);
	wavesurfer[id].setVolume(0);
	wavesurfer[id].on('loading', function(p) { wv_loading(wavesurfer[id], p); });
	wavesurfer[id].on('ready', function() { wv_ready(wavesurfer[id]); });

	var lastFire = 0;
	wavesurfer[id].on('progress', function() { 
		if(Date.now() - lastFire > 50) {
			wv_progress(wavesurfer[id]);
			lastFire = Date.now();
		}
	});
	$('#waveform'+id).find('svg').css('opacity', '0');

	wavesurfer[id].load(url);

	return wavesurfer[id];
}

function wv_loading(wv, percent) {
	$('#'+wv.params.container.id).find('.progress').find('.progress-bar').css('width', percent*100+'%');
	if(Math.round(percent * 10) == 0) {
		$('#'+wv.params.container.id).parent().parent().first('div').find('button').attr('disabled', 'disabled');
		$('#'+wv.params.container.id).parent().parent().first('div').find('small').html('00:00 / 00:00');
		$('#'+wv.params.container.id).find('svg').fadeTo('fast', 0, function() { $('#'+wv.params.container.id).find('.progress').removeClass('active').fadeIn('fast'); });
	} else if (percent == 1) {
		$('#'+wv.params.container.id).find('.progress').addClass('active');
	} else if (percent >= 100) {
		$('#'+wv.params.container.id).find('.progress').removeClass('active').fadeOut('fast', function() { $('#'+wv.params.container.id).find('svg').fadeTo('fast', 1); });
	}
}

function wv_ready(wv) {
	wv.fireEvent('progress');
	$('#'+wv.params.container.id).parent().parent().first('div').find('button').removeAttr('disabled');
	$('#'+wv.params.container.id).parent().parent().first('div').find('button').click(function() { 
		wv.playPause();
		if($(this).find('span').hasClass('glyphicon-play')) $(this).find('span').removeClass('glyphicon-play').addClass('glyphicon-pause');
		else $(this).find('span').removeClass('glyphicon-pause').addClass('glyphicon-play');
	});
}

function wv_progress(wv) {
	t = wv.timings(); a = Math.floor(t[0] / 60); b = Math.floor(t[0] - (a * 60)); c = Math.floor(t[1] / 60); d = Math.floor(t[1] - (c * 60));
	$('#'+wv.params.container.id).parent().parent().first('div').find('small').html(
		((a < 10) ? ('0' + a) : a)+':'+((b < 10) ? ('0' + b) : b)+' / '+((c < 10) ? ('0' + c) : c)+':'+((d < 10) ? ('0' + d) : d)
	);
	if(Math.round(t[0] * 10) == Math.round(t[1] * 10)) {
		$('#'+wv.params.container.id).parent().parent().first('div').find('span').removeClass('glyphicon-pause').addClass('glyphicon-play');
		wv.stop();
	}
}