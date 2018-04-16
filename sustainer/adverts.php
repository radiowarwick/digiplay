<?php

Output::set_title("Advert Manager");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");
Output::add_stylesheet(LINK_ABS."css/select2.min.css");
Output::add_script(LINK_ABS."js/select2.min.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Change the adverts on the sustainer service");

$adverts = Adverts::get_all();

?>

<script>
	$(function(){
		$('#advert-id').select2({
			ajax: {
				url: '../ajax/advert-search.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term
					};
				},
				processResults: function (data, page) {
					// parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data
					return {
						results: data.data
					};
				},
				cache: true
			},
			escapeMarkup: function(markup) { return markup; }, // let our custom formatter work
			minimumInputLength: 1,
			templateResult: formatRepo, // omitted for brevity, see the source of this page
			templateSelection: formatRepoSelection
		});

		function formatRepo(repo) {
			return repo.title;
		}

		function formatRepoSelection(repo) {
			return repo.title;
		}

		$('#add-advert').click(function(){
			advertID = $('#advert-id').val();
			updateAdvertStatus(advertID, 't');
		});

		$('.remove-advert').click(function(){
			advertID = $(this).attr('data-id');
			updateAdvertStatus(advertID, 'f');
		})
	});

	function updateAdvertStatus(advertID, sue) {
		$.ajax({
			url: '../ajax/update-sustainer-advert.php',
			data: {advertid: advertID, sue: sue},
			type: 'POST',
			error: function(xhr, text, error) {
				value = $.parseJSON(xhr.responseText);
				alert(value.error);
			},
			success: function(data, text, xhr) {
				window.location.reload(true);
			}
		});
	}
</script>

<div class="row">
	<div class="col-sm-9">
		<select id="advert-id" name="advert-id" data-width="100%">
		</select>
	</div>
	<div class="col-sm-3">
		<button class="btn btn-primary" id="add-advert">Add Advert</button>
	</div>
</div>

<div style="margin-top:20px;"></div>

<?php

echo("<div class=\"list-group\">");

foreach ($adverts as $advert) {
	if($advert->get_sustainer() == 't')
		echo("<div class=\"list-group-item\">".$advert->get_title().
			"<span class=\"fa-pull-right remove-advert\" data-id=\"".$advert->get_id()."\">".Bootstrap::fontawesome("times-circle", "fa-fw fa-lg")."</span>
		</div>");
}

echo("</div>");

?>