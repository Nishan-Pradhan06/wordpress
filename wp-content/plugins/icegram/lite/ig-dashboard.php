<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $icegram; 

$number_of_days = 60;
$total_active_campaigns = 0;
$avg_conversion_rate = 0;

if( ! $icegram->is_premium() ){
	$campaigns = json_decode(json_encode( Icegram_Stats::get_last_campaign_post_data( 5 ) ), true);
} else{
	$campaigns = Ig_Analytics::get_last_campaign_data( 5 );
	$last_n_days_stats = Ig_Analytics::get_last_campaign_total_stats( 60 );
	$max_converted_campaign = Ig_Analytics::get_max_converted_campaign( 60 );
	$max_converted_campaign_title = ! empty( $max_converted_campaign ) ? $max_converted_campaign['post_title'] : '-';
	
	if( $last_n_days_stats['total_shown'] ) {
		$avg_conversion_rate = ( $last_n_days_stats['total_clicked'] / $last_n_days_stats['total_shown'] ) * 100;
	}
}
$total_active_campaigns = wp_count_posts( 'ig_campaign')->publish;

$tips_blocks = array(

	'welcome_visitors' => array(
		'title'        => __( 'Welcome your visitors with an exclusive offer', 'icegram' ),
		'goals' 	   => '56',
		'message-type' => '0',
	),

	'lead_magnet_subscribers'   => array(
		'title'        			=> __( 'Get more followers for your lead magnet', 'icegram' ),
		'goals' 	   			=> '57',
		'message-type' 			=> '0',
	),

	'subscription_box' 	   	=> array(
		'title'        		=> __( 'Create an elegant subscription box', 'icegram' ),
		'goals' 	   		=> '55',
		'message-type'      => '0',
	),

	'lock_content' 	   		=> array(
		'title'        		=> __( 'Lock your page with a gated-content optin', 'icegram' ),
		'goals' 	   		=> '65',
		'message-type'      => '0',
	),

);


$tips_blocks = apply_filters( 'ig_admin_dashboard_tips_blocks', $tips_blocks );

$topics = Icegram::get_useful_articles();

$topics_indexes = array_rand( $topics, 3 );

