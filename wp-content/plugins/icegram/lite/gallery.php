<style type="text/css">
	.ig-gallery-wrap .theme-browser .theme{
		border-radius: 5px;
		border: none;
		margin-bottom: 5%;
		box-shadow: 1px 3px 10px 0 rgba(0,0,0,0.15);
	}
	.ig-gallery-wrap .theme-browser .theme:hover{
		box-shadow: 1px 3px 10px 0 rgba(0,0,0,0.2);
	}
	.ig-gallery-wrap.wrap > h2:first-child{
		padding-left: 0 !important; 
	}
	.ig-gallery-wrap .theme-browser .theme .theme-screenshot{
		border-radius: 5px;
	}
	.ig-gallery-wrap .theme-browser .theme .theme-screenshot img{
		height: 100%;
	}
	.ig-gallery-wrap .theme-browser .theme .theme-installed{
		width: 2em;
		padding: 0;
		height: 2em;
	}
	.ig-gallery-wrap .theme-browser .theme .theme-installed:before{
		font-size: 30px;
		top:-2px;
		left:-4px;
	}
	.expanded .wp-full-overlay-footer.ig-get-pro-footer{
		position: fixed;
		bottom: 60px;
		left: 0;
		height: 24px;
		background: #dcdcdc;
		text-align: center;
		padding-top: 0.2em;
		border-top: 1px dashed #ddd;
		/*border-bottom: 1px dashed #ddd;*/
	}
	.expanded .wp-full-overlay-footer.ig-get-pro-footer span{
		/*color: #900101;*/
	}
	.wp-full-overlay-connect{
		background-color: #fff;
		width: 100%;
		height: 100%;
	}

	/* New CSS*/
	.igg-sidebar {
		float: left;
		width: 12.9%;
		margin: 0 2% 0 0;
		border-right: 1px solid #d2d2d2;
	}
	.igg-content {
		float: left;
		width: 85%;
	}
	.igg-sidebar .category > h2{
		/*margin-bottom: 0.5em;*/
		margin-top: 0.5em;
		margin-right: 0.5em;
		margin-bottom: 0;
		padding: 0.5em;
		cursor: pointer;
		transition: all 0.1s;
		border-radius: 3px;
		/*background-color: #e0e0e0;*/
		color: #909090;
		font-size: 1.15em;
	}
	
	/*.igg-sidebar .category > h2:hover{
		background-color: orange;
		color: #ffffff;
		}*/

		.igg-sidebar .category > ul{
			/*margin-left: 1em;*/
			padding-bottom: 0.5em;
			margin-right: 1em;
			margin-top:0.5em;
			/*margin-bottom:1em;*/
			cursor: pointer;
			border-bottom: 1px solid #dedede;
		}

		.igg-sidebar .category > ul > li {
			padding: 0.25em 1em;
			margin: 0;
			border-radius: 2px;
			transition: all 0.1s;
		}
		.igg-sidebar .category > ul > li:hover, .igg-sidebar .category > ul > li.active {
			background-color: #5f3af1;
			color: white;
		}

		.igg-content .theme {
			border: 7px solid white !important;
		}

		.igg-content .theme .title {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			max-width: 75%;
			color: #b7b7b7;
			/*font-weight:200;*/
			font-size: 0.85em;
		}

		.pills {
			padding: 0.3em 0.6em;
			background-color: #c5c5c5;
			color: #ffffff;
			font-size:1em;
		}
		.sq.pills {
			border-radius: 3px;
		}
		.pills.sm{
			font-size: 0.75em;
		}
		.pills.md{
			font-size: 1em;
		}
		.pills.lg{
			font-size: 1.3em;
			padding: 0.4em 0.75em;
		}
		.pills.upc{
			text-transform: uppercase;
		}
		.pills.cap{
			text-transform: capitalize;
		}

		.btn {
			padding: 0.75em 1em;
			color: #ffffff;
			border-radius: 3px;
			text-decoration: none;
		}
		.btn:hover {
			color: #ffffff;
			background-color
		}

		.btn.purple {
			background-color: hsl(244, 70%, 60%);
		}
		.btn.purple:hover {
			background-color: hsl(244, 70%, 45%);

		}
		.btn.green {
			background-color: hsl(169, 79%, 40%);;
		}
		.btn.green:hover {
			background-color: hsl(169, 79%, 25%);;
		}

		.igg-content .theme .pills.free,
		.igg-content .theme .pills.plus {
			display: none;
		}

		.igg-content .theme .pills.pro {
			background-color: #16b99a;
		}

		.igg-content .theme .pills.max {
			background-color: #5f3af1;
		}

		.igg-content .theme .locked {
			color: #e4951b;
		}

		.igg-content .theme .unlocked {
			color: #bdbdbd;
		}

		.ig-gallery-wrap .float-left {
			float: left;
		}
		.ig-gallery-wrap .float-right {
			float: right;
		}

		.ig-gallery-wrap .theme-browser {
			clear:both;
		}
		.ig-gallery-wrap .theme-browser .themes{
			clear:both;
		}

		.igg-content .filter-header {
			color: #888888;
			margin: 0 0 2em 0;
			/* display: none; */
			border-bottom: 1px solid #e6e6e6;
			padding-bottom: 1em;
		}
		.igg-content .filter-header .meta {
			font-size: 1em;
			color: #bbbbbb;
	    /*margin-top: 1em;
	    padding-top: 0.5em;*/
	    display: inline-block;
	}

	/*.igg-content .category-title-def,*/
	.igg-content .category-type,
	.igg-content .category-title{
		background-color: #cacaca;
		cursor:pointer;
	}
	.igg-content .filter-header .dashicons-arrow-right {``
		color: #cacaca;
	}

	.igg-content .sub-category-title {
		background-color: #7971f1;
		cursor:pointer;
	}

	.igg-preview-sidebar .wp-full-overlay-sidebar-content .theme-screenshot {
		padding: 0.5em;
		border-radius: 3px;
		border: 1px solid #e4e4e4;
		margin-bottom: 0 !important;
	}
	.igg-preview-sidebar .wp-full-overlay-sidebar-content .theme-description {
		float: none;
		margin-top: 0.25em;
		margin-bottom: 0.5em;
		border-bottom: 1px solid #e6e6e6;
		padding-bottom: 0.5em;
	}
	.igg-preview-sidebar .wp-full-overlay-sidebar-content .theme-name {
		line-height: 18px;
		margin-bottom: 0.25em;
	}
	.igg-preview-sidebar .wp-full-overlay-sidebar-content .theme-info {
		display: unset;
	}
	.igg-preview-sidebar .wp-full-overlay-header .theme-install {
		float: right;
		line-height: 10px;
		margin:8px 10px 0 0;
	}
	.igg-preview-sidebar .wp-full-overlay-sidebar-content .theme-by a {
		text-decoration: none;
		color: #2a25de;
		font-style: italic;
	}

	.igg-preview-sidebar .wp-full-overlay-sidebar-content .tags {
		margin: 1em 0;
	}
	.igg-preview-sidebar .wp-full-overlay-sidebar-content .tags .plan {
		background-color: #7971f1;
	}

	.igg-preview-sidebar .wp-full-overlay-sidebar-content .tags .category {
		background-color: #16b99a;
	}

	.igg-preview-sidebar .wp-full-overlay-sidebar-content .tags .meta {
		margin-top:0.5em;
		font-size: 0.95em;
	}
	.igg-preview-sidebar .wp-full-overlay-sidebar-content .tags .meta .icon {
		color: #d0d0d0;
	}
	.igg-preview-sidebar .wp-full-overlay-sidebar-content .tags .meta .lbl {
		color: #9a9a9a;
		font-style: italic;
	}
	.igg-sidebar .search-form #wp-filter-search-input{
		width:13em;
		/*float: left;*/
		/*display: inline;*/
		/*border-bottom: 1px solid #ddd;*/
	}
	[type="checkbox"]:checked + .ig-select-campaign-type, [type="checkbox"]:checked + .ig-sub-selection-goal {
		transition-property: all;
		transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
		transition-duration: 100ms; 
		transform: scale(1.02);
		background-color: rgba(249, 250, 251, 1);
		border-width: 1.75px;
		border-color: rgba(229, 231, 235, 1);
	}

	[type="checkbox"]:not(:checked) + .ig-sub-selection-goal:hover, [type="checkbox"]:not(:checked) + .ig-select-campaign-type:hover {
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
		border-color: #ebf5ff;
    	border-color: rgba(235, 245, 255, 1);
	}

	[type="checkbox"]:checked + .single-goal {
		background-color: rgba(213, 245, 246, 1);
	}

	.select2-container{
		margin: 0 2px 0 2px;
	}

	@media only screen and (min-width: 1200px) {
		.select2-container{
			width: 36.5% !important;
		}
	}
	@media only screen and (min-width: 1300px) {
		.select2-container{
			width: 37.75% !important;
			margin: 0 2px 0 2px;
		}
	}

	.select2-search__field{
		width: 100% !important;
	}

	.select2-selection__rendered{
		height:2.35rem;
	}

	.campaign_filters .select2-selection{
		border: 1px solid #837e7e;
	}	
	.campaign_filters .select2-container--default .select2-selection--multiple .select2-selection__choice {
		margin-bottom: 0;
		margin-top: 4px;
	}

	.campaign_filters .select2-container .select2-search--inline {
		margin-bottom: 0;
	}

	.campaign_filters .select2-container:not(.select2-container--focus) .select2-search--inline:not(:only-child) {
		display: none;
	}

	.campaign_filters .select2-container .select2-search--inline .select2-search__field {
		margin-top: 0;
		min-height: 20px;
		height: 24px;
	}

	.campaign_filters .select2-container .select2-selection--multiple {
		min-height: 30px;
	}

	.ig-sub-selection-goal {
		height: 12rem !important;
	}

