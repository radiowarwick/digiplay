$( document ).ready(function() {

    $( '.timeslot' ).click(function() {

      // Update colours for the table
      $(this).css('background-color', '#'+$('#genre-selector option:selected').data('colour'));

      // Update corresponding hidden values
      $( '#field-' + $(this).attr('id') ).val( $('#genre-selector').val() );
    
    });

    $("select.prerecord-id").keyup(function(){
		var value=this.value;
		var searchbox = this;
    		setTimeout(function(){
        		if (searchbox.value == value) {
	                	if(searchbox.value.length < 3) {
     					$("ul#quick-search").slideUp(200,function(){
        					return false;
					});
	   			} else {
     					$.ajax({
        					type: "GET",
        					url: site_root+"/ajax/prerecord-search.php?q="+searchbox.value,
        					dataType: "json",
        					success: function(data){
          						if(data.length < 1){
        							$("ul#quick-search").slideUp(200);
        						} else {
            							var output_html = '';
            							$.each(data, function(i, val) {
            								output_html += '<li class="dropdown-header">'+val.title+'</li>';
              								$.each(val.data, function(i, data) {
              									if(typeof(data.by) == "undefined") {
              										output_html += '<li><a href="'+data.href+'"><strong>'+data.title+'</strong></a></li>'
        	      								} else {
	              									output_html += '<li><a href="'+data.href+'"><strong>'+data.title+'</strong> by '+data.by+'</a></li>'
              									}
              								});
									output_html += '<li class="full-search"><a href="'+val.href+'">Full Search...</a></li>';
            							});
								
            							$("ul#quick-search").html(output_html);
            							$("ul#quick-search").slideDown(200);
							}
        					}
      					});
				}
    			}
    		},250);
  	});
	$('#quick-search').click(function (e) {
		e.stopPropagation();
	});
	$('input.search-query').click(function (e) {
		e.stopPropagation();
		if($('ul#quick-search').children().length > 0) {
			$('ul#quick-search').slideDown(200);
		}
	});
	$(document).click(function() {
		$('#quick-search').slideUp(200);
	});

});