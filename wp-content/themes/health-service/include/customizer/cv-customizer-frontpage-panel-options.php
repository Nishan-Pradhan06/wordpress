<?php
/**
 * health-service manage the Customizer options of frontpage panel.
 *
 * @subpackage health-service
 * @since 1.0 
 */

// Toggle field for Enable/Disable banner content
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_slider_section',
		'label'    => __( 'Enable Home Page Slider', 'health-service' ),
		'section'  => 'health_service_section_slider_content',
		'default'  => '0',
		'priority' => 5,
	)
);

Kirki::add_field( 'health_service_config', [
	'type'        => 'checkbox',
	'settings'    => 'enable_dark_header',
	'label'       => esc_html__( 'Enable Title/ Description Dark Mode', 'kirki' ),
	'section'     => 'health_service_section_slider_content',
	'default'     => false,
	'priority' => 5,
] );

for($k=1;$k<=3;$k++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'health_service_slider_page'.$k,
		'label'       => 'Select Slider Page '.$k,
		'section'     => 'health_service_section_slider_content',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}

for($k=1;$k<=3;$k++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'text',
		'settings'    => 'health_service_slider_page_btn_txt_'.$k,
		'label'       => 'First Button Text of Slide -'.$k,
		'section'     => 'health_service_section_slider_content',
		'default'     => "",
		'priority'    => 11,
		
	)
);
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'text',
		'settings'    => 'health_service_slider_page_btn_url_'.$k,
		'label'       => 'First Button URL of Slide -'.$k,
		'section'     => 'health_service_section_slider_content',
		'default'     => "",
		'priority'    => 11,
		
	)
);
}

for($k=1;$k<=3;$k++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'text',
		'settings'    => 'health_service_slider_page_second_btn_txt_'.$k,
		'label'       => 'Second Button Text of Slide -'.$k,
		'section'     => 'health_service_section_slider_content',
		'default'     => "",
		'priority'    => 11,
		
	)
);
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'text',
		'settings'    => 'health_service_slider_page_second_btn_url_'.$k,
		'label'       => 'Second Button URL of Slide -'.$k,
		'section'     => 'health_service_section_slider_content',
		'default'     => "",
		'priority'    => 11,
		
	)
);
}



// Toggle field for Enable/Disable Features Section
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_features_section',
		'label'    => __( 'Enable Home Features Area', 'health-service' ),
		'section'  => 'health_service_section_features',
		'default'  => '0',
		'priority' => 4,
	)
);

for($i=1;$i<=3;$i++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'health_service_features_page '.$i,
		'label'       => 'Select Features Page '.$i,
		'section'     => 'health_service_section_features',
		'default'     => 0,
		'priority'    => '7',
		
	)
);

	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'text',
		'settings'    => 'health_service_features_icon '.$i,
		'label'       => 'Select Features Icon '.$i,
		'description' =>  __('Select font awesome icons <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Click Here</a> for select icon','health-service'),
		'section'     => 'health_service_section_features',
		'default'     => 'fa fa-user',
		'priority'    => '7',
		
	)
);
}


// Toggle field for Enable/Disable About Us Section
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_about_us_section',
		'label'    => __( 'Enable Home About Area', 'health-service' ),
		'section'  => 'health_service_section_about_us',
		'default'  => '0',
		'priority' => 5,
	)
);

// Text field for About Us subtitle  
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_about_title',
		'label'    => __( 'About Us Title', 'health-service' ),
		'section'  => 'health_service_section_about_us',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_about_us_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);


// Text field for About Us subtitle  
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_about_subtitle',
		'label'    => __( 'About Us Sub Title', 'health-service' ),
		'section'  => 'health_service_section_about_us',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_about_us_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Dropdown pages field for about us section
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'health_service_about_page',
		'label'       => __( 'Select Page', 'health-service' ),
		'section'     => 'health_service_section_about_us',
		'default'     => 0,
		'priority'    => 10,
		
	)
);
 
 
// Toggle field for Enable/Disable Service Section
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_service_section',
		'label'    => __( 'Enable Home Service Area', 'health-service' ),
		'section'  => 'health_service_section_services',
		'default'  => '0',
		'priority' => 5,
	)
);

// Text field for Service section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_service_title',
		'label'    => __( 'Service Title', 'health-service' ),
		'section'  => 'health_service_section_services',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_service_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Service section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_service_subtitle',
		'label'    => __( 'Service Sub Title', 'health-service' ),
		'section'  => 'health_service_section_services',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_service_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

