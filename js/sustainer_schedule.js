$( document ).ready(function() {

    $( '.timeslot' ).click(function() {

      //console.log($(this).attr('id'));

      // Update colours for the table
      $(this).css('background-color', '#'+$('#genre-selector option:selected').data('colour'));

      //console.log( '#field-' + $(this).attr('id') );

      // Update corresponding hidden values
      $( '#field-' + $(this).attr('id') ).val( $('#genre-selector').val() );
    
    });

});