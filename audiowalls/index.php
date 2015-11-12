<?php
require_once('pre.php');

Output::set_title("Audiowalls");
$sets = AudiowallSets::get_all();


$active = DigiplayDB::select("\"val\" FROM \"public\".\"usersconfigs\" WHERE \"userid\" = '".Session::get_id()."' AND \"configid\" = '1'");
?>
<style type="text/css">
table { font-size:1.2em; }
thead { display:none; }
.description { font-size:0.8em; font-style:italic; }
.hover-info { display:none; }
.table tbody tr.success td { background-color: #DFF0D8; }
</style>
<?php
echo("	<div class=\"row\"><div class=\"col-md-3\"><div class=\"well\"><p><a href=\"#\" class=\"btn btn-success\" id=\"create\">Create New Audiowall</a></p><p>An Audiowall is a 3x4 grid of buttons in Digiplay which play audio when pressed. They are displayed on the right hand side of the touchscreen in the studio. The top audiowall is set for everyone and contains core station imaging, promos and beds. The bottom audiowall can be set on this page, and may contain jingles and other audio specific to your show.</p></div></div><div class=\"col-md-9\">	<table class=\"table table-striped\" cellspacing=\"0\">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th style=\"width:65px\"></th>
					<th style=\"width:185px\"></th>
				</tr>
			</thead><tbody>");
			
foreach($sets as $set) {

	if (!$set->user_can_view() && !(Session::is_group_user('Audiowalls Admin'))) continue;

	echo("<tr".($set->get_id() == $active?' class="success"':'')."><td class=\"wall-info\" >");
	if($set->user_can_delete()){
		echo("<a href=\"users/users-viewers.php?setid=".$set->get_id()."\">".Bootstrap::glyphicon("info-sign")."</a>");
	}
	echo("</td><td><strong>".$set->get_name()."</strong><br /><span class=\"description\">".$set->get_description()."</span></td>");
	$station_aw = DigiplayDB::select("val FROM configuration WHERE parameter = 'station_aw_set' AND location = '1'");
	if (!($set->get_id() == (int)$station_aw)){
		if ($set->user_can_delete() || (Session::is_group_user('Audiowalls Admin'))) {
			echo("<td class=\"delete-aw-btn\" data-aw-name=\"".$set->get_name()."\" data-dps-set-id=\"".$set->get_id()."\" style=\"width:65px\"><a href=\"#\" class=\"btn btn-danger\">Delete</a></td>");
		} else {
			echo("<td style=\"width:65px\"></td>");
		}
	} else {
		echo ("<td style=\"width:65px\"></td>");
	}
	if ($set->user_can_edit() || (Session::is_group_user('Audiowalls Admin'))) {
		echo("<td style=\"width:65px\"><a href=\"edit.php?id=".$set->get_id()."\" class=\"btn btn-primary\">Edit</a></td>");
	} else {
		echo("<td style=\"width:65px\"></td>");
	}
	echo("<td style=\"width:185px\">");
	if ($set->get_id() == $active) {
		echo("<a href=\"#\" class=\"btn btn-success disabled\" id=\"active-aw\" data-user-id=\"".Session::get_id()."\" onclick=\"javascript: return false;\">Active Personal Audiowall</a>");
	} else {
		echo("<a href=\"#\" data-aw-id=\"".$set->get_id()."\" class=\"btn btn-default set-personal-audiowall\" onclick=\"javascript: return false;\">Use as Personal Audiowall</a>");
	}
	echo("</td></tr>");
}

echo("</tbody></table></div>");

echo(Bootstrap::modal("add-audiowall-modal", "
		<form class=\"form-horizontal\" action=\"?\" method=\"POST\">
			<fieldset>
				<div class=\"control-group\">
					<label class=\"control-label\" for=\"audiowall-name\">Audiowall Name</label>
					<div class=\"controls\">
						<input type=\"text\" class=\"form-control\" id=\"audiowall-name\" placeholder=\"Enter audiowall title.\">
					</div>
					<br>
					<label class=\"control-label\" for=\"audiowall-description\">Audiowall Description</label>
					<div class=\"controls\">
						<textarea class=\"form-control\" id=\"audiowall-description\" placeholder=\"Enter audiowall description.\"></textarea>
					</div>
				</div>
			</fieldset>
			<input type=\"hidden\"class=\"update-id\" name=\"updateid\">
		</form>
	", "Create New Audiowall", "<a class=\"btn btn-success\" id=\"create-audiowall\" href=\"#\">Create New Audiowall</a><a class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</a>"));


echo("<div id=\"delete-audiowall-modal\" class=\"modal fade\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\"> 
      <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button><h4 class=\"modal-title\">Delete Audiowall</h4>
      </div>
      <div class=\"modal-body\">
        <div class=\"row\">
          <div class=\"col-md-8\">
            Are you sure you want to delete the page: 
          </div>
          <div class=\"col-md-4\" id=\"wall-to-delete\"></div>
      </div>
      <p>&nbsp;</p>
      <div class=\"modal-footer clearfix\">
        <a href=\"#\" class=\"btn btn-primary\">Yes</a>
        <a href=\"#\" class=\"btn btn-danger\">No</a>
      </div>
    </div>
  </div>
</div>
</div>");

echo(
		"<script type=\"text/javascript\">
		$('.delete-aw-btn').click(function(){
			$('#wall-to-delete').html($(this).data('aw-name'));
			$('#wall-to-delete').attr('data-dps-aw-set', $(this).data('dps-set-id'));
			$('#delete-audiowall-modal').modal('show');
		});
		$('#delete-audiowall-modal .btn-danger').click(function(){
			$('#delete-audiowall-modal').modal('hide');
		});
		$('#delete-audiowall-modal .btn-primary').click(function(){
			$.ajax({
				url: '".LINK_ABS."ajax/delete-audiowall-set.php',
				data: { setid: $('#wall-to-delete').attr('data-dps-aw-set') },
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
		</script>
		"
	);

echo("<script type=\"text/javascript\">
		boxes = $('#create');
		boxes.click(function(){
			$('#add-audiowall-modal').modal('show');
		});

		$('#create-audiowall').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/add-audiowall-set.php',
				data: { awname: $('#audiowall-name').val(), awdescription: $('#audiowall-description').val() },
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
</script>");
echo("<script type=\"text/javascript\">
	$('.set-personal-audiowall').click(function(){
				$.ajax({
					url: '".LINK_ABS."ajax/update-audiowall-config.php',
					data: { awid: $(this).attr('data-aw-id'), userid: ".Session::get_id()."},
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
</script>");
?>