for($i=1;$i<=6;$i++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'health_service_service_page '.$i,
		'label'       => 'Select Service Page '.$i,
		'section'     => 'health_service_section_services',
		'default'     => 0,
		'priority'    => '7',
		
	)
);

	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'text',
		'settings'    => 'health_service_service_icon '.$i,
		'label'       => 'Select Service Icon '.$i,
		'description' =>  __('Select font awesome icons <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Click Here</a> for select icon','health-service'),
		'section'     => 'health_service_section_services',
		'default'     => 'fa fa-user',
		'priority'    => '7',
		
	)
);
}


// Toggle field for Enable/Disable Portfolio Section
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_portfolio_section',
		'label'    => __( 'Enable Home Portfolio Area', 'health-service' ),
		'section'  => 'health_service_section_portfolio',
		'default'  => '0',
		'priority' => 5,
	)
);

// Text field for Service section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_portfolio_title',
		'label'    => __( 'Portfolio Title', 'health-service' ),
		'section'  => 'health_service_section_portfolio',
		'default'  =>'',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_portfolio_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Service section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_portfolio_subtitle',
		'label'    => __( 'Portfolio Sub Title', 'health-service' ),
		'section'  => 'health_service_section_portfolio',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_portfolio_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

for($k=1;$k<=6;$k++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'health_service_portfolio_page'.$k,
		'label'       =>  'Select Portfolio Page '.$k,
		'section'     => 'health_service_section_portfolio',
		'default'     => 0,
		'priority'    => 5,
		
	)
);
}

// Toggle field for Enable/Disable Team Section
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_team_section',
		'label'    => __( 'Enable Home Team Area', 'health-service' ),
		'section'  => 'health_service_section_team',
		'default'  => '0',
		'priority' => 6,
	)
);


// Text field for Team section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_team_title',
		'label'    => __( 'Team Title', 'health-service' ),
		'section'  => 'health_service_section_team',
		'default'  => '',	
		'priority' => 6,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_team_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Team section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_team_subtitle',
		'label'    => __( 'Team Sub Title', 'health-service' ),
		'section'  => 'health_service_section_team',
		'default'  => '',	
		'priority' => 6,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_team_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

for($k=1;$k<=4;$k++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'health_service_team_page'.$k,
		'label'       => 'Select Team Page'.$k,
		'section'     => 'health_service_section_team',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_blog_section',
		'label'    => __( 'Enable Home Blog Area', 'health-service' ),
		'section'  => 'health_service_section_blog',
		'default'  => '1',
		'priority' => 5,
	)
);


Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_blog_section',
		'label'    => __( 'Enable Home Blog Area', 'health-service' ),
		'section'  => 'health_service_section_blog',
		'default'  => '1',
		'priority' => 5,
	)
);

// Text field for blog section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_blog_title',
		'label'    => __( 'Top Title', 'health-service' ),
		'section'  => 'health_service_section_blog',
		'default'  => '',	
		'priority' => 10,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_blog_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_blog_subtitle',
		'label'    => __( 'Sub Title', 'health-service' ),
		'section'  => 'health_service_section_blog',
		'default'  => '',	
		'priority' => 10,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_blog_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Select field for blog section categories.
Kirki::add_field(
	'health_service_config', array(
		'type'        => 'select',
		'settings'    => 'health_service_blog_cat',
		'label'       => esc_attr__( 'Select Category', 'health-service' ),
		'section'     => 'health_service_section_blog',
		'default'     => 'Uncategorized',
		'priority'    => 15,
		'choices'     => health_service_select_categories_list(),
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_blog_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Blog button label
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_rm_button_label',
		'label'    => __( 'Read More Text', 'health-service' ),
		'default'  => '',
		'section'  => 'health_service_section_blog',
		'priority' => 25,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_blog_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Toggle field for Enable/Disable callout content second
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_callout_section',
		'label'    => __( 'Enable Home Page Callout', 'health-service' ),
		'section'  => 'health_service_section_callout_content',
		'default'  => '0',
		'priority' => 5,
	)
);
 
