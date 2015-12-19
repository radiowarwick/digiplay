<?php

Output::set_title("Advert Manager");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");
Output::add_stylesheet(LINK_ABS."css/select2.min.css");
Output::add_script(LINK_ABS."js/select2.min.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Change the adverts on the sustainer service");

$adverts = Adverts::get_all();



echo("<div class=\"list-group\">");

  foreach ($adverts as $advert) {
  	if($advert->get_sustainer() == 't') echo("<div class=\"list-group-item active\" data-advert=\"".$advert->get_id()."\">".$advert->get_title()."</div>");
    else echo("<div class=\"list-group-item\" data-advert=\"".$advert->get_id()."\">".$advert->get_title()."</div>");
  }
 

echo("</div>");

echo("<p></p>");

echo("<script>
  $('.list-group-item').click(function(){
    $(this).toggleClass(\"active\");
    $.ajax({
      url: '".LINK_ABS."ajax/update-sustainer-advert.php',
      data: {advertid: $(this).data(\"advert\")},
      type: 'POST',
      error: function(xhr, text, error) {
        value = $.parseJSON(xhr.responseText);
        alert(value.error);
      },
      success: function(data, text, xhr) {
        window.location.reload(true);
      }
    });
  });
  </script>");
?>