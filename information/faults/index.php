<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
$faults = Faults::get(NULL, Session::get_id());
foreach($faults as $fault){
	$title = "<b>Fault ID: DIGI_".$fault->get_id()." </b><small>Assigned to: ".$fault->get_real_assignedto($fault->get_assignedto())."</small><span class=\"pull-right label label-".$fault->get_panel_class()."\">".$fault->get_real_status()."</span>";
	$footer = "<a data-toggle=\"modal\" href=\"#add-comment\" class=\"btn btn-primary btn-xs new-comment\" data-dps-id=".$fault->get_id().">Add Comment</a>";
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
echo( "<script>
	$('.new-comment').click(function() {
		$('#comment-status-title').html('Add a comment to the fault DIGI_');
		$('#comment-status-title').append($(this).attr('data-dps-id'));
		$('.fault-comment-id').val($(this).attr('data-dps-id'));
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
</script>" );

?>