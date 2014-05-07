<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("info","1.0.4","Current Version:", false);
$faults = Faults::get(NULL);
foreach($faults as $fault){
	$title = "<b>Fault ID: DIGI_".$fault->get_id()." </b><small>Assigned to: ".$fault->get_real_assignedto($fault->get_assignedto())."</small><span class=\"pull-right label label-".$fault->get_panel_class()."\">".$fault->get_real_status()."</span>";
	$footer = "<a data-toggle=\"modal\" href=\"#add-comment\" class=\"btn btn-primary btn-xs new-comment\" data-dps-id=".$fault->get_id().">Add Comment</a> 
	<a data-toggle=\"modal\" href=\"#update-status\" class=\"btn btn-success btn-xs change-status\" data-dps-id=".$fault->get_id().">Change Status</a> 
	<a data-toggle=\"modal\" href=\"#assign-fault\" class=\"btn btn-warning btn-xs assign-fault\" data-dps-id=".$fault->get_id().">Assign Fault</a> 
	<a data-toggle=\"modal\" href=\"#delete-fault\" class=\"btn btn-danger btn-xs delete-fault\" data-dps-id=".$fault->get_id().">Delete</a> ";
	if (Comments::get_fault_comments($fault->get_id()) != 0) {
		$footer .= "<span class=\"pull-right\"><a class=\"accordion-toggle\" data-toggle=\"collapse\" href=\"#collapse-".$fault->get_id()."\">".Bootstrap::glyphicon("plus")."</a></span></div><div id=\"collapse-".$fault->get_id()."\" class=\"panel-collapse collapse\"><div class=\"panel-body\">";
		$comments = Comments::get_by_fault($fault->get_id());
		foreach($comments as $comment){
			if ($comment->get_author() == -1) {
				$footer .= "<div class=\"row\">
			  		<div class=\"col-md-10\">".Bootstrap::alert_message_basic("warning",NULL,"SYSTEM: ".$comment->get_comment(), false)."</div>
			  		<div class=\"col-md-2\">".$comment->get_postdate()."</div>
		    	</div>";
			} else if ($comment->get_author() == $fault->get_author()) {
				$footer .= "<div class=\"row\">
					<div class=\"col-md-6\">".Bootstrap::alert_message_basic("info",$comment->get_comment(),$comment->get_author().":<br>", false)."</div>
			  		<div class=\"col-md-4\">&nbsp;</div>
			  		<div class=\"col-md-2\">".$comment->get_postdate()."</div>
		    	</div>";
	    	} else {
	    		$footer .= "<div class=\"row\">
					<div class=\"col-md-4\">&nbsp;</div>
					<div class=\"col-md-6\">".Bootstrap::alert_message_basic("success",$comment->get_comment(),$comment->get_author().":<br>", false)."</div>
					<div class=\"col-md-2\">".$comment->get_postdate()."</div>
		    	</div>";
	    	}
		}
		$footer .= "</div>";
	}
	$body = "<p><i>Submitted by: <b>".$fault->get_real_author($fault->get_author())."</b> on: <b>".$fault->get_postdate()."</b></i><hr></p>
	<p>".$fault->get_content()."</p>";
	echo( Bootstrap::panel($fault->get_panel_class(), $body, $title, $footer) );
}
$title = "<span id=\"comment-status-title\">Add a comment to the fault DIGI_</span>";
$body = "<form role=\"form\">
  <div class=\"form-group\">
  	<input type=\"hidden\" class=\"fault-comment-id\">
    <textarea class=\"form-control fault-comment-value\" rows=\"3\"></textarea>
  </div>
  <div class=\"form-group\">
  <button type=\"submit\" class=\"btn btn-primary confirm-fault-comment\">Add Comment</button>
  <a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>
  </div>
