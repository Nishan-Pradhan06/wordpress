<?php
/**
 * Template part for displaying single posts content.
 *
 *
 * @subpackage health-service
 * @since 1.0 
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="blog-detail">
    <?php if(has_post_thumbnail()):?>
    <?php health_service_post_thumbnail(); ?>
    <?php endif; ?>
     <div class="row mb-2">
            <ul class="post-meta text-left">
                <?php health_service_posted_by();
             health_service_post_comments(); 
             health_service_posted_on();
                             ?>
            </ul>
        
    </div>
    <h4 class="text-capitalize"><?php the_title(); ?></h4>
  		<?php
		the_content( sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'health-service' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
							get_the_title()

		) );

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'health-service' ),
			'after'  => '</div>',
		) );
		?>	
		    <?php if (has_tag()) : ?>
    <div class="post-tags mt-4">
        <span class="text-capitalize mr-2 c-black">
            <i class="fa fa-tags"></i><?php echo esc_html__('Tags :','health-service'); ?></span>
        <?php the_tags('&nbsp;'); ?>
    </div>
    <?php endif; ?> 
</div>
</div>