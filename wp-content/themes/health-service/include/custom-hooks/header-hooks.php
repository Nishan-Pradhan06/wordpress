<?php
/**
 * Managed the custom functions and hooks for header section of the theme.
 *
 * @subpackage health-service
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/
if( ! function_exists( 'health_service_header_start' ) ):
    
    function health_service_header_start(){
		?>
	<header>
	<?php 
	$enable_topheader_socialmedia = get_theme_mod( 'enable_topheader_socialmedia', true );
		if($enable_topheader_socialmedia==true ) {
			$enable_topheader_facebook = get_theme_mod( 'enable_topheader_facebook');
			$enable_topheader_twitter =  get_theme_mod( 'enable_topheader_twitter');
			$enable_topheader_instagram = get_theme_mod( 'enable_topheader_instagram');
			$enable_topheader_linkedin = get_theme_mod( 'enable_topheader_linkedin');
			$enable_topheader_yt = get_theme_mod( 'enable_topheader_yt');
			$enable_topheader_contact_no = get_theme_mod( 'enable_topheader_contact_no');
			$enable_topheader_mail = get_theme_mod( 'enable_topheader_mail');
			$enable_topheader_address = get_theme_mod( 'enable_topheader_address');
			
		
			?>
			<div class="top-bar">
			  <div class="container">
				<div class="row align-item-center">
				  <div class="col-md-3">
					<ul class="social-icon">
					  <?php if($enable_topheader_facebook) : ?><li class="icon-list"><a href="<?php echo esc_url($enable_topheader_facebook)?>"><i class="fa fa-facebook"></i></a></li><?php endif; ?>
					  <?php if($enable_topheader_twitter) : ?><li class="icon-list"><a href="<?php echo esc_url($enable_topheader_twitter)?>"><i class="fa fa-twitter"></i></a></li><?php endif; ?>
					  <?php if($enable_topheader_instagram) : ?><li class="icon-list"><a href="<?php echo esc_url($enable_topheader_instagram)?>"><i class="fa fa-instagram"></i></a></li><?php endif; ?>
					  <?php if($enable_topheader_linkedin) : ?><li class="icon-list"><a href="<?php echo esc_url($enable_topheader_linkedin)?>"><i class="fa fa-linkedin"></i></a></li><?php endif; ?>
					  <?php if($enable_topheader_yt) : ?><li class="icon-list"><a href="<?php echo esc_url($enable_topheader_yt)?>"><i class="fa fa-youtube"></i></a></li><?php endif; ?>
					</ul>
				  </div>
				  <div class="col-md-9 d-flex justify-content-end">
				   <?php if($enable_topheader_contact_no) : ?>
						<div class="content">
						  <div class="icon">
							<i class="fa fa-phone"></i>
						  </div>
						  <div class="details">
							<ul>
							  <li><p class="light"><?php echo esc_html($enable_topheader_contact_no); ?></p></li>
							</ul>
						  </div>
						</div>
					<?php endif; ?>	
					<?php if($enable_topheader_mail) : ?>
						<div class="content">
						  <div class="icon">
							<i class="fa fa-envelope"></i>
						  </div>
						  <div class="details">
							<ul>
							  <li><p class="light"><?php echo esc_html($enable_topheader_mail); ?></p></li>
							</ul>
						  </div>
						</div>
					<?php endif; ?>
					<?php if($enable_topheader_address) : ?>
						<div class="content">
						  <div class="icon">
							<i class="fa fa-map-marker"></i>
						  </div>
						  <div class="details">
							<ul>
							  <li><p class="light"><?php echo esc_html($enable_topheader_address); ?></p></li>
							</ul>
						  </div>
						</div>
					<?php endif; ?>	
				  </div>
				</div>
			  </div>
			</div>
	<?php } ?>
	
        <div class="header-two affix">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="menu-two">
<?php }
endif;  

/*-----------------------------------------------------------------------------------------------------------------------*/
if( ! function_exists( 'health_service_header_site_branding' ) ):
   
    function health_service_header_site_branding(){ ?>
        
            <div class="logo-wrap">
                <div class="logo">
                <?php the_custom_logo();   
                 if (display_header_text()==true){ ?>
                 <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                 <h1 class="site-title">
                 <?php bloginfo( 'title' ); ?>
                 </h1>
                   <p class="site-description">
                 <?php bloginfo( 'description' ); ?>
                 </p>
                 </a>
                 <?php } ?>
            </div>
        </div>
    

    <?php
    }
endif;  

/*-----------------------------------------------------------------------------------------------------------------------*/

if( ! function_exists( 'health_service_header_nav_menu' ) ):
    
    function health_service_header_nav_menu(){ ?>
        
            <nav class="main-navigation navbar navbar-expand-lg" id="site-navigation">
                 <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="fa fa-bars"></i></button>
                 <?php
                    wp_nav_menu(array(
						'theme_location'      => 'primary',
						'container'           => 'div',
						'container_class'     => 'main-menu',
						'menu_class'          => 'navbar-nav mr-auto',
						'menu_id'             => 'nav-content',
					)) ;
                    ?>
            </nav>
        </div>
        
<?php }
endif;  
if( ! function_exists( 'health_service_header_end' ) ):
      function health_service_header_end(){ ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

<?php }


endif;  


add_action( 'health_service_header', 'health_service_header_start', 5  );
add_action( 'health_service_header', 'health_service_header_site_branding', 10  );
add_action( 'health_service_header', 'health_service_header_nav_menu', 15  );
add_action( 'health_service_header', 'health_service_header_end', 25  );