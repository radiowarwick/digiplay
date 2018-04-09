$(function () {
	// Make AW items drag/droppable.
	function makeDraggable() {
		$('.dps-aw-item').draggable({
			revert: "invalid",
			appendTo: 'body',
			containment: 'window',
			scroll: false,
			helper: 'clone',
			start: function(event, ui) { jQuery(this).hide(); },
			stop: function(event, ui) { jQuery(this).show(); }
 		});
 		console.log("test");
	}
	makeDraggable();

	$('#walls-tabs').children().click(function(){
		if ($(this).attr("id") != "wall-new") {
    		$("#walls-tab.list-group-item-info").removeClass("list-group-item-info");
    		$(this).addClass("list-group-item-info");
    	}
    	//alert("IT WAS CLICKED");
  	});
	
	// Make empty spaces and tray droppable
	function makeDroppable(){
		$('.spacer, #tray').droppable({
			drop: function(ev, ui) {
				var dropped = ui.draggable;
				var droppedOn = $(this);
				if ( droppedOn.attr('id') != 'tray' ) {
					if ($(dropped).parent().attr('id') == 'search-results') {
						droppedOn.find('.dps-aw-item').prependTo('#tray');
					} else {
						droppedOn.find('.dps-aw-item').appendTo($(dropped).parent());
					}
				}
				if ($(dropped).parent().attr('id') == 'search-results') {
					$(dropped).clone().draggable({
						revert: "invalid",
						appendTo: 'body',
						containment: 'window',
						scroll: false,
						helper: 'clone', start: function(event, ui) {ui.helper.addClass('dps-aw-style-1'); }
					}).insertBefore(dropped);
					$(dropped).addClass('dps-aw-style-1').attr('data-dps-aw-style','1');
					$('#tray .clearfix').appendTo('#tray');
				}
				$(dropped).detach().appendTo(droppedOn).draggable({
					revert: "invalid",
					appendTo: 'body',
					containment: 'window',
					scroll: false,
					helper: 'clone',
					start: function(event, ui) {jQuery(this).hide(); },
					stop: function(event, ui) {jQuery(this).show(); }
				});
				console.log(dropped.parent());
				console.log(dropped.data( "dpsAudioId" ));
				console.log(dropped.data( "dpsAwStyle" ));
				console.log(dropped.parent().data( "dpsAwSlot" ));
				if ($('#tray div.dps-aw-item').length > 0 ) { $('#tray-wrap p').hide(); }

				$('.alert-danger').css({'visibility':'visible'});
			}
		});
	}
	makeDroppable();
	
	// Searching for music
	$('#search-form').submit(function(){
		$('#search-btn').empty().html('<i class="fa fa-sync fa-spin"></i>');
		$.ajax({
			url: "../ajax/audiowall-music-search.php?q=" + escape($("#search-term").val()),
			type: "GET",
			error: function(xhr, text, error) {
				value = $.parseJSON(xhr.responseText);
				alert(value.error);
			},
			success: function(data, text, xhr) {
				var results = JSON.parse(data);

				$("#search-btn").empty().text("Search");
				$("#search-result-count").text(results.length);
				$("#search-result-term").text($("#search-term").val());
				$("#search-result-message").show();

				$("#search-results").find("tbody").empty();

				for(var i = 0; i < results.length; i++) {
					track = results[i];

					// Create row and fill with data
					row = $("<tr></tr>").appendTo("#search-results tbody");

					linkIcon = "<a href='../music/detail/" + track["id"] + "' target='_blank' title='Track information'><span class='fa fa-info-circle'></span></a>";
					row.append("<td>" + linkIcon + "</td>");

					addIcon = "<a href='#' data-dps-audio-id='" + track["id"] + "' title='Add track to tray'><span class='fa fa-plus-circle'></span></a>";
					row.append("<td>" + addIcon + "</td>");

					row.append("<td id='title'>" + track["title"] + "</td>");
					if(track["artist"] !== false)
						row.append("<td>" + track["artist"] + "</td>");
					else
						row.append("<td>(none)</td>");
					row.append("<td>" + track["album"] + "</td>");

					playIcon = "<span class='play-audio' data-dps-audio-id='" + track["id"] + "' data-dps-action='play'><i class='fa fa-play-circle fa-lg'></i></span>";
					row.append("<td>" + playIcon + "</td>");

					row.append("<td id='length' class='length' data-dps-audio-length='" + track["length2"] + "'>" + track["length"] + "</td>");

					row.find(".play-audio").click(audioPreviewClick);

					// When plus is clicked create div and add to tray.
					row.find("a[href='#']").click(function(e){
						e.preventDefault();

						row = $(this).parent().parent();

						id = $(this).attr("data-dps-audio-id");
						title = row.find("#title").text();
						length = row.find("#length").text();
						length2 = row.find("#length").attr("data-dps-audio-length");

						track = $("<div></div>");
						track.attr("id", "track-draggable-" + id);
						track.attr("data-dps-aw-style", "1");
						track.attr("data-dps-audio-id", id);
						track.addClass("ui-draggable");
						track.addClass("dps-aw-item");
						track.addClass("dps-aw-style-1");
						track.css({"background-color": "rgb(18,137,192)"});

						track.append("<span class='text'>" + title + "</span>");
						track.append("<span class='length' data-dps-audio-length='" + length2 + "'>" + length + "</span>");

						playSpan = $("<span></span>");
						playSpan.attr("class", "play-audio");
						playSpan.attr("data-dps-action", "play");
						playSpan.attr("data-dps-audio-id", id);
						playSpan.append("<i class='fa fa-play-circle fa-lg'></i>");
						track.append(playSpan);
						playSpan.click(audioPreviewClick);
						
						track.prependTo("#tray");
						$('#tray-wrap p').hide();

						$('.dps-aw-item').draggable({
							revert: "invalid",
							appendTo: 'body',
							containment: 'window',
							scroll: false,
							helper: 'clone',
							start: function(event, ui) { jQuery(this).hide(); },
							stop: function(event, ui) { jQuery(this).show(); }
				 		});
					});
				}
			}
		});
		return false;
	});
	
	// Make walls sortable
	// $('#walls-tabs').sortable({
	// 	items: "li:not(#wall-new)",
	// 	axis: 'x',
	// 	containment: 'parent',
	// 	stop: function(event, ui) {
	// 		renumberWalls();
	// 		}
	// });

	// Create item edit modal on doubleclick
	$('.dps-aw-item').dblclick(function() {
		$('#text').val($(this).find('.text').text());
		$('#item-edit').attr('data-dps-item-id', $(this).data('dps-item-id'));
		$('#sample .text').html($(this).find('.text').text());
		$('#sample .length').html($(this).find('.length').text());
		$('#style').val($(this).attr('data-dps-aw-style'));
		$('#sample').removeClass().addClass('dps-aw-item dps-aw-style-'+$(this).attr('data-dps-aw-style'));
		$('#dps-aw-item').val($(this).parent().attr('data-dps-aw-slot'));
		$('#dps-aw-page').val($(this).parent().parent().parent().attr('data-dps-wall-page'));
		$('#sample').attr('style', $('#style option:selected').attr('style'));
		$('#item-edit').modal('show');
	});

	var count = 0;

	// Update AW item preview
	$('#text').keyup(function(){
		$('#sample .text').html($(this).val());
	});
	
	$('#style').change(function() { 
		$('#sample').attr('class','dps-aw-item dps-aw-style-'+$(this).val());
		$('#sample').attr('style', $('#style option:selected').attr('style'));
	});

	
	// Close edit modal on cancel
	$('#item-edit .btn.btn-danger').click(function(){
		$('#item-edit').modal('hide'); return false; 
	});
	
	// Apply item edit changes
	$('#item-edit .btn.btn-primary').click(function(){ 
		var item = $('#walls div[data-dps-wall-page="'+$('#dps-aw-page').val()+'"] li[data-dps-aw-slot="'+$('#dps-aw-item').val()+'"]');
		item.find('div').attr('data-dps-aw-style',$('#style').val())
			.attr('class','ui-draggable dps-aw-item dps-aw-style-'+$('#style').val())
			.find('.text').html($('#text').val())
			$('#walls div[data-dps-wall-page="'+$('#dps-aw-page').val()+'"] li[data-dps-aw-slot="'+$('#dps-aw-item').val()+'"]').children('div').attr('style', $('#style option:selected').attr('style'));
		$('#item-edit').modal('hide');
		audiowallChange();		
		return false;
	});

	$('#item-edit .form-control').on('keypress', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			e.preventDefault();
		}
	});
	
	//Edit Set Name and Description
	$('#aw_edit_buttons .btn-primary').click(function(){
		$('#set-edit').modal('show');
		$('#set-edit-page').attr('value', $('.walls-tab.active').children().text());
	});

	//change values for set on confirm
	$('#set-edit .btn.btn-primary').click(function(){ 
		$('#wall-name').html($('#set-edit-name').val());
		$('#wall-description').html($('#set-edit-desc').val());
		$('.walls-tab.active').children().html(($('#set-edit-page').val()));
		$('#set-edit').modal('hide');
		audiowallChange();
		return false;
	});

	$('#set-edit .btn.btn-danger').click(function(){
		$('#set-edit').modal('hide'); return false; 
	});

	// Save audiowall set
	$('#aw_edit_buttons .btn-success').click(function(){
		var walls = new Array();
		$('#walls>div.dps-wall').each(function(){
			var items = new Array();
			$(this).find('li div').each(function(){
				items.push({ 
					'item':$(this).parent().attr('data-dps-aw-slot'),
					'audio_id':$(this).attr('data-dps-audio-id'),
					'style_id':$(this).attr('data-dps-aw-style'),
					'text':$(this).find('.text').html()
				});
			});
			walls.push({
				'id': $(this).attr('data-dps-wall-id'),
				'name': $('a[href$="'+$(this).attr('id')+'"]').text().replace("'", "''"),
				'page': $(this).attr('data-dps-wall-page'),
				'items': items
			});
		});
		var data = {
			'id':$('#wall-name').attr('data-dps-set-id'),
			'name':$('#wall-name').html().replace("'", "''"),
			'description':$('#wall-description').html().replace("'", "''"),
			'walls':walls
		};
		$('#aw_edit_buttons .btn-success').html('<img src="../img/ajax-loader.gif" />');
		$('#aw_edit_buttons .text-danger').css({'display':'none'});
		$.post("../ajax/save-audiowall.php", data, function(data){ $('#aw_edit_buttons .btn-success').html('Save'); $('#aw_edit_buttons .text-success').css({'display':'inline-block'}); $('#browse pre').html(data); window.location.reload(true); });
	});

	//bring up delete item confirm modal
	$('#item-delete').click(function(){
		$('#delete-item-item').html($('#text').val());
		$('#delete-item-item').attr('data-dps-item-id', $('#item-edit').data('dps-item-id'));
		$('#item-edit').modal('hide');
		$('#delete-item-modal').modal('show');
	});

	//delete item from wall
	$('#delete-item-modal .btn.btn-primary').click(function(){
		$.ajax({
			url: '../ajax/delete-audiowall-item.php',
			data: { itemid: $('#delete-item-item').attr('data-dps-item-id') },
			type: 'POST',
			error: function(xhr,text,error) {
				value = $.parseJSON(xhr.responseText);
				alert(value.error);
			},
			success: function(data,text,xhr) {
				window.location.reload(true); 
			}
		});
		return(false);
	});

	//hide modal on cancel
	$('#delete-item-modal .btn-danger').click(function(){
		$('#delete-item-modal').modal('hide');
	});

	// Add a new wall to the set
	$('#wall-new').click(function(){
		// if($('#walls-tabs li:not(#wall-new)').length == 0) {
		// 	page = 0;
		// } else {
		// 	page = parseInt( $('#walls-tabs li:not(#wall-new) a').last().attr('data-dps-wall-page') ) +1 ;
		// }
		// $('#new').clone().insertBefore('#new').attr('id', 'page'+page).attr('data-dps-wall-page', page).addClass('dps-wall');
		// $('<li><a href="#page'+page+'" data-toggle="tab" data-dps-wall-page="'+page+'">New Page <span class="glyphicon glyphicon-remove"></span></a></li>').insertBefore('#wall-new');
		// makeDroppable();
		// makeDeleteable();
		// $('#walls-tabs a[href=#page'+page+']').click(function (e) {
 		// 		e.preventDefault();
		// 	$(this).tab('show');
		// }).tab('show');
		$('#add-page-modal').modal('show');
	});

	//hide modal on cancel
	$('#add-page-modal .btn-danger').click(function(){
		$('#add-page-modal').modal('hide');
		return(false);
	});

	function submit_add_page() {
		$.ajax({
			url: '../ajax/add-audiowall-page.php',
			data: { setid: $('#wall-name').data('dps-set-id'), name: $('#new-page-name').val().replace("'", "''"), desc: $('#new-page-desc').val().replace("'", "''") },
			type: 'POST',
			error: function(xhr,text,error) {
				value = $.parseJSON(xhr.responseText);
				alert(value.error);
			},
			success: function(data,text,xhr) {
				window.location.reload(true); 
			}
		});
		return(false);
	}

	//add page on confirm
	$('#add-page-modal .btn-primary').click(function(){
		submit_add_page();
	});

	$('#add-page-modal .form-control').on('keypress', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			submit_add_page();
			e.preventDefault();
		}
	});
	
	// open a delete confirmation modal
	function makeDeleteable(){
		$('.badge-remove').click(function(){
			$('#delete-modal-page').html($(this).parent().text());
			$('#delete-modal').modal('show');
			$('#delete-modal').data( "dps-wall-id", $(this).parent().data('dps-wall-id') );
		});
	}
	makeDeleteable();

	//delete a wall from the set
	function deleteModal() {
		$('#delete-modal .btn-primary').click(function(){
			// wall = $(this).parent().attr('href').substr(5);
			// $(this).parent().parent().remove();
			// $('#page'+wall).remove();
			// if($('#walls-tabs li:not(#wall-new)').length == 0) {
			// 	$('#wall-new').click();
			// }
			// if($('#walls-tabs li.active').length == 0) {
			// 	$('#walls-tabs li:first-child a').tab('show');
			// }
			$.ajax({
				url: '../ajax/delete-audiowall-page.php',
				data: { wallid: $('#delete-modal').data('dps-wall-id') },
				type: 'POST',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					window.location.reload(true); 
				}
			});
			renumberWalls();
			return(false);
		});
	}
	deleteModal();

	//close modal on cancel
	$('#delete-modal .btn.btn-danger').click(function(){
		$('#delete-modal').modal('hide'); return false; 
	});
	

	// AJAX Update Test
	function ajaxUpdateTest(oldItemLocation, newItemLocation, wallID){
		
		var data = {
			'id':$('#wall-name').attr('data-dps-set-id'),
			'name':$('#wall-name').html(),
			'description':$('#wall-description').html(),
			'walls':walls
		};
		console.log(data);
		$.post("../ajax/update-audiowall-item.php", data, function(data){ $('#aw_edit_buttons .btn-success').html('Save'); $('#aw_edit_buttons .text-success').css({'display':'inline-block'}); $('#browse pre').html(data); });
	}

	// Functions for moving wall order
	function movePages() {
		// Move page up
		$(".badge-up").click(function(){
			wall = $(this).parent();
			number = parseInt(wall.attr("data-dps-wall-page"));

			// only move up if not the first wall
			if(number > 0) {
				previousWall = $("a[data-dps-wall-page='" + (number - 1) + "']");
				previousWall.before(wall);
				renumberWalls();

				preWall = $("#page" + (number - 1));
				afterWall = $("#page" + number);

				preWall.attr("id", "page" + number);
				preWall.attr("data-dps-wall-page", number);

				afterWall.attr("id", "page" + (number - 1));
				afterWall.attr("data-dps-wall-page", (number - 1));

				audiowallChange();
			}
		});

		// Move page down
		$(".badge-down").click(function(){
			wall = $(this).parent();
			number = parseInt(wall.attr("data-dps-wall-page"));

			// only move page down if not the last page
			if(number < $(".list-group-item").length - 2) {
				nextWall = $("a[data-dps-wall-page='" + (number + 1) + "']");
				wall.before(nextWall);
				renumberWalls();

				preWall = $("#page" + number);
				afterWall = $("#page" + (number + 1));

				preWall.attr("id", "page" + (number + 1));
				preWall.attr("data-dps-wall-page", (number + 1));

				afterWall.attr("id", "page" + number);
				afterWall.attr("data-dps-wall-page", number);

				audiowallChange();
			}
		});
	}
	movePages();

	// Add event listen for renaming page names
	function renamePages() {
		$(".badge-edit").click(function(){
			pageText = $(this).parent().text();
			pageID = $(this).parent().attr("data-dps-wall-id");
			$("#edit-page-modal").modal("show");
			$("#edit-page-name").val(pageText);
			$("#edit-page-name").attr("data-dps-wall-id", pageID);
		});

		$("#edit-page-modal").find(".btn-danger").click(function(){
			$("#edit-page-modal").modal("hide");
		});

		$("#edit-page-modal").find(".btn-primary").click(function(){
			pageText = $("#edit-page-name").val();
			pageID = $("#edit-page-name").attr("data-dps-wall-id");
			$("a[data-dps-wall-id='" + pageID + "'] #page-name").text(pageText);
			$("#edit-page-modal").modal("hide");
			audiowallChange();
		});

		$("#edit-page-modal").find("form").submit(function (){
			return false;
		});
	}
	renamePages();

	function audioPreviewClickListener() {
		$(".play-audio").click(audioPreviewClick);
	}
	audioPreviewClickListener();

});

