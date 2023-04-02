<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @subpackage health-service
 * @since 1.0 
 */

if ( ! function_exists( 'health_service_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function health_service_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html( '%s', 'post date', 'health-service' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<li><i class="fa fa-clock-o"></i>' . $posted_on . '</li>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'health_service_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function health_service_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html( '%s', 'post author', 'health-service' ),
			'<li><i class="fa fa-user"></i><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></li>'
		);

		echo $byline; // WPCS: XSS OK.

	}
endif;


if ( ! function_exists( 'health_service_post_comments' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function health_service_post_comments() {

		// Get the author name; wrap it in a link.
		// $post_comment = sprintf(.

		/**
		 *
		 * Translators: %s: post author */
		?><li><i class="fa fa-comments"></i><?php comments_popup_link( 'No comment yet', '1 Comment', '% Comments' ); ?></li>
		<?php
	}
endif;



if ( ! function_exists( 'health_service_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function health_service_entry_footer() {
		// Hide category and tag text for pages.
		

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<i class="fa fa-comments-o"></i><span class="comments-link">';
			comments_popup_link(
				sprintf(
				
					get_the_title()
				)
			);
			echo '</span>';
		}

	
	}
endif;

if ( ! function_exists( 'health_service_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function health_service_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/

if( ! function_exists( 'health_service_fontawesome_social_icons_lists' ) ) :
	/**
     * Font Awesome
     *
     * @param string $file_path font awesome css file path
     * @param string $class_prefix change this if the class names does not start with `fa-`
     * @return array
     */

	function health_service_fontawesome_social_icons_lists() {

		$social_icons_array = array( 'facebook-square', 'facebook', 'facebook-official', 'twitter-square', 'twitter', 'github', 'behance', 'behance-square', 'whatsapp', 'qq', 'wechat', 'weixin', 'tumblr', 'tumblr-square', 'instagram', 'google-plus-circle', 'google-plus-official', 'google-plus-square', 'dribbble', 'skype', 'snapchat', 'snapchat-ghost', 'snapchat-square', 'pinterest', 'pinterest-square', 'pinterest-p', 'linkedin-square', 'linkedin', 'reddit', 'reddit-square', 'youtube-square', 'youtube', 'youtube-play', 'yelp' );

		foreach ( $social_icons_array as $icon ) {
			$icon_name = ucfirst( str_ireplace( array( '-' ), array( ' ' ), $icon ) );
			$font_awesome_icons[esc_attr( $icon )] = esc_html( $icon_name );
		}
		return $font_awesome_icons;
	}
endif;