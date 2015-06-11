<?php
Output::set_title("Jingle Packages");
MainTemplate::set_subtitle("Create and manage groups of jingles");
Output::add_script(LINK_ABS.'js/bootbox.min.js');
Output::add_script(LINK_ABS.'js/bootstrap.typeahead.min.js');

if(isset($_GET['archive']) && ($package = JinglePackages::get_by_id($_GET['archive']))) {
	if($package->get_archived() == false) $package->set_archived(true);
	else $package->set_archived(false);

	$package->save();
} else if(isset($_GET['delete']) && ($package = JinglePackages::get_by_id($_GET['delete']))) $package->delete();

if(isset($_GET['show_archived']) && $_GET['show_archived'] == 'true') $show_archived = true;
else $show_archived = false;

?>
<script>

$().ready(function() {
	<?=$show_archived? 'var archive_str = \'&show_archived=true\';':'var archive_str = \'\';'?>

	$('#packages a').on('click', function() {
		$('#packages a').removeClass('active');
		$(this).addClass('active')

		$('#package-name').text($(this).find('.package-name').text());
		$('#package-description').text($(this).find('.package-description').text());

		$('#archive').attr('href', '<?=LINK_ABS?>files/packages/?archive='+$(this).data('package-id')+archive_str);
		$('#delete').attr('href', '<?=LINK_ABS?>files/packages/?delete='+$(this).data('package-id')+archive_str);

		$('.btn-group').find('a').removeClass('disabled');

		var data = {
			'method': 'info',
			'params': {
				'id': $(this).data('package-id')
			},
			'id': Date.now()
		}

		$.post('<?=LINK_ABS?>ajax/jingle-packages.php', data).done(function(data) {
			$('#count-jingles').text(data.result.jingles.length);

			$('#jingles').html('');

			var package = data.result.package;
			$.each(data.result.jingles, function(idx, jingle) {
				$('#jingles').append(
					$('<tr data-jingle-id="'+jingle.id+'"></tr>')
					.addClass('jingle')
					.append(
						$('<td></td>')
						.text(jingle.title)
					)
					.append(
						$('<td></td>')
						.addClass('icon')
						.append(
							$('<a></a>')
							.attr('href', '<?=LINK_ABS?>music/detail/'+jingle.id)
							.append(
								$('<?=Bootstrap::glyphicon("info-sign")?>')
							)
						)
					)
					.append(
						$('<td></td>')
						.addClass('icon')
                                                .append(
                                                        $('<a></a>')
							.attr('href', '#')
                                                        .on('click', function() {
                                                                bootbox.confirm('<p>Delete <strong>'+jingle.title+'</strong> from <strong>'+package.name+'</strong>?', function(answer) {
                                                                        if(answer === true) {
										opts = {
											'method': 'delete_from',
											'params': {
												'package_id': package.id,
												'jingle_id': jingle.id
											},
											'id': Date.now()
										}
										$.post('<?=LINK_ABS?>ajax/jingle-packages.php', opts).done(function(data) {
											if(data.result == 'ok') $('[data-jingle-id='+jingle.id+']').remove();
										});
									}
                                                                });
                                                        })
                                                        .append(
                                                                $('<?=Bootstrap::glyphicon("remove-sign")?>')
                                                        )
                                                )
					)
				)
			});
		});
	});

	$('#delete').on('click', function() {
		href = $(this).attr('href');
		bootbox.confirm('<p>Are you sure you want to delete this package?</p><p>Jingles in this package will not be deleted from the system.</p>', function(result) {
			if(result === true) window.location = href;
		});
		return false;
	});

	$('.typeahead').typeahead({
		source: function(query, process) {
			var opts = {
                                'method': 'search',
                                'params': { 'q' : query },
                                'id': Date.now()
                        }
			objects = [];
			map = {};
			$.post('<?=LINK_ABS?>ajax/jingle-packages.php', opts).done(function(data) {
				$.each(data.result, function(i, object) {
					map[object.label] = object;
					objects.push(object.label);
				});
				process(objects);
			});
		},
		updater: function(item) {
			$('.typeahead').data('id', map[item].id);
			return item;
		}
	});

});

</script>

<div class="row">
	<div class="col-sm-7">
		<div class="row">
			<div class="col-sm-6">
				<a href="#" id="new_package" class="btn btn-success btn-block">New Package</a>
			</div>
			<div class="col-sm-6">
				<a href="?show_archived=<?= !$show_archived? 'true':'false'?>" class="btn btn-info btn-block"><?= !$show_archived? 'Show':'Hide'?> Archived Packages</a>
			</div>
		</div>
		<p></p>
		<div class="list-group" id="packages">
<?php foreach(JinglePackages::get_all(false) as $package) {
	if(!$show_archived && $package->get_archived() == true) continue; ?>
			<a href="#" class="list-group-item <?=$package->get_archived()? 'list-group-item-warning':''?>" data-package-id="<?=$package->get_id()?>">
				<span class="badge"><?=$package->count_jingles()?> jingles</span>
				<p class="package-name list-group-item-text"><strong><?=$package->get_name()?></strong></p>
				<?php if(strlen($package->get_description()) > 0) {?><p class="package-description list-group-item-text"><em><?=$package->get_description();?></em></p><?php } ?>
			</a>
<?php } ?>
		</div>
	</div>
	<div class="col-sm-5 selected-package">
		<h2 id="package-name">Select a package</h2>
		<p id="package-description">Choose a package from the left.</p>
		<div class="btn-group">
			<a href="#" class="btn btn-success disabled" data-toggle="tooltip" title="Coming soon!" id="edit"><?=Bootstrap::glyphicon('pencil')?> Edit</a>
			<a href="#" class="btn btn-warning disabled" id="archive"><?=Bootstrap::glyphicon('eye-close')?> Archive</a>
			<a href="#" class="btn btn-danger disabled" id="delete"><?=Bootstrap::glyphicon('remove-sign')?> Delete</a>
		</div>
		<p></p>
		<div class="input-group">
			<input type="text" autocomplete="off" data-provide="typeahead" class="form-control typeahead" placeholder="Search for jingles to add...">
			<span class="input-group-btn">
				<button id="add-jingle" class="btn btn-default" type="button">Add</button>
			</span>
		</div>
		<hr />
		<p><span id="count-jingles">0</span> jingles in this package:</p>
		<table class="table table-striped table-condensed">
			<thead>

			</thead>
			<tbody id="jingles">
			</tbody>
		</table>
	</div>
</div>

