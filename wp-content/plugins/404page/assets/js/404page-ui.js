jQuery( document ).ready( function( $ ) {
	
  $( '#select404page' ).change( function() {
    $( '#edit_404_page, #test_404_page' ).prop( 'disabled', !( $( '#select404page' ).val() == $( '#404page_current_value').text() != 0 ) );
  });
  
  $( '#select404page' ).trigger( 'change' );
  
  $( '#edit_404_page' ).click( function() {
    window.location.href = $( '#404page_edit_link' ).text();
  });
  
  $( '#test_404_page' ).click( function() {
    window.location.href = $( '#404page_test_link' ).text();
  });
  
  $( '#404page-http410_always' ).change( function() {
    $( '#404page-http410_if_trashed' ).prop( 'disabled', $( '#404page-http410_always' ).prop( 'checked' ) );
  });
  
});