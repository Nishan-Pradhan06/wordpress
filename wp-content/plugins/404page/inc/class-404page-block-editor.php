<?php

/**
 * The 404page block editor plugin class
 *
 * @since  9
 * partially rewritten in 11.4.0, because it stopped working
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The block editor plugin class
 */
if ( !class_exists( 'PP_404Page_BlockEditor' ) ) {
  
  class PP_404Page_BlockEditor extends PPF08_SubClass {  
    
    /**
	   * Do Init
     *
     * @since 9
     * @access public
     */
    public function init() {

      add_action( 'admin_head', array( $this, 'admin_style' ) );
    
    }
    
    
    /**
	   * Add Block Editor Style to Header if currently edited page is a custom 404 error page
     *
     * @since 9
     * @access public
     */
    public function admin_style() {
      
      if ( $this->is_gutenberg_editing() ) {
      
        ?>
        <style type="text/css">
          .edit-post-layout__content:before { content: "<?php esc_html_e( 'You are currently editing your custom 404 error page', '404page'); ?>"; background-color: #333; color: #FFF; padding: 8px; font-size: 16px; display: block };
        </style>
        <?php /* since 11.4.0 */ ?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				var checkExist = setInterval( function() {
					if ( $( '.edit-post-header-toolbar' ).length ) {               
						$( '.edit-post-header-toolbar' ).prepend( '<div style="margin-<?php echo ( is_rtl() ? 'right' : 'left' ); ?>: 24px; height: 32px; line-height: 32px; padding: 0 12px; background-color: #000; color: #fff">404</div>' );
						clearInterval(checkExist);
					}
				}, 100);

			} );
		</script>
		<?php
        
      }
      
		}
    
    
    /**
	   * Is the 404 page edited in gutenberg editor?
     *
     * @since 9
     * @access private
     */
    private function is_gutenberg_editing() {
      
      // Is the current screen the page edit screen and is a custom 404 error page defined?
      if ( get_current_screen()->id == 'page' && $this->settings()->get( 'page_id' ) > 0 ) {
        
        // Is the block editor active for pages and is the classic editor not loaded?
        if ( function_exists( 'use_block_editor_for_post_type' ) && use_block_editor_for_post_type( 'page' ) && ! isset( $_GET['classic-editor'] ) ) {
        
          global $post;
        
          $all404pages = $this->core()->get_all_page_ids();
        
          // Is the currently edited page a custom 404 error page?
          if ( in_array( $post->ID, $all404pages  ) ) {
      
            return true;
            
          }
          
        }
        
      }
      
      return false;
      
    }

  }
  
}

?>