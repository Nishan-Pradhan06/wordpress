jQuery( document ).ready(function( $ ) {
  
  var cookie_name = pp_admin_cookie_prefix + '_current_tab';
  
  $( '.tab-navigation .tabset' ).click( function() {
    
    if ( $( this ).not( '.current' ) ) {
    
      $( '.tab-panel .panel.current' ).removeClass( 'current ');
      $( '#' + $(this).data('tab-content') ).addClass( 'current' );
      
      $( '.tab-navigation .tabset.current' ).removeClass( 'current ');
      $( this ).addClass( 'current' );
      
      $.cookie( cookie_name, $( '.tab-navigation .tabset.current' ).attr('id') );
    
    }
    
  });
  
  
  var current_tab = jQuery.cookie( cookie_name );
  if ( current_tab === undefined ) {
    current_tab = $( '.tab-navigation .tabset:first' ).attr('id');
    $.removeCookie( cookie_name );
  }
  $( '#' + current_tab ).trigger( 'click' );
  
  
});