// Text field for callout title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_callout_title',
		'label'    => __( 'Callout Title', 'health-service' ),
		'section'  => 'health_service_section_callout_content',
        'default'  => '',
		'priority' => 15,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Textarea field for callout content
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'textarea',
		'settings' => 'health_service_callout_content',
		'label'    => __( 'Callout Text', 'health-service' ),
		'section'  => 'health_service_section_callout_content',
        'default'  => '',
		'priority' => 20,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for callout content button label
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_callout_button_label1',
		'label'    => __( 'Callout Button Text', 'health-service' ),
		'default'  => '',
		'section'  => 'health_service_section_callout_content',
		'priority' => 25,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Link field for callout content button link
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_callout_button_link1',
		'label'    => __( 'Callout Button URL', 'health-service' ),
		'default'  => '',
		'section'  => 'health_service_section_callout_content',
		'priority' => 30,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'techup_config', array(
		'type'        => 'image',
		'settings'    => 'health_service_callout_image',
		'label'       => esc_attr__( 'Callout Background Image', 'health-service' ),
		'section'     => 'health_service_section_callout_content',
		'default'     => esc_url(  get_template_directory_uri() . '/assets/images/banner.jpg' ),
		'priority' 	  => 10,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Toggle field for Enable/Disable Tesimonial Section
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_testimonial_section',
		'label'    => __( 'Enable Home Tesimonial', 'health-service' ),
		'section'  => 'health_service_section_testimonial',
		'default'  => '0',
		'priority' => 5,
	)
);

// Text field for Tesimonial section title
Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_testimonial_title',
		'label'    => __( 'Tesimonial Title', 'health-service' ),
		'section'  => 'health_service_section_testimonial',
		'default'  =>'',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_testimonial_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'health_service_testimonial_subtitle',
		'label'    => __( 'Tesimonial Sub Title', 'health-service' ),
		'section'  => 'health_service_section_testimonial',
		'default'  =>'',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'health_service_enable_testimonial_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);
 

for($k=1;$k<=6;$k++){
	Kirki::add_field(
	'health_service_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'health_service_testimonial_page'.$k,
		'label'       =>  'Select Tesimonial Page '.$k,
		'section'     => 'health_service_section_testimonial',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}

/* Top Header Details */

 

Kirki::add_field( 'health_service_config', [
	'type'        => 'toggle',
	'settings'    => 'enable_topheader_socialmedia',
	'label'       => esc_html__( 'Enable Top Header Social Icons', 'kirki' ),
	'section'     => 'health_service_section_site_header',
	'priority' => 5,
	'default'     => false,
] ); 


 

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_facebook',
		'label'    => __( 'Facebook Page URL', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_twitter',
		'label'    => __( 'Twitter Page URL', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_instagram',
		'label'    => __( 'Instagram Page URL', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_yt',
		'label'    => __( 'YouTube Page URL', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_linkedin',
		'label'    => __( 'Linkedin Page URL', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_contact_no',
		'label'    => __( 'Contact Number', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_mail',
		'label'    => __( 'Mail ID', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

Kirki::add_field(
	'health_service_config', array(
		'type'     => 'text',
		'settings' => 'enable_topheader_address',
		'label'    => __( 'Address', 'health-service' ),
		'section'  => 'health_service_section_site_header',
		'default'  => '',
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'enable_topheader_socialmedia',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);
 

/* Sticky Header Settings */

Kirki::add_field( 'health_service_config', [
	'type'        => 'checkbox',
	'settings'    => 'enable_sticy_header',
	'label'       => esc_html__( 'Enable Sticy Header', 'kirki' ),
	'section'     => 'health_service_section_sticy_header',
	'default'     => true,
] );

// Copyright field for Footer Area
Kirki::add_field(
	'techup_config', array(
		'type'     => 'toggle',
		'settings' => 'health_service_enable_footer_copyright_section',
		'label'    => __( 'Enable Footer Copyright Area', 'health-service' ),
		'section'  => 'health_service_section_footer_copyright',
		'default'  => '1',
		'priority' => 5,
	)
);
Kirki::add_field(
	'techup_config', array(
		'type'        => 'image',
		'settings'    => 'health_footer_bg_image',
		'label'       => esc_attr__( 'Footer Background Image', 'health-service' ),
		'section'     => 'health_service_section_footer_copyright',
		'default'     => esc_url(  get_template_directory_uri() . '/assets/images/banner.jpg' ),
		'priority' 	  => 10,
	)
);
 

// Textarea field for banner content
Kirki::add_field(
	'techup_config', array(
		'type'     => 'textarea',
		'settings' => 'health_service_cr_text',
		'label'    => __( 'Copyright Text', 'health-service' ),
		'section'  => 'health_service_section_footer_copyright',
        'default'  => '',
		'priority' => 20,
	)
);
