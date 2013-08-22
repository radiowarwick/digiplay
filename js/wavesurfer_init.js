var wavesurfer = Array();

function wv_create(container, url) {
	wv = Object.create(WaveSurfer);
	var options = {
    	container     : document.querySelector(container),
    	renderer	  : 'SVG'
    };

	wv.init(options);
	wv.setVolume(0);
	wv.on('loading', function(p) { wv_loading(wv, p); });
	wv.on('ready', function() { wv_ready(wv); });
	wv.on('progress', function() { wv_progress(wv); });
	$(container).find('svg').css('opacity', '0');

	wv.load(url);

	return wv;
}

function wv_loading(wv, percent) {
	$('#'+wv.params.container.id).find('.progress').find('.progress-bar').css('width', percent*100+'%');
  	if(percent == 1) {
  		$('#'+wv.params.container.id).find('.progress').addClass('active');
  	} else if (percent >= 100) {
  		$('#'+wv.params.container.id).find('.progress').fadeOut('fast', function() { $('#'+wv.params.container.id).find('svg').fadeTo('fast', 1); });
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
}