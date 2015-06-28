<?php

Output::set_title("Trash");

$index = (isset($_REQUEST["i"])? explode(",",$_REQUEST["i"]) : array("title","artist","album"));
$limit = (isset($_GET["n"]) && is_numeric($_REQUEST["n"])? $_GET["n"] : 10);
$page = (isset($_REQUEST["p"]) && is_numeric($_REQUEST["p"])? $_REQUEST["p"] : 1);

MainTemplate::set_subtitle("Find a track in the database, edit track details");

$tracks = Tracks::get_deleted($limit,(($page-1)*$limit));

if($tracks) {
	$pages = new Paginator;
	$pages->items_per_page = $limit;
	$pages->index = implode(",",$index);
	$pages->mid_range = 5;
	$pages->items_total = Tracks::count_deleted();
	$pages->paginate();

	$low = (($page-1)*$limit+1);
	$high = (($low + $limit - 1) > Tracks::count_deleted())? Tracks::count_deleted() : $low + $limit - 1;

	echo("<script>
		$(function () {
			$('.track-info').popover({
				'html': true, 
				'trigger': 'hover',
				'title': function() { 
					return($(this).parent().parent().find('.title').html())
				},
				'content': function() {
					return($(this).parent().find('.hover-info').html());
				}
			});
".(Session::is_group_user("Playlist Admin") ? "
		var item;
		$('.playlist-add').click(function() {
			item = $(this).parent().parent();
			playlists = $(this).attr('data-playlists-in').split(',');
			$('.playlist-select').parent().removeClass('active');
			$('.playlist-select').find('span').removeClass('glyphicon-minus').addClass('glyphicon-plus');
			$('.playlist-select').each(function() {
				if($.inArray($(this).attr('data-playlist-id'),playlists) > -1) {
					$(this).find('span').removeClass('icon-plus').addClass('glyphicon-minus');
					$(this).parent().addClass('active');
				}
			})
		});

		$('.playlist-select').click(function() {
			obj = $(this);
			if($(this).parent().hasClass('active')) {
				$(this).find('span').removeClass('glyphicon-minus').addClass('glyphicon-refresh');
				$.ajax({
					url: '".LINK_ABS."ajax/track-playlist-update.php',
					data: 'trackid='+item.attr('id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=del',
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-minus');
						alert(value.error);
					},
					success: function(data,text,xhr) {
						values = $.parseJSON(data);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-plus');
						obj.parent().removeClass('active');
						item.find('.playlist-add').attr('data-playlists-in',values.playlists.join(','));
					}
				});
			} else {
				$(this).find('span').removeClass('glyphicon-plus').addClass('glyphicon-refresh');
				$.ajax({
					url: '".LINK_ABS."ajax/track-playlist-update.php',
					data: 'trackid='+item.attr('id')+'&playlistid='+obj.attr('data-playlist-id')+'&action=add',
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-plus');
						alert(value.error);
					},
					success: function(data,text,xhr) {
						values = $.parseJSON(data);
						obj.find('span').removeClass('glyphicon-refresh').addClass('glyphicon-minus');
						obj.parent().addClass('active');
						item.find('.playlist-add').attr('data-playlists-in',values.playlists.join(','));
					}
				});
				$(this).parent().addClass('active');
				$(this).find('span').removeClass('glyphicon-plus').addClass('glyphicon-minus');
			}
		});		
" : "").
(Session::is_group_user("Music Admin") ? "
		var trackid;
		$('.track-delete').click(function() {
			$('.delete-track-title').html($(this).parent().parent().find('.title').html());
			trackid = $(this).attr('data-dps-id');
		});

		$('.yes-definitely-delete').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/delete-track.php',
				data: 'id='+trackid,
				type: 'POST',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					window.location.reload(true); 
				}
			});
		});
" : "").
"		});
	</script>");

	echo("<a href=\"#\" id=\"flag\" class=\"btn btn-danger btn-block\">".Bootstrap::glyphicon("warning-sign")." Empty Trash</a>");
	echo("<p></p>");

	echo("<h3>".Tracks::count_deleted()." results for deleted items.</small></h3>");
	echo("<div class=\"row\"><div class=\"col-lg-5\"><h5>Showing results ".$low." to ".$high."</h5></div><div class=\"pull-right\">".$pages->display_jump_menu().$pages->display_items_per_page()."</div></div>");
	echo("<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"album\">Album</th>
			<th class=\"length nowrap\">Length</th> 
			".(Session::is_group_user("Playlist Admin")? "<th class=\"icon\"></th>" : "")."
			".(Session::is_group_user("Music Admin")? "<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($tracks as $track) {
		echo("
		<tr id=\"".$track->get_id()."\">
			<td class=\"icon\">
				<a href=\"".LINK_ABS."music/detail/".$track->get_id()."\" class=\"track-info\">
					".Bootstrap::glyphicon("info-sign")."
				</a>
				<div class=\"hover-info\">
					<strong>Artist:</strong> ".$track->get_artists_str()."<br />
					<strong>Album:</strong> ".$track->get_album()->get_name()."<br />
					<strong>Year:</strong> ".$track->get_year()."<br />
					<strong>Length:</strong> ".Time::format_succinct($track->get_length())."<br />
					<strong>Origin:</strong> ".$track->get_origin()."<br />
					".($track->get_reclibid()? "<strong>Reclib ID:</strong> ".$track->get_reclibid()."<br />" : "")."
					<strong>Censored:</strong> ".($track->is_censored()? "Yes" : "No")."<br /> 
				</div>
			</td>
			<td class=\"artist\">".$track->get_artists_str()."</td>
			<td class=\"title\">".$track->get_title()."</td>
			<td class=\"album\">".$track->get_album()->get_name()."</td>
			<td class=\"length nowrap\">".Time::format_succinct($track->get_length())."</td>");
			if(Session::is_group_user("Playlist Admin")) {
				$playlists = array();
				foreach($track->get_playlists_in() as $playlist) $playlists[] = $playlist->get_id();
				echo("<td class=\"icon\"><a href=\"#\" data-toggle=\"modal\" data-target=\"#playlist-modal\" data-backdrop=\"true\" data-keyboard=\"true\" data-dps-id=\"".$track->get_id()."\" data-playlists-in=\"".implode(",",$playlists)."\" class=\"playlist-add\" title=\"Add to playlist\" rel=\"twipsy\">".Bootstrap::glyphicon("plus-sign")."</a></td>"); 
			}
			echo((Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"#\" data-toggle=\"modal\" data-target=\"#delete-modal\" data-backdrop=\"true\" data-keyboard=\"true\" data-dps-id=\"".$track->get_id()."\" class=\"track-delete\" title=\"Delete this track\" rel=\"twipsy\">".Bootstrap::glyphicon("remove-sign")."</a></td>" : "")."
		</tr>");
	}
	echo("</table>");
	echo($pages->return);
	
}

if(Session::is_group_user("Music Admin")) echo(Bootstrap::modal("delete-modal", "<p>Are you sure you want to move <span class=\"delete-track-title\">this track</span> to the trash?</p>", "Delete track", "<a href=\"#\" class=\"btn btn-primary yes-definitely-delete\">Yes</a> <a href=\"#\" class=\"btn btn-default\" data-dismiss=\"modal\">No</a>"));

?>
