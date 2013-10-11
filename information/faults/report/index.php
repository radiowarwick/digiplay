<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
echo(
	"<h3>Report a Fault</h3>
	<p>You the form below to report a existing DIGIPLAY fault</p>
	<form role=\"form\">
	  <div class=\"form-group\">
	    <label for=\"exampleInputEmail1\">Your name:</label>
	    <input type=\"email\" class=\"form-control\" id=\"exampleInputEmail1\" placeholder=\"Enter email\" value=\"".Session::get_name()."\" readonly>
	  </div>
	  <div class=\"form-group\">
	    <textarea class=\"form-control\" rows=\"3\"></textarea>
	  </div>
	  <button type=\"submit\" class=\"btn btn-default\">Submit</button>
	</form>
"
	); 
?>