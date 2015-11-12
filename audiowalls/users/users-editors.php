<?php
$session = Session::get_user();
$aw = AudiowallSets::get_by_id(pg_escape_string($_REQUEST['setid']));
$sessionpermissions = $aw->get_user_permissions($session->get_id());
if($sessionpermissions[2] == '1' || Session::is_group_user('Audiowalls Admin')){
	$ownerid = DigiplayDB::select("user_id FROM aw_sets_owner WHERE set_id = '".$aw->get_id()."'");
	if (isset($ownerid)) {
		$user = Users::get_by_id($ownerid);
		$username = $user->get_username();
	} else {
		$username = "";
	}
	require_once('pre.php');
	Output::add_script("../aw.js");
	Output::set_title("Audiowall Users");
	MainTemplate::set_subtitle("<span style=\"margin-right:20px;\">Set: ".$aw->get_name()."</span><span style=\"margin-right:20px;\">Owner: ".$username."</span><span id=\"editor_edit_buttons\"><a href=\"#\" class=\"btn btn-success\">Add Editor</a></span>");
	echo("<style type=\"text/css\">
	table { font-size:1.2em; }
	thead { display:none; }
	.description { font-size:0.8em; font-style:italic; }
	.hover-info { display:none; }
	.table tbody tr.success td { background-color: #DFF0D8; }
	</style>");

	echo("<table class=\"table table-striped\" cellspacing=\"0\">
				<thead>
					<tr>
						<th></th>
						<th style=\"width:65px\"></th>
					</tr>
				</thead><tbody>");

	$aw_set = AudiowallSets::get_by_id($_REQUEST['setid']);
	$users = $aw_set->get_users_with_permissions();
	if (!is_null($users) && count($users) > 1){
		foreach ($users as $user) {
			$userclass = Users::get_by_id($user->get_id());
			$username = $userclass->get_username();
			$permissions = $aw_set->get_user_permissions($user->get_id());				
			if($permissions[1] == "1" && $permissions[2] == '0') {
				echo("<tr><td><strong>".$username."</strong></td>");
				echo("<td class=\"delete-aw-btn\" style=\"width:65px\"><a href=\"#\" class=\"btn btn-danger\">Delete</a></td>");
				echo("</td></tr>");
			}
		}
	}
	if (!is_null($users) && count($users) == 1) {
		$userclass = Users::get_by_id($users->get_id());
		$username = $userclass->get_username();
		$permissions = $aw_set->get_user_permissions($users->get_id());					
		if($permissions[1] == "1" && $permissions[2] == '0') {
			echo("<tr><td><strong>".$username."</strong></td>");
			echo("<td class=\"delete-aw-btn\" style=\"width:65px\"><a href=\"#\" class=\"btn btn-danger\">Delete</a></td>");
			echo("</td></tr>");
		}
	}
	echo("</tbody></table>");
	echo("<div id=\"add-user-modal\" class=\"modal fade\">
			<div class=\"modal-dialog\">
    			<div class=\"modal-content\"> 
      				<div class=\"modal-header\">
        				<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
        				<h4 class=\"modal-title\">Add User</h4>
      				</div>
      				<div class=\"modal-body\">
        				<div class=\"row\">
          					<div class=\"col-md-12\">Search for the user you wish to add to editor</div>
          					</div>
          					<div class=\"row\">
          					<div class=\"col-md-12\">
          						<form role=\"form\" class=\"form-horizontal\">
          							<div class=\"form-group\">
            							<label for=\"text\" class=\"col-lg-2 control-label\">Username</label>
            							<div class=\"col-lg-10\">
            								<input class=\"form-control\" id=\"text\" name=\"text\" type=\"text\">
            							</div>
            						</div>
            					</form>
            				</div>
      					</div>
      	      			<div class=\"modal-footer clearfix\">
        					<a href=\"#\" class=\"btn btn-primary\">Yes</a>
        					<a href=\"#\" class=\"btn btn-danger\">No</a>
      					</div>
    				</div>
  				</div>
			</div>
		</div>");
	echo("<script type=\"text/javascript\">
			$('.btn-success').click(function(){
				$('#add-user-modal').modal('show');
			});
			$('#add-user-modal .btn-danger').click(function(){
				$('#add-user-modal').modal('hide');
			});
			$('#add-user-modal .btn-primary').click(function(){
				$.ajax({
					url: '../../ajax/add-user-permissions.php',
					data: { username: $('#text').val().replace(\"'\", \"''\"), setid: ".$_REQUEST['setid'].", val: 'editor' },
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
		</script>");
			echo("<div id=\"delete-user-modal\" class=\"modal fade\">
				<div class=\"modal-dialog\">
    				<div class=\"modal-content\"> 
      					<div class=\"modal-header\">
        					<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
        					<h4 class=\"modal-title\">Delete User</h4>
      					</div>
      					<div class=\"modal-body\">
        					<div class=\"row\">
          						<div class=\"col-md-12\">Are you you sure you want to delete <span id=\"username-to-delete\"></span></div>
          					</div>
      					</div>
      	      			<div class=\"modal-footer clearfix\">
        					<a href=\"#\" class=\"btn btn-primary\">Yes</a>
        					<a href=\"#\" class=\"btn btn-danger\">No</a>
      					</div>
    				</div>
  				</div>
			</div>");
	echo("<script type=\"text/javascript\">
			$('a.btn-danger').click(function(){
				$('#username-to-delete').html($(this).parent().parent().find('strong').html());
				$('#delete-user-modal').modal('show');
			});
			$('#delete-user-modal .btn-danger').click(function(){
				$('#delete-user-modal').modal('hide');
			});
			$('#delete-user-modal .btn-primary').click(function(){
				$.ajax({
					url: '../../ajax/delete-user-permissions.php',
					data: { username: $('#username-to-delete').html(), setid: ".$_REQUEST['setid'].", val: 'editor' },
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
		</script>");
} else {
	header('Location: ../index.php');
}
?>