</style>

<div class="wrap ig-gallery-wrap">
	<div class="wp-clearfix">
		<?php 
		global $icegram;

		$campaign_types = array(
			'popup' 	  => array(
				'term-id' 	=> 25,
				'category'  => 'message-type',
				'type' 	  	=> 'popup',
				'plan' 	  	=> 'free',
				'title'   	=> __('Popup', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-popup-158X127.png',
			),
			'action-bar' => array(
				'term-id' 	=> 23,
				'category'  => 'message-type',
				'type' 	  	=> 'action-bar',
				'plan' 	  	=> 'free',
				'title'   	=> __('Action Bar', 'icegram'),
				'img-path' 	=> 'lite/assets/images/action-bar-158X127.png',
			),
			'messenger'  => array(
				'term-id' 	=> 24,
				'category'  => 'message-type',
				'type' 	  	=> 'messenger',
				'plan' 	  	=> 'free',
				'title'   	=> __('Messenger', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-messenger-158X127.png',
			),
			'inline' 	 => array(
				'term-id' 	=> 31,
				'category'  => 'message-type',
				'type' 	  	=> 'inline',
				'plan' 	  	=> 'free',
				'title'   	=> __('Inline', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-inline-158X127.png',
			),
			'badges' 	 => array(
				'term-id' 	=> 35,
				'category'  => 'message-type',
				'type' 	  	=> 'badge',
				'plan' 	  	=> 'free',
				'title'   	=> __('Badges', 'icegram'),
				'img-path' 	=> 'lite/assets/images/badges-158X127.png',
			),
			'toast'	  	 => array(
				'term-id' 	=> 34,
				'category'  => 'message-type',
				'type' 	  	=> 'toast',
				'plan' 	  	=> 'free',
				'title'   	=> __('Toast', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-toast-notification-158X127.png',
			),
			'overlay' 	 => array(
				'term-id' 	=> 39,
				'category'  => 'message-type',
				'type' 	  	=> 'overlay',
				'plan' 	  	=> 'free',
				'title'   	=> __('Overlay', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-overlay-158X127.png',
			),
			'sticky' 	 => array(
				'term-id' 	=> 37,
				'category'  => 'message-type',
				'type' 	  	=> 'sticky',
				'plan' 	  	=> 'pro',
				'title'   	=> __('Sticky', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-stickies-158X127.png',
			),
			'ribbon' 	 => array(
				'term-id' 	=> 36,
				'category'  => 'message-type',
				'type' 	  	=> 'ribbon',
				'plan' 	  	=> 'pro',
				'title'   	=> __('Ribbon', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-ribbons-158X127.png',
			),
			'tab' 		=> array(
				'term-id' 	=> 41,
				'category'  => 'message-type',
				'type' 	  	=> 'tab',
				'plan' 	  	=> 'max',
				'title'   	=> __( 'Tab', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-tab-158X127.png',
			),
			'sidebar' 	 => array(
				'term-id' 	=> 40,
				'category'  => 'message-type',
				'type' 	  	=> 'sidebar',
				'plan' 	  	=> 'max',
				'title'   	=> __('Sidebar', 'icegram'),
				'img-path' 	=> 'lite/assets/images/sketch-sidebar-158X127.png',
			),
		);

		$campaign_types = apply_filters( 'ig_get_widget_message_types' , $campaign_types );

		$onboarding_campaign_goal = array(
			'newsletter' => array(
				'term-id' 	=> 55,
				'category'  => 'use-case',
				'title'   	=> __('Grow audience', 'icegram'),
				'desc'   	=> __('Build your lists with newsletter subscription opt-ins', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />/svg>',
				'checked'   => true,
			),
			'guide-visitors' => array(
				'term-id' 	=> 63,
				'category'  => 'use-case',
				'title'   	=> __('Guide visitors', 'icegram'),
				'desc'   	=> __('Call to actions to nudge people to visit a page or link', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>',
			),
			'lead-magnet' => array(
				'term-id' 	=> 57,
				'category'  => 'use-case',
				'title'   	=> __('Giveaway downloadable resources', 'icegram'),
				'desc'   	=> __('Have a lead magnet? Deliver it when people sign up to your list', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
			),
			'announcement' => array(
				'term-id' 	=> 62,
				'category'  => 'use-case',
				'title'   	=> __('Make announcements', 'icegram'),
				'desc'   	=> __('Show important information or news to visitors', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>',
			),
			'drive-sales' => array(
				'term-id' 	=> 56,
				'category'  => 'use-case',
				'title'   	=> __('Grow revenue', 'icegram'),
				'desc'   	=> __('Make special offers, show coupons and reduce cart abandonment', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>',
			),
			'callback' => array(
				'term-id' 	=> 60,
				'category'  => 'use-case',
				'title'   	=> __('Get inbound leads', 'icegram'),
				'desc'   	=> __('Show phone call back, text SMS or mail contact information easily', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>',
			),
			'social' => array(
				'term-id' 	=> 30,
				'category'  => 'use-case',
				'title'   	=> __('Increase social media followers', 'icegram'),
				'desc'   	=> __('Show elegant social media icons to gain followers', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
			),
			'yes-no' => array(
				'term-id' 	=> 44,
				'category'  => 'use-case',
				'title'   	=> __('Collect feedback', 'icegram'),
				'desc'   	=> __('Run a mini survey with Yes/No campaign and drive people to act', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>',
			),
			'scratch' => array(
				'term-id' 	=> 51,
				'category'  => 'use-case',
				'title'   	=> __('Build your own', 'icegram'),
				'desc'   	=> __('Start with a blank template and customize it your way', 'icegram'),
				'icon' 		=> '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>',
				'allow-multiple-goal' => "no",
			),
		);

		$campaign_goal = array_merge( array(
		  	'lock_content' => array(
				'term-id' 	=> 65,
				'category'  => 'use-case',
				'title'   	=> __('Lock your content', 'icegram'),
			),
		), $onboarding_campaign_goal);
		$show_onboarding = $icegram->show_campaign_creation_guide();

		if ( $show_onboarding ) {

			// Import data if not done already
			if ( false === get_option( 'icegram_sample_data_imported' ) ) {
				$icegram->import_sample_data( $icegram->get_sample_data() );
			}
		?>
	
	<div class="select-item icegram_tw">
		<div class="mx-auto mt-6 sm:mt-5 ig-logo">
			<img src="<?php echo esc_url( IG_PLUGIN_URL . 'lite/assets/images/icegram_logo.svg' ); ?>" class="mx-auto h-6 border-0" alt="Icegram" />
		</div>

		<div id="slider-wrapper" class="font-sans">
			<div id="slider">
				<div class="ig-onboarding-campaign-goal" style="">
					<section class="mx-auto my-6 sm:my-7">
						<div class="w-full h-full overflow-hidden bg-white lg:flex md:rounded-lg md:shadow-xl md:mx-auto lg:max-w-3xl xl:max-w-4xl">
							<div class="flex-1 ">
								<div class="p-5 md:px-8 md:py-5">
									<h3	class="text-center mb-1 text-2xl font-bold leading-snug text-gray-800">
										<?php echo esc_html__( 'Goal for this campaign', 'icegram' ); ?>
									</h3>
									<p class="text-center title-font text-base text-gray-800 mb-3"><?php echo esc_html__( 'What should this campaign help you achieve? Please select a primary goal or more', 'icegram' ); ?></p>
									<div class="select-item py-2">
										<div class="inline-block p-2 overflow-hidden text-left align-bottom sm:w-full">
											<div class="container px-5 py-2 mx-auto ig-section-sub">
												<div class="flex flex-wrap -m-4">
													<?php
													foreach ($onboarding_campaign_goal as $goal) {
														$bg_css = isset( $goal['allow-multiple-goal'] ) && 'no' === $goal['allow-multiple-goal']  ? 'bg-teal-50 single-goal' : '';
														?>
														<div class="xl:w-1/3 md:w-1/2 p-4 h-full ig-select-sub ">
															<label class="cursor-pointer hover:shadow-lg hover:border-transparent">
																<input type="checkbox" name="ig-campaign-goal" class="absolute w-0 h-0 opacity-0" category="usecase" usecase="<?php echo esc_attr( $goal['term-id'] ) ?>" value="<?php echo esc_html( $goal['title'] ) ?>" <?php echo isset( $goal['checked'] ) ? 'checked' : '' ?> allow-multiple-goal="<?php echo isset( $goal['allow-multiple-goal'] ) ? esc_html( $goal['allow-multiple-goal'] ) : 'yes' ?>" >

																<div class="border border-gray-200 p-4 rounded-lg ig-sub-selection-goal <?php echo esc_html($bg_css) ?>">
																	<p>
																		<svg id="usecase_<?php echo esc_attr( $goal['term-id'] ) ?>" class="selected_goals hidden float-right h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
																		  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
																		</svg>
																	</p>
																	<div class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-purple-100 text-purple-500 mb-4">
																		<?php echo $goal['icon']; 
																		?>
																	</div>
																	<p class="text-lg text-gray-900 font-medium title-font inline break-words relative bottom-1"><?php echo esc_html( $goal['title'] ) ?></p>
																	<p class="leading-relaxed text-base"><?php echo esc_html( $goal['desc'] ) ?></p>
																</div>
															</label>
														</div>
														<?php
													}
													?>
												</div>
											</div>
										</div>

									</div>										
								</div>
								<div class="px-4 py-3 text-right bg-gray-50 md:px-8 -mt-5">
									<button
									type="button" id="ig-select-goal" class="relative inline-flex items-center px-2 py-1 text-base font-medium leading-5 text-white bg-indigo-800 border border-transparent rounded-md hover:bg-indigo-600 focus:outline-none focus:shadow-outline">
									<?php echo esc_html__( 'Continue →', 'icegram' ); ?>
								</button>
							</div>
						</div>
					</section>
				</div>
				<div class="ig-onboarding-campaign-type" style="display: none">
					<section class="mx-auto my-6 sm:my-7">
						<div class="w-full h-full overflow-hidden bg-white lg:flex md:rounded-lg md:shadow-xl md:mx-auto lg:max-w-3xl xl:max-w-4xl">
							<div class="flex-1">
								<div class="p-5 md:px-8 md:py-5">
									<h3 class=" text-center mb-2 text-2xl font-bold leading-snug text-gray-800 sm:text-3xl">
										<?php echo esc_html__( 'Message placement', 'icegram' ); ?>
									</h3>
									<p class="px-5 text-center title-font text-base text-gray-800 mb-3"><?php echo esc_html__( 'What message types would you like to use in this campaign? Select all that you like - or just continue without selecting if you’re not sure.', 'icegram' ); ?></p>
									<div class="select-item py-2">
										<div class="inline-block p-2 overflow-hidden text-left align-bottom sm:w-full">
											<div class="container px-5 py-2 mx-auto">
												<ul role="list" class="grid gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
													<?php
													foreach ($campaign_types as $campaign_type) {
														?>
														<li class="relative cursor-pointer radio-select ig-selection-section" >
															<label class="inline items-center cursor-pointer">
																<input type="checkbox" name="ig-campaign-type" class="absolute w-0 h-0 opacity-0" category="message-type" message-type="<?php echo esc_attr( $campaign_type['term-id']) ?>" value="<?php echo esc_html( $campaign_type['title'] ) ?>">
																<div class="ig-select-campaign-type text-center border border-gray-100 rounded-lg px-4 py-2">
																	<p class="h-6">
																		<svg id="message-type_<?php echo esc_attr( $campaign_type['term-id'] ) ?>" class="hidden float-right h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
																		  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
																		</svg>
																	</p>
																	<div class="group w-full aspect-w-10 aspect-h-7 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 focus-within:ring-indigo-500 overflow-hidden">

																		<img src="<?php echo esc_url( IG_PLUGIN_URL . $campaign_type['img-path'] ); ?>" alt="" class="w-full pointer-events-none">

																	</div>
																	<?php
																	if( ( $icegram->is_pro() && ! $icegram->is_max() && 'max' === $campaign_type['plan'] ) || ( ! $icegram->is_premium() && in_array( $campaign_type['plan'], array('max', 'pro') ) ) ) {

																		?>
																		<span class="float-right border border-blue rounded py-0.5 px-2 bg-blue-700 text-white -ml-12 -mr-1"><?php echo esc_html( ucfirst( $campaign_type['plan'] ) ); ?></span>
																		<?php
																	}
																	?>
																	<span class="text-center block text-sm font-medium text-gray-900 truncate pointer-events-none"><?php echo esc_html( $campaign_type['title']) ?></span>
																</div>
															</label>
														</li>
														<?php
													}
													?>
												</ul>
											</div>
										</div>
									</div>

								</div>
								<div class="px-4 py-3 text-right bg-gray-50 md:px-8">
									<button type="button" class="ig-message-type-select relative inline-flex items-center px-3 py-1 text-base font-medium leading-5 text-white bg-indigo-800 border border-transparent rounded-md hover:bg-indigo-600 focus:outline-none focus:shadow-outline">
										<?php echo esc_html__( 'Continue →', 'icegram' ); ?>
									</button>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
<!--  Sidebar - quick filtering and searching -->
<div class="description" style="padding-bottom:2em; <?php echo ( $show_onboarding ) ? 'display: none' : '' ?>">
	<h2 style="font-size:17px;font-weight: 500">
		<?php echo ( $show_onboarding ) ? esc_html__('Choose a design', 'icegram') : esc_html__( 'Icegram Engage design templates', 'icegram' ) ?>
		
	</h2>
	<div style="padding-bottom:3px;font-size:14px">
		
	</div>
	<div style="font-size:14px">
		<?php echo ( $show_onboarding ) ? esc_html__('Here\'s some gallery templates filtered out for you. Simply ' ,'icegram') : sprintf( esc_html('Filter out the templates based on %1s  and %2s and then simply','icegram_'), '<strong>your goals</strong>', '<strong>message type</strong>' );

			echo  sprintf( esc_html__(' click to %s and the campaign will automatically appear in your Icegram Engage dashboard. No coding or special skills required.','icegram'), '<strong>Use This</strong>')  ?>
	</div>
</div>
<div class="igg-sidebar" style="display:none">
	<form class="search-form"></form>
	<div class="meta"></div>
	<div class="category reset">
		<ul><li class="category-type" category="reset">Reset</li></ul>
	</div>
	<?php  
	foreach ($cat_list as $category) {
		$not_have_sub_cat = (empty($category['list'])) ? 'not_have_sub_cat' : '';
		?>
		<div class="category <?php echo $category['slug']?> <?php echo $not_have_sub_cat?>" category="<?php echo $category['slug']?>"  <?php echo $category['slug']?>="<?php echo $category['term_id']?>" >
			<h2><?php  _e($category['name'], 'icegram')?></h2>
			<ul>
				<?php 
				if(!empty($category['list'])){
					foreach ($category['list'] as $sub_cat) {
						?>
						<li class="category-type" category="<?php echo $category['slug']?>" <?php echo $category['slug']?>="<?php echo $sub_cat['term_id']?>" ><?php _e($sub_cat['name'], 'icegram')?></li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		<?php
	}

	?>
</div>

<div class="igg-filters campaign_filters align-right icegram_tw" style="<?php echo ( $show_onboarding ) ? 'display: none' : '' ?>">
	<div class="inline-block ml-6 w-11/12 inline mb-2 campaign-goal">
		<select class="gallery_campaign_goal select2-hidden-accessible overflow-hidden" multiple="multiple" name="gallery_campaign_goal" tabindex="-1" aria-hidden="true" placeholder="Select goal">
			<option value=""><?php echo esc_html__( 'Any campaign', 'icegram' ); ?></option>
			<?php 
			foreach ($campaign_goal as $goal) { ?>
				<option class="goal_options" category="<?php echo esc_attr( $goal['category'] ) ?>" use-case="<?php echo esc_attr( $goal['term-id'] ) ?>" value="<?php echo esc_attr( $goal['title'] ) ?>"><?php echo esc_html( $goal['title']); ?></option>
			<?php } 
			?>
		</select>
		<select class="gallery_message_type select2-hidden-accessible" multiple="" name="gallery_message_type" tabindex="-1" aria-hidden="true" placeholder="Select message type">
			<?php 
			foreach ($campaign_types as $campaign_type) { ?>
				<option class="message_type_options" category="<?php echo esc_attr( $campaign_type['category'] ) ?>" message-type="<?php echo esc_attr( $campaign_type['term-id'] ) ?>" value="<?php echo esc_attr( $campaign_type['title'] ) ?>"><?php echo esc_html( $campaign_type['title']); ?></option>
			<?php } 
			?>
		</select>

		<div class="inline-block mx-1 mt-4">
			<p class="gallery_filters cursor-pointer relative inline-flex text-sm items-center -mt-2 mx-1.5 px-3 py-1 font-medium leading-5 text-white bg-indigo-800 border border-transparent rounded-md hover:bg-indigo-600 focus:outline-none focus:shadow-outline"><?php echo esc_html__('Filter Templates', 'icegram'); ?></p>
		</div>
		<div class="category inline-block mx-1 mt-3">
			<p class="ig-reset-button category-type inline cursor-pointer border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white transition duration-150 ease-in-out px-3 py-1" category="reset"><?php echo esc_html__('Show All', 'icegram'); ?></p>
		</div>
	</div>

</div>
<hr style="margin:1.5rem 0;">
<div class="igg-content" style="width: 100%; <?php echo ( $show_onboarding ) ? 'display: none' : '' ?>">
	
	

	<div class="theme-browser">
		<div class="themes wp-clearfix"></div>
	</div>
</div>


</div>
<div class="theme-install-overlay wp-full-overlay expanded"></div>
<!-- <div class="theme-overlay"></div> -->
</div><!-- .wrap -->
<script id="tmpl-theme" type="text/template">
	<# if ( data.image ) { #>
	<div class="theme-screenshot">
		<div class="wp-clearfix">
			<img src="{{ data.image.guid }}" alt="" />
		</div>
	</div>
	<# } else { #>
	<div class="theme-screenshot blank"></div>
	<# } #>
	<span class="more-details" id="{{ data.id }}-action"><?php _e( 'Preview' ); ?></span>
	<div class="theme-author"><?php printf( __( 'By %s' ), '{{{ data.id }}}' ); ?></div>
	<div class="theme-id-container">
		<div class="theme-name wp-clearfix">
			<!-- <span>Active:</span> -->
			
			<!-- Theme title or description.. -->
			<# if( data.title.rendered ) { #>
			<span class="float-left title" title="{{ data.title.rendered }}">{{ data.title.rendered }}</span>
			<# } #>

			<!--  For logos and labels-->
			<div class="float-right">
				<!-- logo -->
				
				<!-- label -->
				<# if( data.plan_name ) { #>
				<span class="sq pills sm cap {{data.plan_name}}" title="{{ data.plan_name }}">{{ data.plan_name }}</span>
				<# } #>
			</div>
		</div>
	</div>
</script>
<!-- TODO:: Remove it if not required -->

<script id="tmpl-theme-preview" type="text/template">
	<div class="wp-full-overlay-sidebar igg-preview-sidebar">
		<div class="wp-full-overlay-header">
			<a href="#" class="close-full-overlay"><span class="screen-reader-text"><?php _e( 'Close' ); ?></span></a>
			<a href="#" class="previous-theme"><span class="screen-reader-text"><?php _ex( 'Previous', 'Button label for a theme' ); ?></span></a>
			<a href="#" class="next-theme"><span class="screen-reader-text"><?php _ex( 'Next', 'Button label for a theme' ); ?></span></a>
			<a href="?action=fetch_messages&campaign_id={{data.campaign_id}}&gallery_item={{data.slug}}" class="btn purple theme-install" style="display:none"><?php _e( 'Use This', 'icegram' ); ?></a>
			<a href="https://www.icegram.com/pricing/?utm_source=ig_inapp&utm_medium=ig_gallery&utm_campaign=get_pro" target="_blank" class="ig-get-pro btn green" style="display:none;">
				<# if(data.plan === '3') { #>
				<span><?php _e("Get The Max Plan", 'icegram') ?></span>
				<# } else if(data.plan === '2') { #>
				<span><?php _e("Get The Pro Plan", 'icegram') ?></span>
				<# } #>	
			</a>
		</div>
		<div class="wp-full-overlay-sidebar-content">
			<div class="install-theme-info">
				<h3 class="theme-name">{{ data.title.rendered }}</h3>
				<span class="theme-by">
					<a href="https://www.icegram.com/" target="_blank"><?php printf( __( '- By %s' ), 'Icegram Engage' ); ?></a>
				</span>

				<img class="theme-screenshot" src="{{ data.image.guid }}" alt="">

				<div class="theme-details">
					<div class="theme-description">{{ data.description }}</div>
					<div class="tags">
						<!-- plan-name -->
						<# if(data.plan_name) { #>
						<span class="sq pills sm cap plan">{{data.plan_name}}</span>
						<# } #>	
						<!-- categories -->
						
						<# _.each(data.category_names,function(cname){ #> 
						<# if(cname != 'no category') { #>
						<span class="sq pills sm cap category">{{cname}}</span>
						<# } #>	
						<# }) #>
					</div>
					<!-- <div class="theme-info">Liked this template? <br/>Here's how you can customize it further </div> -->
					<div class="theme-info" style="padding:0.2em;height:auto;">
						<p style="border-top: 1px solid #f3f3f3;padding: 1em 0;margin:0.5em 0;"><?php _e( 'Would you like to personalize this template to fit your brand?', 'icegram' );?></p>
						<div>
							<a href="https://www.icegram.com/documentation/customize-icegrams-gallery-templates/?utm_source=ig_gallery&utm_medium=ig_inapp_promo&utm_campaign=ig_custom_css" target="_blank" class="" style="margin-top:0.4em;"><?php _e( 'Personalize It Now' , 'icegram'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="wp-full-overlay-footer ig-get-pro ig-get-pro-footer " style="display:none">
			<# if(data.plan === '3') { #>
			<span><?php _e("This template is available in the 'Max Plan'", 'icegram') ?></span>
			<# } else if(data.plan === '2') { #>
			<span><?php _e("This template is available in the 'Pro Plan'", 'icegram') ?></span>
			<# } #>
		</div>
		<div class="wp-full-overlay-footer">
			<button type="button" class="collapse-sidebar button-secondary" aria-expanded="true" aria-label="<?php esc_attr_e( 'Collapse Sidebar' ); ?>">
				<span class="collapse-sidebar-arrow"></span>
				<span class="collapse-sidebar-label"><?php _e( 'Collapse' ); ?></span>
			</button>
		</div>
	</div>
	<div class="wp-full-overlay-main">
		<iframe src="{{ data.link }}?utm_source=ig_inapp&utm_campaign=ig_gallery&utm_medium={{data.campaign_id}}" title="<?php esc_attr_e( 'Preview' ); ?>" />
		</div>
	</script>

	<script type="text/javascript">
		jQuery(document).ready(function() {

			jQuery('.category-type').on('click', function() {
				jQuery('.category-type').removeClass('active');
				jQuery(this).addClass('active');
			});

		});

	</script>