?>
<div class="icegram_tw">
	<div class="wrap pt-4 font-sans" id="ig-container">
		<header class="mx-auto max-w-7xl">
			<div class="md:flex md:items-center md:justify-between">
				<div class="flex-1 min-w-0">
					<h2 class="text-3xl font-bold leading-7 text-gray-700 sm:leading-9 sm:truncate">
						<?php echo esc_html__( 'Dashboard', 'icegram' ); ?>
					</h2>
				</div>
			</div>
		</header>

		<main class="mx-auto max-w-7xl">
			<section class="ig-dashboard-stats-item py-4 my-8 bg-white rounded-lg shadow md:flex md:items-start md:justify-between sm:px-4 sm:grid sm:grid-cols-3 relative overflow-hidden">
				<div class="w-4/12 min-w-0">
					<div class="flex p-4 text-gray-600">
						<div class="w-2/5">
							<img
						class="absolute w-20 -mt-3"
						src= "<?php echo esc_url( IG_PLUGIN_URL . 'lite/assets/images/anne-portray.png' ); ?>"
						/>
						</div>
						<div class="mt-5">
							<div class="p-1">
								<span class="text-5xl font-bold leading-none text-indigo-600"><?php echo esc_html( $total_active_campaigns ); ?>					
								</span>
								<p class="mt-1 text-base font-medium leading-6 text-gray-500">
								<?php echo esc_html__( 'Active Campaigns', 'icegram' ); ?>						
								</p>
							</div>
						</div>
					</div>
				</div>
				<?php if( ! $icegram->is_premium() ){ ?>
					<a href="https://www.icegram.com/engage/pricing/?utm_source=in_app_new&utm_medium=dashboard_analytics&utm_campaign=ig_upsell"> <img src="<?php echo esc_url( IG_PLUGIN_URL . 'lite/assets/images/upsell/dashboard-last-60-days.png') ?>"></a>
				<?php } else{ ?>
				<div class="w-4/12 px-3 flex-auto">
					<p class="px-1 text-lg font-medium leading-6 text-gray-400">
						<?php echo esc_html__( 'Last 60 days', 'icegram' ); ?>
					</p>
					<div class="pt-2">
						<div class="p-1">
							<span class="text-xl font-bold leading-none text-indigo-600"><?php echo esc_html( round( $avg_conversion_rate, 2 ) ); ?>					
							% </span>
							<p style="font-size:0.8rem" class="mt-1 text-sm font-medium leading-6 text-gray-500">
							<?php echo esc_html__( 'Average conversion rate', 'icegram' ); ?>						
							</p>
						</div>
						<div class="pt-5 px-1 pb-1 ">
							<span style="font-size: 1.1rem;" class="font-bold leading-none text-indigo-600"><?php echo esc_html( $max_converted_campaign_title ); ?>					
							</span>
							<p style="font-size:0.8rem" class="mt-1 font-medium leading-6 text-gray-500">
							<?php echo esc_html__( 'Most conversion campaign', 'icegram' ); ?>						
							</p>
						</div>
					</div>
				</div>
			<?php } ?>
				<div class="flex-auto">
					<div class="overflow-hidden">
						<ul>
							<li class="ml-1.5 text-lg font-medium leading-6 text-gray-400">
								<?php echo esc_html__( 'How To\'s', 'icegram' ); ?></li>
							<?php foreach ( $topics_indexes as $index ) { ?>
								<li class="border-b border-gray-200 mb-0">
									<a href="<?php echo esc_url( $topics[ $index ]['link'] ); ?>" class="block transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:bg-gray-50" target="_blank">

										<div class="flex items-center px-2 py-2 md:justify-between">
											<div class="text-sm leading-5 text-gray-900">
												<?php
												echo wp_kses_post( $topics[ $index ]['title'] );
												if ( ! empty( $topics[ $index ]['label'] ) ) {
													?>
													<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo esc_attr( $topics[ $index ]['label_class'] ); ?>"><?php echo esc_html( $topics[ $index ]['label'] ); ?></span>
												<?php } ?>
											</div>
											<div>
												<svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
													<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
												</svg>
											</div>
										</div>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</section>

			<section class="my-16">
				<div class="grid grid-cols-4 gap-8 sm:grid-cols-2 lg:grid-cols-3">
				<?php
				foreach ( $tips_blocks as $tips => $data ) {
					?>
					<div id="ig-<?php echo esc_attr( $tips ); ?>-block" class="relative p-4 rounded-lg shadow bg-white">
						<h3 class="text-base font-medium leading-6 text-gray-900">
							<?php echo esc_html( $data['title'] ); ?>
						</h3>
						
						<div class="block-description pt-4" style="width: calc(100% - 4rem)">
							<a id="ig-<?php echo esc_attr( $tips ); ?>-cta" href="post-new.php?post_type=ig_campaign&use_case=<?php echo esc_html($data['goals']) ?>&message_type=<?php echo esc_html($data['message-type']) ?>" target="_blank" class="font-medium hover:underline tips-templates">

								<?php echo esc_html( 'Add now', 'icegram' ); ?> &rarr;
							</a>
						</div>
					</div>
					<?php
				}
				?>
				</div>
			</section>

			<section class="my-12">
				<header class="md:flex md:items-center md:justify-between mb-3 border-b border-gray-200">
						<div class="flex">
							<h3 class="text-lg font-bold leading-7 text-gray-700 sm:text-2xl sm:leading-9 sm:truncate">
								<?php echo esc_html__( 'Marketing Tips & Updates', 'icegram' ); ?>
							</h3>
						</div>
					</header>
				<div class="relative p-4 rounded-lg shadow bg-white">
					<?php do_action( 'icegram_settings_after' ) ?>
				</div>
			</section>
			<?php
			if ( ! empty( $campaigns ) && count( $campaigns ) > 0 ) { ?>
				<section class="my-4">
					<header class="md:flex md:items-center md:justify-between mb-3 border-b border-gray-200">
						<div class="flex">
							<h3 class="text-lg font-bold leading-7 text-gray-700 sm:text-2xl sm:leading-9 sm:truncate">
								<?php echo esc_html__( 'Recent Campaigns', 'icegram' ); ?>
							</h3>
						</div>
					</header>

					<div class="bg-white shadow overflow-hidden sm:rounded-md">
						<ul>
							<?php 
							foreach ( $campaigns as $campaign_id => $campaign ) {
								?>
								<li class="border-b border-gray-200">
									<a href="post.php?post=<?php echo $campaign['ID'] ?>&action=edit" class="block py-1 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out" target="_blank">
										<div class="flex items-center px-4 py-2 sm:px-6">
											<div class="w-3/5 min-w-0 flex-1">
												<div class="text-base text-gray-600 mt-2 pr-4">
													<?php echo esc_html( $campaign['post_title'] ); ?>
												</div>
											</div>
											<?php if( $icegram->is_premium() ){ ?>
											<div class="sm:grid sm:grid-cols-3 flex-1">
												<?php 
													$impressions = ! empty($campaign['total_shown'] ) ? $campaign['total_shown'] : 0 ;
													$conversions = ! empty($campaign['total_clicked'] ) ? $campaign['total_clicked']  : 0 ;
												?>
												<div class="p-3">
															<span class="leading-none text-sm text-indigo-500">
															  <?php echo esc_html( number_format_i18n( $impressions ) ); ?>
															</span>
													<p class="mt-1 leading-6 text-gray-400">
														<?php echo esc_html__( 'Impressions', 'icegram' ); ?>
													</p>
												</div>
												<div class="p-3">
															<span class="leading-none text-sm text-indigo-500">
																	<?php echo esc_html( number_format_i18n( $conversions ) ); ?>
																  </span>
													<p class="mt-1 leading-6 text-gray-400">
														<?php echo esc_html__( 'CTA Clicks', 'icegram' ); ?>
													</p>
												</div>
												<div class="p-3">
															<span class="leading-none text-sm text-indigo-500">
																   <?php echo esc_html( round( $campaign['conversion_per'], 2 ) ); ?> 
																%
																  </span>
													<p class="mt-1 leading-6 text-gray-400">
														<?php echo esc_html__( 'Conversion Rate', 'icegram' ); ?>
													</p>
												</div>
											</div>
										<?php } ?>
											<div>
												<svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
													<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
												</svg>
											</div>
										</div>
									</a>
								</li>
								<?php
							}
							
							?>
						</ul>
					</div>
				</section>
			<?php 
			} 
			?>
		</main>
	</div>
</div>