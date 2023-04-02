<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if( isset( $_POST['submit'] ) && isset( $_POST['ig-settings-nonce'] ) && wp_verify_nonce( $_POST['ig-settings-nonce'], 'ig_settings' ) ) {
	if( isset( $_POST['icegram_share_love'] ) ) {
		update_option( 'icegram_share_love', sanitize_text_field($_POST['icegram_share_love']) );
	} else {		
		update_option( 'icegram_share_love', 'no' );
	}
	if( isset( $_POST['icegram_cache_compatibility'] ) ) {
		update_option( 'icegram_cache_compatibility', sanitize_text_field($_POST['icegram_cache_compatibility']) );
	} else {		
		update_option( 'icegram_cache_compatibility', 'no' );
	}
	if( isset( $_POST['icegram_track_usage_enable'] ) ) {
		update_option( 'ig_allow_tracking', sanitize_text_field($_POST['icegram_track_usage_enable']) );
	} else {		
		update_option( 'ig_allow_tracking', 'no' );
	}
}

?>
<div class="wrap">
	<h2><?php _e( "Icegram Engage Settings", "icegram" ) ?></h2>
	<form name="icegram_settings" method="POST" action="<?php echo admin_url(); ?>edit.php?post_type=ig_campaign&page=icegram-settings">
		<table class="form-table">
	        <tr>
				<th scope="row"><?php _e( 'Share Icegram', 'icegram' ) ?></th>
				<td>
	                <label for="icegram_share_love">
	                    <input type="checkbox" name="icegram_share_love" id="icegram_share_love"/ value="yes" <?php checked('yes', get_option('icegram_share_love')); ?> />
	                    <?php _e( 'Show "Powered by" link', 'icegram' ); ?>                        
	                </label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Cache Friendly?', 'icegram' ) ?></th>
				<td>
	                <label for="icegram_cache_compatibility">
	                    <input type="checkbox" name="icegram_cache_compatibility" id="icegram_cache_compatibility"/ value="yes" <?php checked('yes', get_option('icegram_cache_compatibility')); ?> />
	                    <?php _e( 'Turn on "Lazy Load" - load Icegram Engage scripts and messages after page load to avoid caching problems.', 'icegram' ); ?>                        
	                </label>
				</td>
			</tr>
			<?php
				$product_plan = get_option( 'ig_engage_plan', 'lite' );
				if( 'lite' === $product_plan ){
			?>
				<tr>
					<th scope="row"><?php _e( 'Plugin Usage Tracking', 'icegram' ) ?></th>
					<td>
						<label for="icegram_track_usage_enable">
							<input type="checkbox" name="icegram_track_usage_enable" id="icegram_track_usage_enable"/ value="yes" <?php checked('yes', get_option('ig_allow_tracking')); ?> />
							<?php _e( 'Enable Tracking Usage - Help us to improve Icegram by opting in to share non-sensitive plugin usage data.', 'icegram' ); ?>                        
						</label>
					</td>
				</tr>
			<?php 
				}
			?>
		</table>
		<?php
			wp_nonce_field( 'ig_settings', 'ig-settings-nonce' );
			
			submit_button(); 
		?>
	</form>
</div>
<?php do_action( 'icegram_settings_after' ); 