// plays/pauses/creates an audio preview for the audiowall item
function audioPreviewClick() {
	audioID = $(this).attr("data-dps-audio-id");
	action = $(this).attr("data-dps-action");
	parent = audioPreviewParent($(this));
	console.log(audioID + ", " + action);


	if(action == "play") {
		if($(this).parent().find("audio").length > 0) {
			audio = $(this).next().get(0);
			audio.currentTime = 0;
			audio.play();

			$(this).find("svg").attr("class", "fa-stop-circle fa-lg");
			$(this).attr("data-dps-action", "stop");
			parent.find(".length").css("font-weight", "bold");
		}
		else {
			audioElement = $("<audio src='../audio/preview/" + audioID + ".mp3' style='display:none;'>");
			
			$(this).after(audioElement);
			$(this).attr("data-dps-action", "loading");
			$(this).find("svg").attr("class", "fa-sync fa-spin fa-lg");

			// when audio is loaded
			audioElement.on("canplaythrough", function(){
				parent = audioPreviewParent($(this));

				parent.find(".length").css("font-weight", "bold");
				$(this).get(0).play();
				parent.find(".play-audio").attr("data-dps-action", "stop");
				parent.find(".play-audio").find("svg").attr("class", "fa-stop-circle fa-lg");
			});

			// when audio has stopped playing
			audioElement.on("ended", function(){
				parent = audioPreviewParent($(this));

				$(this).get(0).pause();
				parent.find(".play-audio").attr("data-dps-action", "play");
				parent.find(".play-audio").find("svg").attr("class", "fa-play-circle fa-lg");

				parent.find(".length").css("font-weight", "initial");
				$(this).trigger("timeupdate");
			});

			// periodic updates when audio is playing
			audioElement.on("timeupdate", function(){
				parent = audioPreviewParent($(this));

				if($(this).get(0).paused)
					time = 0;
				else
					time = $(this).get(0).currentTime;

				length = parent.find(".length").attr("data-dps-audio-length");
				timeString = formatTimeLeft(length, time);

				parent.find(".length").text(timeString);
			});
		}
	}
	else if(action == "stop") {
		$(this).next().get(0).pause();
		$(this).next().trigger("timeupdate");
		$(this).attr("data-dps-action", "play");
		$(this).find("svg").attr("class", "fa-play-circle fa-lg");

		parent.find(".length").css("font-weight", "initial");
	}
}

function formatTimeLeft(length, played) {
	timeLeft = length - played;
	
	minutes = Math.floor(timeLeft / 60);
	if(minutes == 0)
		minutes = "0";

	seconds = Math.floor(timeLeft % 60);
	if(seconds == 0)
		seconds = "00";
	else if(seconds < 10)
		seconds = "0" + seconds;

	return minutes + "m " + seconds + "s";
}

// returns correct element if a search result or block
// if in block
// <li> <- return this
// 	...
//  <span class='play-audio'></span>
//	<audio></audio>
// </li>
// if in table
// <tr> <- return this
//	<td>
//		<span class='play-audio'>
//		<audio></audio>
//	</td>
// </td>
function audioPreviewParent(element) {
	if(element.parent().is("td"))
		return element.parent().parent();
	else
		return element.parent();
}

// Re-number walls
function renumberWalls(){
	$(".list-group-item[id!='wall-new']").each(function(i){
		$(this).attr('data-dps-wall-page', i);
		$(this).attr('href', '#page' + i);
	});
}

// Show the unsaved changes message
function audiowallChange() {
	$('.alert-danger').css({'visibility':'visible'});
}