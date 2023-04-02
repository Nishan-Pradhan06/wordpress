<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Campaign Admin class
*/
if ( ! class_exists( 'Icegram_Compat_wpforms' ) ) {
	class Icegram_Compat_wpforms extends Icegram_Compat_Base {

		function __construct() {
			global $icegram; 
			parent::__construct();
		}

		function render_js( $type ) {
			global $icegram;
			if( 'wpforms' === $type || 'yes' === $icegram->cache_compatibility ){
			?>
				<script type="text/javascript">
					jQuery(function() {
					  	jQuery( window ).on( "init.icegram", function(e, ig) {
							if(typeof ig !== 'undefined' && typeof ig.messages !== 'undefined' ) {
								jQuery(ig.messages).each(function(i, msg){
									if(this.el.find('form[id^=wpforms]').length > 0 ){
										var form = this.el.find('form');
										this.el.find('input[type="submit"]').addClass('wpforms-submit');
										form.addClass('wpforms-validate wpforms-form');
										if ( form.data( 'token' ) ) {
											jQuery( '<input type="hidden" class="wpforms-token" name="wpforms[token]" />' )
												.val( form.data( 'token' ) )
												.appendTo( form );
										}
									}
								});
							}
						});
					});
				</script>

			<?php
			}
		}		
	}
}