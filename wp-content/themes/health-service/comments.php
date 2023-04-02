<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @subpackage health-service
 * @since health-service 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
*/
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( post_password_required() ) {
	return;
}
?>
<div class="comments-area">
	<?php
	if ( have_comments() ) :
		?>
		<h4 class="title-sep2 mb-30">
			<?php
			$health_service_comment_count = get_comments_number();
			if ( '1' === $health_service_comment_count ) {
				printf( 
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'health-service' ),
					'<span>' . esc_html(get_the_title()) . '</span>'
				);
			} else {
				printf( // WPCS: XSS OK.
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $health_service_comment_count, 'comments title', 'health-service' ) ),
					number_format_i18n( $health_service_comment_count ),
					'<span>' . esc_html(get_the_title()) . '</span>'
				);
			}
			?>
		</h4> 

		<?php the_comments_navigation(); ?>

		
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		<?php
		the_comments_navigation();
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'health-service' ); ?></p>
			<?php
		endif;
	endif;  
	comment_form();
	?>
</div> 