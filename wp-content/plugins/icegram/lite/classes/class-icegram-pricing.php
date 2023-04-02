<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Icegram_Pricing {

	public static function ig_show_pricing() {

		$utm_medium = 'ig-upgrade';
		$allowedtags = apply_filters( 'ig_escape_allowed_tags', array() );

		?>
		<style type="text/css">
			.update-nag {
				display: none;
			}
			.wrap.about-wrap.ig {
				margin: 0 auto;
				max-width: 100%;
			}
			body{
				background-color: white;
			}
			.ig_main_heading {
				font-size: 2em;
				background-color: #252f3f !important;
				color: #ffffff;
				text-align: center;
				font-weight: 500;
				margin: auto;
				padding-top: 0.75em;
				padding-bottom: 0.5em;
				/* max-width: 1375px; */
			}
			.ig_discount_code {
				/* color: #6875F5; */
				font-weight: 600;
				font-size: 2.5rem;
			}
			.ig_sub_headline {
				font-size: 1.6em;
				font-weight: 400;
				color: #00848D !important;
				text-align: center;
				line-height: 1.5em;
				margin: 0 auto 1em;
			}
			.ig_row {
				/* padding: 1em !important;
				margin: 1.5em !important; */
				clear: both;
				position: relative;
			}
			#ig_price_column_container {
				display: -webkit-box;
				display: -webkit-flex;
				display: -ms-flexbox;
				display: flex;
				max-width: 1190px;
				margin-right: auto;
				margin-left: auto;
				margin-top: 4em;
				padding-bottom: 4em;
			}
			.ig_column {
				padding: 2em;
				margin: 0 1em;
				background-color: #fff;
				border: 1px solid rgba(0, 0, 0, 0.1);
				text-align: center;
				color: rgba(0, 0, 0, 0.75);
			}
			.column_one_fourth {
				width: 30%;
				border-radius: 3px;
				margin-right: 4%;
			}
			.ig_last {
				margin-right: 0;
			}
			.ig_price {
				margin: 1.5em 0;
				color: #1e73be;
			}
			.ig_button {
				color: #FFFFFF !important;
				padding: 15px 32px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				font-size: 16px;
				font-weight: 500;
				margin: 2em 2px 1em 2px;
				cursor: pointer;
			}
			.ig_button.green {
				background: #23B191;
				border-color: #23B191;
			}
			.ig_button.green:hover {
				background: #66C78E;
				border-color: #66C78E;
			}

			.ig_button.small {
				text-transform: uppercase !important;
				box-shadow: none;
				padding: 0.8em;
				font-size: 1rem;
				border-radius: 0.25rem;
				margin-top: 1em;
				font-weight: 600;
			}
			.ig_discount_amount {
				font-size: 1.3em !important;
			}
			.dashicons.dashicons-yes {
				color: green;
				font-size: 2em;
			}
			.dashicons.dashicons-no-alt {
				color: #ed4337;
				font-size: 2em;
			}
			.dashicons.dashicons-yes.yellow {
				color: #BDB76B;
				line-height: unset;
			}
			.dashicons.dashicons-awards,
			.dashicons.dashicons-testimonial {
				margin-right: 0.25em !important;
				color: #15576F;
				font-size: 1.25em;
			}
			.ig_license_name {
				font-size: 1.1em !important;
				color: #1a72bf !important;
				font-weight: 500 !important;
			}
			.ig_old_price {
				font-size: 1.3em;
				color: #ed4337;
				vertical-align: top;
			}
			.ig_new_price {
				font-size: 1.6em;
				padding-left: 0.2em;
				font-weight: 400;
			}
			.ig_most_popular {
				position: absolute;
				right: 0px;
				top: -39px;
				background-color: #41495b;
				background-color: #596174;
				text-align: center;
				color: white;
				padding: 10px;
				font-size: 18px;
				border-top-right-radius: 4px;
				border-top-left-radius: 4px;
				font-weight: 500;
				width: 275px;
			}
			#ig-testimonial {
				text-align: center;
			}
			.ig-testimonial-content {
				width: 50%;
				margin: 0 auto;
				margin-bottom: 1em;
				background-color: #FCFEE9;
			}
			.ig-testimonial-content img {
				width: 12% !important;
				border-radius: 9999px;
				margin: 0 auto;
			}
			.ig_testimonial_headline {
				margin: 0.6em 0 !important;
				font-weight: 500 !important;
				font-size: 1.5em !important;
			}
			.ig_testimonial_text {
				text-align: left;
				font-size: 1.2em;
				line-height: 1.6;
				padding: 1em;
			}
			.pricing {
				border-radius: 5px;
				position: relative;
				padding: 0.25em;
				margin: 2em auto;
				background-color: #fff;
				border: 1px solid rgba(0, 0, 0, 0.1);
				text-align: center;
				color: rgba(0, 0, 0, 0.75);
			}
			.pricing h4 {
				margin-top: 1em;
				margin-bottom: 1em;
			}
			.pricing del {
				font-size: 1.3em;
				color: grey;
			}
			.pricing h2 {
				margin-top: 0!important;
				margin-bottom: 0.5em;
				text-align: center;
				font-weight: 600;
				line-height: 1.218;
				color: #515151;
				font-size: 2.5em;
			}
			.pricing p {
				text-align: center;
				margin: 0em;
			}
			.pricing:hover{
				border-color: #15576F;
			}
			.pricing.scaleup{
				transform: scale(1.2);
			}
			.fidget.spin{
				animation: spin 1.2s 0s linear both infinite;
			}
			@keyframes spin {
				0% {
						transform: rotate(0deg);
					}
				100% {
						transform: rotate(360deg);
					}
			}
			table.ig_feature_table {
				width: 90%;
				margin-left: 5%;
				margin-right: 5%;
			}
			table.ig_feature_table th,
			table.ig_feature_table tr,
			table.ig_feature_table td,
			table.ig_feature_table td span {
				padding: 0.5em;
				text-align: center !important;
				background-color: transparent !important;
				vertical-align: middle !important;
			}
			table.ig_feature_table,
			table.ig_feature_table th,
			table.ig_feature_table tr,
			table.ig_feature_table td {
				border: 1px solid #eaeaea;
			}
			table.ig_feature_table.widefat th,
			table.ig_feature_table.widefat td {
				color: #515151;
			}
			table.ig_feature_table th {
				font-weight: bolder !important;
				font-size: 1.3em;
			}
			table.ig_feature_table tr td {
				font-size: 15px;
			}
			table.ig_feature_table th.ig_features {
				background-color: #F4F4F4 !important;
				color: #A1A1A1 !important;
				width:16em;
			}
			table.ig_feature_table th.ig_free_features {
				background-color: #F7E9C8 !important;
				color: #D39E22 !important;
			}
			table.ig_feature_table th.ig_starter_features {
				background-color: #CCFCBF !important;
				color: #14C38E !important;
				width:16em;
			}
			table.ig_feature_table th.ig_pro_features {
				background-color: #DCDDFC !important;
				color: #6875F5 !important;
			}
			table.ig_feature_table td{
				padding: 0.5em;
			}
			table.ig_feature_table td.ig_feature_name {
				text-transform: capitalize;
				padding:1em 2em;
			}
			table.ig_feature_table td.ig_free_feature_name {
				background-color: #FCF7EC !important;
				padding:1em 2em;
			}
			table.ig_feature_table td.ig_starter_feature_name {
				background-color: #E3FCBF !important;
				padding:1em 3em;
			}
			table.ig_feature_table td.ig_pro_feature_name {
				background-color: #F4F5FD !important;
				padding:1em 2em;
			}
			#ig_product_page_link {
				text-align: center;
				font-size: 1.1em;
				margin-top: 2em;
				line-height: 2em;
			}
			.clr-a {
				color: #00848D !important;
			}
			.update-nag , .error, .updated{
				display:none;
			}
			table .dashicons {
				padding-top: 0 !important;
			}
			#wpcontent {
				padding-left: 0!important;
			}
			#ig-testimonial-others{
				margin-top: 4em;
			}
			#ig_comparison_table{
				margin-top: 4em;
			}

		</style>

		<div class="wrap about-wrap ig">
			<div class="ig_row" id="ig-pricing">
				<div class="ig_main_heading">
					<div style="display: inline-flex;">
						<div style="padding-right: 0.5rem;">🎊</div>
						<div style="line-height: 1.5rem;">
							<?php
								/* translators: %s: Offer text */
								echo sprintf( esc_html__( 'Congratulations! You just unlocked %s on Icegram Engage Premium!', 'icegram' ), '<span class="ig_discount_code">' . esc_html__( '25% off', 'icegram' ) . '</span>' );
							?>
						</div>
						<div style="padding-left: 0.5rem;">🎉</div>
					</div>
					<div style="padding-top: 1em;font-size: 0.5em;"><?php echo esc_html__( '⏰ Limited time offer', 'icegram' ); ?></div>
				</div>
				<div id="ig_price_column_container">
					<div class="ig_column column_one_fourth pricing scaleup" style="border-color: #15576F;padding: 0;border-width: 0.2em;">
						<div style="text-align: center;background-color: #15576F;color: #FFF;padding: 1em;font-weight: 900;text-transform: uppercase;"> <?php echo esc_html__( 'Best Seller', 'icegram' ); ?> </div>
						<span class="ig_plan"><h4 class="clr-a center"><?php echo esc_html__( 'Max', 'icegram' ); ?></h4></span>
						<span class="ig_plan"><h4 class="clr-a center"><?php echo esc_html__( '3 sites (Annual)', 'icegram' ); ?></h4></span>
						<span class="ig_price">
							<p><del class="center"><?php echo esc_html__( '$229', 'icegram' ); ?></del></p>
							<h2><?php echo esc_html__( '$172', 'icegram' ); ?></h2>
						</span>

						<div class="center">
							<a class="ig_button small green center" href="https://www.icegram.com/?buy-now=16542&qty=1&coupon=ig-upgrade-25&with-cart=1&utm_source=ig_in_app&utm_medium=<?php echo esc_attr( $utm_medium ); ?>&utm_campaign=ig-max" target="_blank" rel="noopener"><?php esc_html_e( 'Buy Now', 'icegram' ); ?><span style="width: 1em; height: 1em; background-image: url('https://www.storeapps.org/wp-content/themes/storeapps/assets/images/fidget.svg'); display: inline-block; margin-left: 0.5em" class="fidget spin"></span></a>
						</div>
					</div>
					<div class="ig_column column_one_fourth pricing ig_lifetime_price">
						<span class="ig_plan"><h4 class="clr-a center"><?php echo esc_html__( 'Pro', 'icegram' ); ?></h4></span>
						<span class="ig_plan"><h4 class="clr-a center"><?php echo esc_html__( '1 site (Annual)', 'icegram' ); ?></h4></span>
						<span class="ig_price">
							<p><del class="center"><?php echo esc_html__( '$129', 'icegram' ); ?></del></p>
							<h2><?php echo esc_html__( '$97', 'icegram' ); ?></h2>
						</span>

						<div class="center">
							<a class="ig_button small green center" href="https://www.icegram.com/?buy-now=16522&qty=1&coupon=ig-upgrade-25&with-cart=1&utm_source=ig_in_app&utm_medium=<?php echo esc_attr( $utm_medium ); ?>&utm_campaign=ig-pro" target="_blank" rel="noopener"><?php esc_html_e( 'Buy Now', 'icegram' ); ?></a>
						</div>
					</div>

				</div>
			</div>
			<div class="ig_row" id="ig-testimonial">
				<div class="ig_column ig-testimonial-content">
					<?php
					echo wp_kses( apply_filters( 'ig_pricing_page_testimonial_1', '<img src="' . IG_PLUGIN_URL . 'lite/assets/images/Annie Lax.png" alt="mnmatty" />
						<div class="ig_testimonial_text">
							' . __( 'I performed a lot of research, went with my intuition, and purchased the Icegram add-ons. I’m so happy with this decision as I’m extremely pleased with the action bars, sidebars, stickies, ribbons, popups … well, everything!', 'icegram' ) . '<br><br>
							- ' . __( 'Annie Lax', 'icegram' ) . '
						</div>' ), $allowedtags );
					?>
				</div>
			</div>
			<div class="ig_row" id="ig_comparison_table">
				<div class="ig_sub_headline"><span class="dashicons dashicons-awards"></span><?php echo esc_html__( ' More powerful features with Icegram Engage!', 'icegram' ); ?></div>
				<table class="ig_feature_table wp-list-table widefat">
					<thead>
						<tr>
							<th class="ig_features">
								<?php echo esc_html__( 'Features', 'icegram' ); ?>
							</th>
							<th class="ig_free_features">
								<?php echo esc_html__( 'Free', 'icegram' ); ?>
							</th>
							<th class="ig_starter_features">
								<?php echo esc_html__( 'Pro', 'icegram' ); ?>
							</th>
							<th class="ig_pro_features">
								<?php echo esc_html__( 'Max', 'icegram' ); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="ig_feature_name">
								<strong><?php echo esc_html__( 'What\'s so special?', 'icegram' ); ?></strong>
							</td>
							<td class="ig_free_feature_name">
								<?php echo esc_html__( 'Unlimited Campaigns, Unlimited Impressions and Unlimited Pageviews', 'icegram' ); ?>
							</td>
							<td class="ig_starter_feature_name">
								<?php echo esc_html__( 'Everything in Free +', 'icegram' ); ?>
							</td>
							<td class="ig_pro_feature_name">
								<?php echo esc_html__( 'Everything in Pro +', 'icegram' ); ?>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Popups', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Action Bars', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Toasts', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Messengers', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Badges', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Inline message', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Stickies', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Ribbons', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Interstitial', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Overlay', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Tab', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Sidebar', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Basic targeting rules', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Two-step optin', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Exit Intent targeting', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'User behavior targeting', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Geographical Targeting', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Simple design themes', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Elegant design templates gallery', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Countdown Timer', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'After CTA Click Control', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Top 5 message stats', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Top 5 campaign stats', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Impression v/s Conversion report', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( '100+ high converting themes', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'A/B Split Testing', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Show / Hide Animation Effects', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Premium themes', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Remote (Show Campaigns on non WordPress sites)', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_starter_feature_name">
								<span class='dashicons dashicons-no-alt'></span>
							</td>
							<td class="ig_pro_feature_name">
								<span class='dashicons dashicons-yes'></span>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Support', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<?php echo esc_html__( 'WordPress Forum Support', 'icegram' ); ?>
							</td>
							<td class="ig_starter_feature_name">
								<?php echo esc_html__( 'Premium Support (Email)', 'icegram' ); ?>
							</td>
							<td class="ig_pro_feature_name">
								<?php echo esc_html__( 'VIP Support (Email + Facebook)', 'icegram' ); ?>
							</td>
						</tr>
						<tr>
							<td class="ig_feature_name">
								<?php echo esc_html__( 'Pricing', 'icegram' ); ?>
							</td>
							<td class="ig_free_feature_name">
								<span class=''>Free</span>
							</td>
							<td class="ig_starter_feature_name">
								<div class="center">
									<a class="ig_button small green center" href="https://www.icegram.com/?buy-now=16522&qty=1&coupon=ig-upgrade-25&with-cart=1&utm_source=ig_in_app&utm_medium=<?php echo esc_attr( $utm_medium ); ?>&utm_campaign=ig-pro" target="_blank" style="text-transform: none;"><?php esc_html_e( 'Buy Pro', 'icegram' ); ?></a>
								</div>
							</td>
							<td class="ig_pro_feature_name">
									<div class="center">
										<a class="ig_button small green center" href="https://www.icegram.com/?buy-now=16542&qty=1&coupon=ig-upgrade-25&with-cart=1&utm_source=ig_in_app&utm_medium=<?php echo esc_attr( $utm_medium ); ?>&utm_campaign=ig-max" target="_blank" style="text-transform: none;"><?php esc_html_e( 'Buy Max', 'icegram' ); ?></a>
									</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="ig_row" id="ig-testimonial-others">
				<div style="width: 70%; margin: 0 auto; display: flex; gap: 2em;">
					<div class="ig_column ig-testimonial-content">
						<?php
							echo wp_kses( apply_filters( 'ig_pricing_page_testimonial_2',
							'<div class="ig_testimonial_text">
							    <div class="font-semibold text-lg pb-2"></div>
								' . __( 'Worked great with my theme. I was able to add a call to action footer bar on my homepage. I can’t believe all the customizations you can do for free. I was even able to add shortcode to the bottom bar and it worked beautifully.', 'icegram' ) . '<br><br>
								- ' . __( 'David McCaleb', 'icegram' ) . '
							</div>' ), $allowedtags );
						?>
					</div>
					<div class="ig_column ig-testimonial-content">
						<?php
							echo wp_kses( apply_filters( 'ig_pricing_page_testimonial_3',
							'<div class="ig_testimonial_text">
							    <div class="font-semibold text-lg pb-2"></div>
								' . __( 'We’ve been using Icegram Engage as well as the compatible Icegram Express plugin from Icegram for blog subscription and lead generation forms. It’s easy to set up and it does what it’s supposed to do. Thank you!', 'icegram' ) . '<br><br>
								- ' . __( 'IrinaT', 'icegram' ) . '
							</div>' ), $allowedtags );
						?>
					</div>
				</div>
			</div>
			<div class="ig_row" id="ig_product_page_link">
				<?php 
					/* translators: %s: Pricing page URL */
					echo sprintf( esc_html__( 'You can either lose all your potential website visitors or use %s to grab their attention and convert them into customers.', 'icegram' ), '<a style="color: #00848D;" target="_blank" href="https://www.icegram.com/all-features/?utm_source=ig_in_app&utm_medium=in_app_pricing&utm_campaign=ig_pricing_footer">' . esc_html__( 'Icegram Engage', 'icegram' ) . '</a>' );
				?>
				<br>
			</div>
		</div>
		<?php
	}
}

new Icegram_Pricing();