</form>";
echo( Bootstrap::modal("add-comment", $body, $title) );
$title = "<span id=\"update-status-title\">Change the status of fault DIGI_</span>";
$body = "<form role=\"form\">
  <div class=\"form-group\">
  	<input type=\"hidden\" class=\"fault-update-id\">
    <select class=\"form-control fault-update-value\" name=\"status\">
	  <option value=\"1\">Not yet read</option>
	  <option value=\"2\">On hold</option>
	  <option value=\"3\">Work in progress</option>
	  <option value=\"4\">Fault complete</option>
	</select>
  </div>
  <div class=\"form-group\">
  <button type=\"submit\" class=\"btn btn-success confirm-fault-update\">Change Status</button>
  <a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>
  </div>
</form>";
echo( Bootstrap::modal("update-status", $body, $title) );
$title = "<span id=\"assign-status-title\">Assign fault DIGI_</span>";
$body = "<form role=\"form\">
  <div class=\"form-group\">
  	<input type=\"hidden\" class=\"fault-assign-id\">
    <select class=\"form-control fault-assign-value\" name=\"assign\">";
$group = Groups::get_by_name("Developers");
$developers = $group->get_users();
foreach($developers as $developer) {
	$user = Users::get_by_id($developer->get_id());
	$user_information = $user->get_ldap_attributes();
	$user_fullname = $user_information['first_name']." ".$user_information['surname'];
	$body .= "<option value=".$developer->get_id().">".$user_fullname."</option>";
}
$body .= "</select>
  </div>
  <div class=\"form-group\">
  <button type=\"submit\" class=\"btn btn-warning confirm-fault-assign\">Assign Fault</button>
  <a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>
  </div>
</form>";
echo( Bootstrap::modal("assign-fault", $body, $title) );
$title = "<span id=\"delete-status-title\">Are you sure you want to delete fault DIGI_</span>";
$body = "<input type=\"hidden\" class=\"fault-delete-id\">
		<a href=\"#\" class=\"btn btn-danger confirm-fault-delete\">Delete</a> 
		<a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>";
echo( Bootstrap::modal("delete-fault", $body, $title) );
echo( "<script>
	$('.new-comment').click(function() {
		$('#comment-status-title').html('Add a comment to the fault DIGI_');
		$('#comment-status-title').append($(this).attr('data-dps-id'));
		$('.fault-comment-id').val($(this).attr('data-dps-id'));
	});
	$('.change-status').click(function() {
		$('#update-status-title').html('Change the status of fault DIGI_');
		$('#update-status-title').append($(this).attr('data-dps-id'));
		$('.fault-update-id').val($(this).attr('data-dps-id'));
	});
	$('.delete-fault').click(function() {
		$('#delete-status-title').html('Are you sure you want to delete fault DIGI_');
		$('#delete-status-title').append($(this).attr('data-dps-id'));
		$('.fault-delete-id').val($(this).attr('data-dps-id'));
	});
	$('.assign-fault').click(function() {
		$('#assign-status-title').html('Assign fault DIGI_');
		$('#assign-status-title').append($(this).attr('data-dps-id'));
		$('.fault-assign-id').val($(this).attr('data-dps-id'));
	});
	$('.confirm-fault-comment').click(function() {
		$.ajax({
			url: '".LINK_ABS."ajax/add-update-comment.php',
			data: 'faultid='+$('.fault-comment-id').val()+'&comment='+$('.fault-comment-value').val(),
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
	$('.confirm-fault-update').click(function() {
		$.ajax({
			url: '".LINK_ABS."ajax/fault-admin.php',
			data: 'action=update-status&id='+$('.fault-update-id').val()+'&status='+$('.fault-update-value').val(),
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
	$('.confirm-fault-delete').click(function() {
		$.ajax({
			url: '".LINK_ABS."ajax/fault-admin.php',
			data: 'action=del-fault&id='+$('.fault-delete-id').val(),
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
	$('.confirm-fault-assign').click(function() {
		$.ajax({
			url: '".LINK_ABS."ajax/fault-admin.php',
			data: 'action=assign-fault&id='+$('.fault-assign-id').val()+'&assign='+$('.fault-assign-value').val(),
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
</script>" );
?>