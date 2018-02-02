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
			}
		});
	}
	makeDroppable();
	
	// Searching for music
	$('#search-form').submit(function(){
		$('#search-results').empty().html('<img src=\"../img/ajax-loader.gif\" />');
		$('#search-results').load('../ajax/audiowall-music-search.php?q='+escape($('#search-term').val()), function(){
			$('.dps-aw-item').draggable({
				revert: "invalid",
				appendTo: 'body',
				containment: 'window',
				scroll: false,
				helper: 'clone',
				start: function(event, ui) { 
					ui.helper.addClass('dps-aw-style-1');
					console.log("FIRE");
				}

			});
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
		$('#walls-tabs .glyphicon-remove').click(function(){
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
	
	// Re-number walls
	function renumberWalls(){
		/*$('#walls-tabs li:not(#wall-new) a').each(function(i){
			$(this).attr('href', '#page'+i);
		});
		$('#walls > div:not(#new)').each(function(i){
			$(this).attr({id:'page'+i, 'data-dps-wall-page':i});
		});*/
		$('#walls-tabs li:not(#wall-new) a').each(function(i){
			$(this).attr('data-dps-wall-page', i);
			$($(this).attr('href')).attr('data-dps-wall-page', i);
		});
	}

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

	$(".glyphicon-arrow-up").click(function(){
		s = $(this).parent().parent().text();
		alert("clicked up on " + s);
	});

	$(".glyphicon-arrow-down").click(function(){
		s = $(this).parent().parent().text();
		alert("clicked down on " + s);
	});
});